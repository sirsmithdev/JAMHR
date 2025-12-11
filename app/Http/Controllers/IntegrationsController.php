<?php

namespace App\Http\Controllers;

use App\Models\OauthConnection;
use App\Services\GoogleWorkspaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationsController extends Controller
{
    /**
     * Display integrations settings page
     */
    public function index()
    {
        $connections = OauthConnection::with('connectedBy')
            ->orderBy('provider')
            ->get()
            ->keyBy('provider');

        $googleInfo = GoogleWorkspaceService::getConnectionInfo();

        return view('settings.integrations', [
            'connections' => $connections,
            'googleInfo' => $googleInfo,
            'googleScopes' => GoogleWorkspaceService::getAvailableScopes(),
            'activeTab' => 'integrations',
        ]);
    }

    /**
     * Initiate Google OAuth flow
     */
    public function googleRedirect(Request $request)
    {
        $scopes = [];

        // Add selected scopes
        if ($request->has('calendar')) {
            $scopes[] = \Google\Service\Calendar::CALENDAR;
        }
        if ($request->has('directory')) {
            $scopes[] = \Google\Service\Directory::ADMIN_DIRECTORY_USER_READONLY;
        }

        $service = new GoogleWorkspaceService();
        $authUrl = $service->getAuthUrl($scopes);

        // Store selected scopes in session for callback
        session(['google_requested_scopes' => $scopes]);

        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Google authorization was cancelled or failed: ' . $request->get('error'));
        }

        if (!$request->has('code')) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Invalid callback - no authorization code received.');
        }

        try {
            $service = new GoogleWorkspaceService();
            $connection = $service->handleCallback(
                $request->get('code'),
                Auth::id()
            );

            return redirect()->route('settings.integrations')
                ->with('success', 'Google Workspace connected successfully! Connected as: ' . $connection->email);
        } catch (\Exception $e) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Failed to connect Google Workspace: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google integration
     */
    public function googleDisconnect()
    {
        $service = new GoogleWorkspaceService();
        $service->disconnect();

        return redirect()->route('settings.integrations')
            ->with('success', 'Google Workspace has been disconnected.');
    }

    /**
     * Test Google Calendar integration
     */
    public function googleTestCalendar()
    {
        $service = new GoogleWorkspaceService();
        $events = $service->listCalendarEvents('primary', 5);

        if (empty($events) && !GoogleWorkspaceService::isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Google Workspace is not connected.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Calendar access working!',
            'events' => $events,
        ]);
    }

    /**
     * Microsoft OAuth redirect (placeholder)
     */
    public function microsoftRedirect(Request $request)
    {
        // TODO: Implement Microsoft OAuth
        return redirect()->route('settings.integrations')
            ->with('info', 'Microsoft Teams integration coming soon!');
    }

    /**
     * Microsoft OAuth callback (placeholder)
     */
    public function microsoftCallback(Request $request)
    {
        // TODO: Implement Microsoft OAuth callback
        return redirect()->route('settings.integrations');
    }

    /**
     * Disconnect Microsoft integration (placeholder)
     */
    public function microsoftDisconnect()
    {
        OauthConnection::where('provider', 'microsoft')->delete();

        return redirect()->route('settings.integrations')
            ->with('success', 'Microsoft has been disconnected.');
    }
}
