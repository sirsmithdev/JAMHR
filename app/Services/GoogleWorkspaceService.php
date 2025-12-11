<?php

namespace App\Services;

use App\Models\OauthConnection;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Directory;
use Google\Service\Oauth2;
use Illuminate\Support\Facades\Log;

class GoogleWorkspaceService
{
    protected GoogleClient $client;
    protected ?OauthConnection $connection = null;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);
    }

    /**
     * Get the authorization URL for OAuth consent
     */
    public function getAuthUrl(array $scopes = []): string
    {
        $defaultScopes = [
            'openid',
            'email',
            'profile',
        ];

        $this->client->setScopes(array_merge($defaultScopes, $scopes));

        return $this->client->createAuthUrl();
    }

    /**
     * Get available scopes for selection
     */
    public static function getAvailableScopes(): array
    {
        return [
            'calendar' => [
                'scope' => Calendar::CALENDAR,
                'name' => 'Google Calendar',
                'description' => 'Read and write calendar events',
            ],
            'calendar_readonly' => [
                'scope' => Calendar::CALENDAR_READONLY,
                'name' => 'Google Calendar (Read Only)',
                'description' => 'Read calendar events',
            ],
            'directory_readonly' => [
                'scope' => Directory::ADMIN_DIRECTORY_USER_READONLY,
                'name' => 'Directory (Read Only)',
                'description' => 'Read organization users (requires Workspace Admin)',
            ],
        ];
    }

    /**
     * Exchange authorization code for tokens
     */
    public function handleCallback(string $code, int $userId): OauthConnection
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new \Exception('Google OAuth error: ' . ($token['error_description'] ?? $token['error']));
        }

        $this->client->setAccessToken($token);

        // Get user info
        $oauth2 = new Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();

        // Store or update connection
        $connection = OauthConnection::updateOrCreate(
            ['provider' => 'google'],
            [
                'provider_user_id' => $userInfo->getId(),
                'email' => $userInfo->getEmail(),
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'token_expires_at' => isset($token['expires_in'])
                    ? now()->addSeconds($token['expires_in'])
                    : null,
                'scopes' => $token['scope'] ?? null,
                'metadata' => [
                    'name' => $userInfo->getName(),
                    'picture' => $userInfo->getPicture(),
                    'hd' => $userInfo->getHd(), // Hosted domain (Workspace domain)
                ],
                'is_active' => true,
                'connected_by' => $userId,
            ]
        );

        return $connection;
    }

    /**
     * Load existing connection and refresh token if needed
     */
    public function loadConnection(): bool
    {
        $this->connection = OauthConnection::forProvider('google');

        if (!$this->connection) {
            return false;
        }

        $this->client->setAccessToken([
            'access_token' => $this->connection->access_token,
            'refresh_token' => $this->connection->refresh_token,
            'expires_in' => $this->connection->token_expires_at
                ? $this->connection->token_expires_at->diffInSeconds(now())
                : 3600,
        ]);

        // Refresh token if expired
        if ($this->client->isAccessTokenExpired() && $this->connection->refresh_token) {
            $this->refreshToken();
        }

        return true;
    }

    /**
     * Refresh the access token
     */
    protected function refreshToken(): void
    {
        try {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->connection->refresh_token);

            if (isset($newToken['error'])) {
                Log::error('Google token refresh failed', $newToken);
                $this->connection->update(['is_active' => false]);
                return;
            }

            $this->connection->update([
                'access_token' => $newToken['access_token'],
                'token_expires_at' => isset($newToken['expires_in'])
                    ? now()->addSeconds($newToken['expires_in'])
                    : null,
            ]);

            $this->client->setAccessToken($newToken);
        } catch (\Exception $e) {
            Log::error('Google token refresh exception: ' . $e->getMessage());
            $this->connection->update(['is_active' => false]);
        }
    }

    /**
     * Disconnect Google integration
     */
    public function disconnect(): bool
    {
        $connection = OauthConnection::forProvider('google');

        if ($connection) {
            // Optionally revoke token
            try {
                $this->client->revokeToken($connection->access_token);
            } catch (\Exception $e) {
                // Token may already be invalid
            }

            $connection->delete();
            return true;
        }

        return false;
    }

    /**
     * Get Calendar service
     */
    public function getCalendarService(): ?Calendar
    {
        if (!$this->loadConnection()) {
            return null;
        }

        return new Calendar($this->client);
    }

    /**
     * Get Directory service (requires Workspace admin)
     */
    public function getDirectoryService(): ?Directory
    {
        if (!$this->loadConnection()) {
            return null;
        }

        return new Directory($this->client);
    }

    /**
     * Create a calendar event (e.g., for leave request)
     */
    public function createCalendarEvent(array $eventData, string $calendarId = 'primary'): ?array
    {
        $calendar = $this->getCalendarService();
        if (!$calendar) {
            return null;
        }

        try {
            $event = new Calendar\Event([
                'summary' => $eventData['title'],
                'description' => $eventData['description'] ?? '',
                'start' => [
                    'date' => $eventData['start_date'], // YYYY-MM-DD for all-day
                ],
                'end' => [
                    'date' => $eventData['end_date'],
                ],
            ]);

            $createdEvent = $calendar->events->insert($calendarId, $event);

            return [
                'id' => $createdEvent->getId(),
                'link' => $createdEvent->getHtmlLink(),
            ];
        } catch (\Exception $e) {
            Log::error('Google Calendar event creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * List upcoming calendar events
     */
    public function listCalendarEvents(string $calendarId = 'primary', int $maxResults = 10): array
    {
        $calendar = $this->getCalendarService();
        if (!$calendar) {
            return [];
        }

        try {
            $events = $calendar->events->listEvents($calendarId, [
                'maxResults' => $maxResults,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => now()->toRfc3339String(),
            ]);

            return array_map(function ($event) {
                return [
                    'id' => $event->getId(),
                    'title' => $event->getSummary(),
                    'start' => $event->getStart()->getDateTime() ?? $event->getStart()->getDate(),
                    'end' => $event->getEnd()->getDateTime() ?? $event->getEnd()->getDate(),
                    'link' => $event->getHtmlLink(),
                ];
            }, $events->getItems());
        } catch (\Exception $e) {
            Log::error('Google Calendar list failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if connected
     */
    public static function isConnected(): bool
    {
        return OauthConnection::where('provider', 'google')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get connection details
     */
    public static function getConnectionInfo(): ?array
    {
        $connection = OauthConnection::forProvider('google');

        if (!$connection) {
            return null;
        }

        return [
            'email' => $connection->email,
            'name' => $connection->metadata['name'] ?? null,
            'domain' => $connection->metadata['hd'] ?? null,
            'connected_at' => $connection->created_at,
            'connected_by' => $connection->connectedBy?->name,
        ];
    }
}
