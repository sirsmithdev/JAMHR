# JamHR Deployment Guide

This guide covers deploying JamHR to DigitalOcean App Platform.

## Prerequisites

- DigitalOcean account
- GitHub repository with the JamHR codebase
- Domain name (optional, but recommended)

## Quick Deploy

1. **Fork/Clone the Repository**
   Ensure your code is in a GitHub repository connected to your DigitalOcean account.

2. **Create App on DigitalOcean**
   - Go to [DigitalOcean App Platform](https://cloud.digitalocean.com/apps)
   - Click "Create App"
   - Select your GitHub repository
   - DigitalOcean will detect the `.do/app.yaml` configuration

3. **Configure Environment Variables**
   Set these required variables in the App Platform dashboard:
   - `APP_KEY`: Generate with `php artisan key:generate --show`
   - `APP_URL`: Your application URL (e.g., `https://jamhr.yourdomain.com`)
   - Mail settings (if using email features)

4. **Deploy**
   Click "Create Resources" and wait for the build to complete.

## Manual Deployment Steps

### Option 1: Using App Spec (Recommended)

1. Install the [doctl CLI](https://docs.digitalocean.com/reference/doctl/how-to/install/)

2. Authenticate:
   ```bash
   doctl auth init
   ```

3. Create the app:
   ```bash
   cd laravel-app
   doctl apps create --spec .do/app.yaml
   ```

### Option 2: Using Docker

1. Build the Docker image locally:
   ```bash
   docker build -t jamhr:latest .
   ```

2. Push to DigitalOcean Container Registry:
   ```bash
   doctl registry login
   docker tag jamhr:latest registry.digitalocean.com/your-registry/jamhr:latest
   docker push registry.digitalocean.com/your-registry/jamhr:latest
   ```

3. Deploy using the container image in App Platform.

## Database Setup

### Using DigitalOcean Managed Database (PostgreSQL)

The `.do/app.yaml` configuration automatically provisions a PostgreSQL database. The `DATABASE_URL` environment variable is automatically injected.

### Manual Database Setup

1. Create a Managed Database cluster in DigitalOcean
2. Get the connection string from the database dashboard
3. Set environment variables:
   ```
   DB_CONNECTION=pgsql
   DATABASE_URL=postgres://user:password@host:port/database?sslmode=require
   ```

## Environment Variables Reference

### Required Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_KEY` | Laravel encryption key | `base64:xxxxx` |
| `APP_URL` | Application URL | `https://jamhr.example.com` |
| `DATABASE_URL` | Database connection string | Auto-injected by App Platform |

### Optional Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `LOG_CHANNEL` | Logging channel | `stderr` |
| `MAIL_MAILER` | Mail driver | `smtp` |
| `MAIL_HOST` | SMTP host | - |
| `MAIL_PORT` | SMTP port | `587` |
| `MAIL_USERNAME` | SMTP username | - |
| `MAIL_PASSWORD` | SMTP password | - |
| `REDIS_URL` | Redis connection | - |

## Scaling

### Horizontal Scaling

Update `instance_count` in `.do/app.yaml`:

```yaml
services:
  - name: web
    instance_count: 3  # Scale to 3 instances
```

### Vertical Scaling

Change `instance_size_slug` in `.do/app.yaml`:

```yaml
services:
  - name: web
    instance_size_slug: professional-s  # Upgrade to small
```

Available sizes:
- `professional-xs` (512 MB RAM, 1 vCPU)
- `professional-s` (1 GB RAM, 1 vCPU)
- `professional-m` (2 GB RAM, 2 vCPUs)
- `professional-l` (4 GB RAM, 2 vCPUs)

## Background Jobs

### Enable Queue Worker

Uncomment the worker service in `.do/app.yaml`:

```yaml
services:
  - name: worker
    dockerfile_path: Dockerfile
    run_command: php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

### Enable Scheduler

Uncomment the scheduler in `docker/supervisord.conf`:

```ini
[program:laravel-scheduler]
command=/bin/sh -c "while [ true ]; do /usr/local/bin/php /var/www/html/artisan schedule:run; sleep 60; done"
```

## SSL/HTTPS

DigitalOcean App Platform automatically provisions SSL certificates for:
- `.ondigitalocean.app` domains (free subdomain)
- Custom domains (automatic Let's Encrypt certificates)

To add a custom domain:
1. Go to your app's Settings > Domains
2. Add your domain
3. Update your DNS records as instructed

## Monitoring

### Health Checks

The application includes a health endpoint at `/health` that returns:
```json
{
  "status": "healthy",
  "timestamp": "2025-01-01T00:00:00.000Z"
}
```

### Logs

View logs in the App Platform dashboard or via CLI:
```bash
doctl apps logs <app-id> --type=run
```

### Metrics

App Platform provides built-in metrics for:
- CPU usage
- Memory usage
- HTTP request rates
- Response times

## Troubleshooting

### Build Failures

1. Check build logs in App Platform dashboard
2. Ensure all dependencies are in `composer.json` and `package.json`
3. Verify PHP version compatibility (requires 8.2+)

### Database Connection Issues

1. Verify `DATABASE_URL` is set correctly
2. Check that the database is in the same region
3. Ensure SSL mode is enabled (`?sslmode=require`)

### 502 Bad Gateway

1. Check application logs for PHP errors
2. Verify health check endpoint responds
3. Increase instance size if running out of memory

### Asset Loading Issues

1. Run `php artisan storage:link` in the container
2. Verify `APP_URL` is set correctly
3. Check that Vite build completed successfully

## Local Development with Docker

```bash
# Build and run locally
docker build -t jamhr:dev .
docker run -p 8080:8080 \
  -e APP_KEY=base64:your-key-here \
  -e APP_ENV=local \
  -e APP_DEBUG=true \
  jamhr:dev
```

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] HTTPS enforced (automatic with App Platform)
- [ ] Database SSL enabled
- [ ] Sensitive environment variables marked as secrets
- [ ] CORS configured appropriately
- [ ] Rate limiting enabled

## Support

For issues specific to:
- **JamHR Application**: Check the application logs and GitHub issues
- **DigitalOcean App Platform**: Contact [DigitalOcean Support](https://www.digitalocean.com/support/)
