# Deployment Guide

This guide covers deploying your Laravel + Tailwind CSS application to a remote server.

## What You DON'T Need on Remote Server

- ❌ **Herd** - Only for local development
- ❌ **Vite dev server** (`npm run dev`) - Only for local development
- ❌ **php artisan serve** - Only for local development/testing

## What You DO Need on Remote Server

### Required Software
- ✅ **PHP 8.2+** with required extensions
- ✅ **Composer** - PHP dependency manager
- ✅ **Node.js & npm** - For building assets (can be removed after build)
- ✅ **Web Server** - Nginx or Apache
- ✅ **Database** - MySQL, PostgreSQL, or SQLite (depending on your setup)

### Optional (Recommended)
- ✅ **Process Manager** - Supervisor, PM2, or systemd for queues/workers
- ✅ **SSL Certificate** - Let's Encrypt for HTTPS

---

## Deployment Options

### 1. Traditional VPS/Server (Ubuntu, Debian, etc.)

**Web Server Options:**
- **Nginx** (recommended) - Fast, modern, great for Laravel
- **Apache** - Traditional, widely used (your `.htaccess` file supports this)

**Setup Steps:**
1. Install PHP, Composer, Node.js, and web server
2. Configure web server to point to `public/` directory
3. Set proper file permissions
4. Configure environment variables (`.env`)
5. Build assets and deploy

### 2. Platform-as-a-Service (PaaS)

**Popular Options:**
- **Laravel Forge** - Managed Laravel hosting
- **Laravel Vapor** - Serverless Laravel (AWS)
- **Ploi** - Server management platform
- **DigitalOcean App Platform** - Managed hosting
- **Heroku** - Platform-as-a-Service

These platforms handle most of the server setup for you.

### 3. Shared Hosting

If using shared hosting:
- Ensure PHP 8.2+ is available
- Point document root to `public/` directory
- May need to build assets locally and upload `public/build/` folder

---

## Deployment Checklist

### Before Deployment

1. **Build Assets**
   ```bash
   npm run build
   ```
   This creates optimized CSS/JS in `public/build/`

2. **Set Environment to Production**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Optimize Laravel**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

### On Remote Server

1. **Upload Files** (via Git, FTP, SCP, etc.)
   ```bash
   # Example: Clone from Git
   git clone your-repo.git
   cd your-app
   ```

2. **Install Dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Edit .env with production settings
   ```

4. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Cache Configuration**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Web Server Configuration Examples

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache Configuration

Your `.htaccess` file in `public/` should handle most routing. Ensure:
- `mod_rewrite` is enabled
- Document root points to `public/` directory
- PHP version is 8.2+

---

## Important Notes

### Assets After Deployment

- **You don't need Node.js/npm running** on the server after building
- The `public/build/` folder contains all compiled assets
- Laravel serves these static files directly through the web server

### Environment Variables

Make sure to set these in production `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### Security

- Never commit `.env` file
- Use strong `APP_KEY`
- Set proper file permissions
- Use HTTPS in production
- Keep dependencies updated

---

## Quick Deploy Script Example

```bash
#!/bin/bash
# deploy.sh

# Pull latest code
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

---

## Troubleshooting

### Assets Not Loading
- Verify `public/build/manifest.json` exists
- Check `public/build/assets/` contains CSS/JS files
- Ensure web server can read `public/` directory

### 500 Errors
- Check file permissions on `storage/` and `bootstrap/cache/`
- Verify `.env` is configured correctly
- Check Laravel logs: `storage/logs/laravel.log`

### Database Issues
- Verify database credentials in `.env`
- Ensure database exists and user has proper permissions
- Run migrations: `php artisan migrate`

