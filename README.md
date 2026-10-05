<!-- To add a logo, uncomment and edit the next line:
<p align="center"><img src="docs/logo.png" width="200" alt="Your App Name logo"></p>
-->

<h1 align="center">Project Zintos</h1>

<p align="center">
An open-source forum built with Laravel: categories, moderation, Google sign-in and two-factor authentication.
</p>

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13"></a>
<a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP 8.4"></a>
<a href="https://www.postgresql.org"><img src="https://img.shields.io/badge/PostgreSQL-4169E1?logo=postgresql&logoColor=white" alt="PostgreSQL"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4"></a>
<a href="https://daisyui.com"><img src="https://img.shields.io/badge/daisyUI-5-5A0EF8?logo=daisyui&logoColor=white" alt="daisyUI 5"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
</p>

- [About](#about)
- [Features](#features)
- [Built With](#built-with)
- [Local Development](#local-development)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [License](#license)

## About

Project Zintos is an open-source forum and community platform built on [Laravel](https://laravel.com). Members organise discussions into categories and subcategories, write rich-text posts with images, GIFs, video and code blocks, comment on each other's posts, and build up a profile. Moderators review new content before it goes public, and administrators manage users, groups and site analytics from the same interface.

It is designed to be easy to self-host. Everything it needs, from the web server to Google sign-in and transactional email, is covered step by step in the [deployment guide](#deployment) below.

## Features

- **Categories and subcategories.** Every post is filed under a category and one of its subcategories.
- **Rich-text posts.** A self-hosted TinyMCE editor with links, tables and syntax-labelled code blocks. All HTML is sanitized on the server with HTMLPurifier before it is stored.
- **Media attachments.** One JPG, PNG, GIF or MP4 file (up to 5 MB) per post.
- **Comments, likes and view counts.**
- **Moderation.** New posts, and edits made by regular members, wait for approval. Moderators and administrators can approve posts and comments and pin or unpin posts.
- **User groups.** Administrator, moderator and premium roles, with group administration for admins.
- **Authentication.** Email and password with email verification, Sign in with Google, and two-factor authentication through Laravel Fortify.
- **Bot protection.** Cloudflare Turnstile on the login, registration and password reset forms.
- **Member profiles.** Avatars, biographies, member search and a team page.
- **Analytics dashboard.** Google Analytics 4 statistics and charts for administrators.
- **Cookie consent.** Analytics scripts load only after a visitor accepts the banner.
- **Themes.** daisyUI themes that follow the visitor's light or dark system preference.
- **Custom error pages** and an installable web app manifest.

## Built With

- [Laravel](https://laravel.com), [PHP 8.4](https://www.php.net) and [PostgreSQL](https://www.postgresql.org)
- [Vite](https://vite.dev), [Tailwind CSS](https://tailwindcss.com) and [daisyUI](https://daisyui.com)
- [TinyMCE](https://www.tiny.cloud) (self-hosted) and [mews/purifier](https://github.com/mewebstudio/Purifier)
- [Laravel Fortify](https://laravel.com/docs/fortify) and [Laravel Socialite](https://laravel.com/docs/socialite)
- [spatie/laravel-analytics](https://github.com/spatie/laravel-analytics) and [whitecube/laravel-cookie-consent](https://github.com/whitecube/laravel-cookie-consent)
- [Resend](https://resend.com) for email and [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) for bot protection

## Local Development

**Requirements:** PHP 8.4 with the `pgsql`, `zip`, `gd`, `mbstring`, `curl`, `xml`, `bcmath` and `intl` extensions, [Composer](https://getcomposer.org), Node.js (current LTS) with npm, and PostgreSQL.

```bash
git clone https://github.com/youruser/your-repo.git
cd your-repo

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Create a PostgreSQL database and user (the commands are in step 5 of the [deployment guide](#5-install-and-configure-postgresql)) and put the credentials in `.env`. Then run:

```bash
php artisan migrate --seed
php artisan storage:link
mkdir -p storage/app/public/assets/img/favicon storage/app/public/assets/img/items
```

Add your favicons and a `background.gif` to those two folders, as described in [step 11](#add-your-favicons-and-background-image). Then start the two development servers in separate terminals:

```bash
npm run dev
php artisan serve
```

The app is now running at `http://localhost:8000`.

A few settings are easier locally:

- **Email.** Set `MAIL_MAILER=log` and messages are written to `storage/logs/laravel.log` instead of being sent.
- **Turnstile.** Cloudflare publishes [test keys](https://developers.cloudflare.com/turnstile/troubleshooting/testing/) that always pass, so you don't need to create a widget.
- **Google sign-in.** Add `http://localhost:8000/auth/google/callback` as an extra authorized redirect URI in Google Cloud, and use the same value for `GOOGLE_CALLBACK_URL`. Step [13.1](#131-google-sign-in-google_client_id-google_client_secret-google_callback_url) explains how to get the credentials.
- **Analytics.** Leave the analytics keys empty if you don't need the dashboard.

## Deployment

This guide walks through a full production deployment on an Ubuntu 24.04 VPS: PHP 8.4, Composer, Nginx, PostgreSQL, Node and Vite, HTTPS through Let's Encrypt, and every third-party service the app needs (Google sign-in, Google Analytics, Resend email and Cloudflare Turnstile).

It is written for a Hostinger VPS, but nothing in it is Hostinger-specific except the DNS and firewall panel references. Any Ubuntu 24.04 VPS works.

> **Never commit secrets.** `.env` and `storage/app/analytics/service-account-credentials.json` must stay out of git. Both are listed in `.gitignore` already, but double-check before pushing a fork.

### Replace these placeholders

| Placeholder | Example |
|---|---|
| `yourdomain.com` | `myapp.com` (the domain you bought) |
| `youruser/your-repo` | `johndoe/my-laravel-app` |
| `laravelapp` | folder name on disk, and the database name |
| `dbuser` / `dbpassword` | your database credentials |
| `YOUR_VPS_IP` | the public IP of your VPS |

### What you'll need accounts for

| Service | Used for | Cost |
|---|---|---|
| Google Cloud | "Sign in with Google" and the Analytics dashboard | Free |
| Google Analytics (GA4) | Visitor statistics | Free |
| [Resend](https://resend.com) | Sending email (verification, password reset) | Free tier available, check current limits |
| [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) | Bot protection on login, register, and reset forms | Free |

Step 13 explains how to get every key.

---

### 0. Point the domain at your VPS

Start here, because DNS propagation can take a while.

1. In your domain registrar or hosting panel (on Hostinger: hPanel → Domains → your domain → DNS), add these records:

| Type | Name | Points to | TTL |
|---|---|---|---|
| A | `@` | `YOUR_VPS_IP` | 300 (or default) |
| A | `www` | `YOUR_VPS_IP` | 300 (or default) |

2. Check propagation:

```bash
dig +short yourdomain.com
dig +short www.yourdomain.com
```

Both should print `YOUR_VPS_IP`.

### 1. Update the system

```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install PHP 8.4 and extensions

Ubuntu 24.04 doesn't ship PHP 8.4, so add the Ondřej Surý PPA first.

```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.4-fpm php8.4-cli php8.4-common php8.4-pgsql php8.4-zip \
  php8.4-gd php8.4-mbstring php8.4-curl php8.4-xml php8.4-bcmath php8.4-intl \
  php8.4-opcache php8.4-redis -y
php -v   # should report PHP 8.4.x
```

### 3. Install Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
composer --version
```

### 4. Install Git

```bash
sudo apt install git -y
```

### 5. Install and configure PostgreSQL

```bash
sudo apt install postgresql postgresql-contrib -y
sudo systemctl enable postgresql
sudo systemctl start postgresql
sudo -u postgres psql
```

Inside the `psql` shell:

```sql
CREATE DATABASE laravelapp;
CREATE USER dbuser WITH PASSWORD 'dbpassword';
GRANT ALL PRIVILEGES ON DATABASE laravelapp TO dbuser;
ALTER DATABASE laravelapp OWNER TO dbuser;
\du
\q
```

`ALTER DATABASE ... OWNER TO` matters: without ownership, migrations can fail with permission errors even after `GRANT ALL`.

### 6. Install Nginx

```bash
sudo apt install nginx -y
sudo systemctl enable nginx
```

### 7. Install Node.js

Needed to build the frontend (Vite).

```bash
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install nodejs -y
node -v
npm -v
```

### 8. Set up SSH access to GitHub (private repos only)

Skip this step if the repo is public.

```bash
ssh-keygen -t ed25519 -C "deploy@yourdomain.com" -f ~/.ssh/id_ed25519 -N ""
cat ~/.ssh/id_ed25519.pub
```

Add the printed key in GitHub → your repo → Settings → Deploy keys (read-only is enough), then test:

```bash
ssh -T git@github.com
```

Generate the key as your regular user, not root.

### 9. Clone the repository

```bash
sudo mkdir -p /var/www
cd /var/www
sudo mkdir -p /var/www/laravelapp
sudo chown $USER:$USER /var/www/laravelapp
git clone https://github.com/youruser/your-repo.git laravelapp
# private repo over SSH:
# git clone git@github.com:youruser/your-repo.git laravelapp
cd laravelapp
```

Don't use `sudo` on `git clone`: root can't see the deploy key you generated under your own user.

### 10. Set ownership and permissions

Nginx runs as `www-data`, so give it group access.

```bash
sudo chown -R $USER:www-data /var/www/laravelapp
sudo find /var/www/laravelapp -type f -exec chmod 664 {} \;
sudo find /var/www/laravelapp -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx /var/www/laravelapp/storage /var/www/laravelapp/bootstrap/cache

# Stop git from reporting these permission changes as file modifications
cd /var/www/laravelapp
git config core.fileMode false
```

### 11. Create upload directories and set PHP upload paths

```bash
cd /var/www/laravelapp
mkdir -p storage/app/temp
mkdir -p storage/app/analytics
mkdir -p storage/app/public/assets/img/favicon
mkdir -p storage/app/public/assets/img/items

sudo chown -R $USER:www-data storage/app/temp storage/app/analytics storage/app/public/assets
touch storage/app/analytics/service-account-credentials.json
sudo chmod -R ug+rwx storage/app/temp
sudo find storage/app/public/assets -type d -exec chmod 775 {} \;
sudo find storage/app/public/assets -type f -exec chmod 664 {} \;
```

The empty `service-account-credentials.json` is a placeholder. Step 13.2 replaces it with the real file.

Edit the PHP-FPM config:

```bash
sudo nano /etc/php/8.4/fpm/php.ini
```

```ini
upload_tmp_dir = /var/www/laravelapp/storage/app/temp
sys_temp_dir = /var/www/laravelapp/storage/app/temp
upload_max_filesize = 5M
post_max_size = 8M
expose_php = Off
```

The app accepts post images and videos up to 5 MB, so `upload_max_filesize` must be at least `5M` and `post_max_size` must be larger than that. Nginx's `client_max_body_size` (step 18) must be at least as large as `post_max_size`.

Apply the same two temp-dir lines to the CLI config if you run queue workers or upload-related Artisan commands:

```bash
sudo nano /etc/php/8.4/cli/php.ini
```

Restart and verify:

```bash
sudo systemctl restart php8.4-fpm
php -i | grep -i "upload_tmp_dir\|sys_temp_dir"
```

#### Add your favicons and background image

The app expects two sets of images in `storage/app/public/assets/img/`. The `php artisan storage:link` command in step 12 exposes that folder at `/storage/assets/img/`. The images aren't shipped with the repository, so you add your own.

**Favicons: `storage/app/public/assets/img/favicon/`**

1. Go to [favicon.io](https://favicon.io) and generate your icons from an image, text, or an emoji.
2. Download the zip and extract it. You'll get `favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, `android-chrome-192x192.png`, `android-chrome-512x512.png` and `site.webmanifest`.
3. Open `site.webmanifest`, fill in your app's `name` and `short_name`, and change the icon paths to the new folder. favicon.io writes them as `/android-chrome-...`, which would return 404 because the files live in a subfolder:

   ```json
   "src": "/storage/assets/img/favicon/android-chrome-192x192.png"
   "src": "/storage/assets/img/favicon/android-chrome-512x512.png"
   ```

4. Upload everything from your own computer:

   ```bash
   scp favicon_io/* youruser@YOUR_VPS_IP:/var/www/laravelapp/storage/app/public/assets/img/favicon/
   ```

**Background image: `storage/app/public/assets/img/items/background.gif`**

Add a GIF named exactly `background.gif`. It's used as the background of the category pages and the 404 page. Keep the file small (a few MB at most), because it loads with those pages.

```bash
scp background.gif youruser@YOUR_VPS_IP:/var/www/laravelapp/storage/app/public/assets/img/items/
```

These files aren't tracked by git (`storage/app/public` is gitignored), so they stay on the server and survive `git pull`. Keep a copy on your own computer in case you rebuild the server.

### 12. Install PHP dependencies

```bash
cd /var/www/laravelapp
composer install --optimize-autoloader --no-dev
php artisan storage:link
```

---

### 13. Get your third-party credentials

These produce every value in the `.env` block you'll fill in at step 14. Do them in any order. A few need your domain to be live, and say so.

#### 13.1 Google sign-in: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_CALLBACK_URL`

1. Open the [Google Cloud Console](https://console.cloud.google.com/) and create a project (or pick an existing one).
2. Go to **Google Auth Platform** and click **Get started**. Fill in the app name and a support email, choose **External** as the audience, and add your contact email.
3. In **Google Auth Platform → Clients**, click **Create client**.
4. Set **Application type** to **Web application** and give it a name.
5. Leave **Authorized JavaScript origins** empty.
6. Under **Authorized redirect URIs**, click **Add URI** and enter your callback URL:

   ```
   https://yourdomain.com/auth/google/callback
   ```

   This must match the callback route defined in `routes/web.php` **exactly**, including `https`, the domain, and the path. A mismatch causes a `redirect_uri_mismatch` error at login.
7. Click **Create**. Google shows the **Client ID** and **Client secret**. Copy both now, because the secret can't be shown again. If you lose it, create a new client.
8. Set the same callback URL in your `.env`:

   ```dotenv
   GOOGLE_CLIENT_ID=123456789-abc.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxx
   GOOGLE_CALLBACK_URL=https://yourdomain.com/auth/google/callback
   ```

> **Publish the app, or only you can log in.** New projects start in **Testing** mode, where only the test users you list can sign in. When you're ready for the public, go to **Google Auth Platform → Audience** and click **Publish app** (set it to **In production**). Sign-in with basic profile and email scopes doesn't require Google's verification review.

#### 13.2 Google Analytics: `GOOGLE_ANALYTICS_ID` and `ANALYTICS_PROPERTY_ID`

These are two different IDs, and the app needs both.

1. Open [Google Analytics](https://analytics.google.com/) and create a **GA4 property** for your site, with a **Web data stream** pointing at `https://yourdomain.com`.
2. **`GOOGLE_ANALYTICS_ID`** is the **Measurement ID**. Find it in **Admin → Data streams →** your stream. It starts with `G-`, for example `G-ABC123XYZ4`. The tracking script loads only after a visitor accepts the cookie banner.
3. **`ANALYTICS_PROPERTY_ID`** is the **Property ID**. Find it in **Admin → Property details**. It is a number only, with no `G-` prefix, for example `123456789`. The admin analytics dashboard uses it.

The dashboard also needs a service account so the app can read your stats:

1. In the same Google Cloud project, open **APIs & Services → Library**, search for **Google Analytics Data API**, and click **Enable**.
2. Go to **IAM & Admin → Service Accounts → Create service account**. Give it a name and skip the optional role steps.
3. Open the new service account, go to **Keys → Add key → Create new key → JSON**. A `.json` file downloads.
4. Copy the service account's **email address** (it looks like `name@project.iam.gserviceaccount.com`). In Google Analytics, go to **Admin → Property access management**, click **+**, add that email, and give it the **Viewer** role.
5. Upload the JSON file to your server from **your own computer**:

   ```bash
   scp service-account-credentials.json youruser@YOUR_VPS_IP:/var/www/laravelapp/storage/app/analytics/
   ```

   Then lock down its permissions on the server:

   ```bash
   cd /var/www/laravelapp
   sudo chown $USER:www-data storage/app/analytics/service-account-credentials.json
   sudo chmod 640 storage/app/analytics/service-account-credentials.json
   ```

   Upload it with `scp` rather than pasting it into an editor. The private key contains line breaks that are easy to mangle by hand. It lives in `storage/`, outside `public/`, so the web server never serves it.

```dotenv
GOOGLE_ANALYTICS_ID=G-ABC123XYZ4
ANALYTICS_PROPERTY_ID=123456789
```

#### 13.3 Email with Resend: `RESEND_API_KEY`, `MAIL_MAILER`, `MAIL_FROM_ADDRESS`

A VPS shouldn't send email directly. Its IP has no sending reputation, so mail lands in spam, and many hosts block port 25. Resend handles delivery.

1. Create an account at [resend.com](https://resend.com).
2. Go to **Domains → Add Domain** and enter `yourdomain.com`.
3. Resend shows several DNS records (SPF, DKIM, and optionally DMARC). Add them exactly as shown in your DNS panel, the same place you added the A records in step 0.
4. Click **Verify** in Resend. This can take a few minutes to a few hours.
5. Go to **API Keys → Create API Key**. Choose **Sending access**, and copy the key. It starts with `re_` and is only shown once.

```dotenv
RESEND_API_KEY=re_xxxxxxxxxxxx
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

`MAIL_FROM_ADDRESS` must use the domain you verified in step 3. Until the domain is verified, Resend only lets you send test emails to your own account's address.

#### 13.4 Cloudflare Turnstile: `TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`

You don't need to move your DNS to Cloudflare to use Turnstile.

1. Create a free account at [cloudflare.com](https://dash.cloudflare.com/sign-up) and open **Turnstile** from the sidebar.
2. Click **Add widget** and give it a name.
3. Under **Hostname management**, add `yourdomain.com`. Add `localhost` as well if you want it to work during local development.
4. Choose the **Managed** widget mode, then click **Create**.
5. Copy the **Site Key** (public) and the **Secret Key** (private).

```dotenv
TURNSTILE_SITE_KEY=0x4AAAAAAAxxxxxxxxxx
TURNSTILE_SECRET_KEY=0x4AAAAAAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Local development:** Cloudflare publishes dummy keys that always pass, so you don't have to register `localhost`. Check [Cloudflare's testing docs](https://developers.cloudflare.com/turnstile/troubleshooting/testing/) for the current values and never use them in production.

---

### 14. Configure the environment file

```bash
cd /var/www/laravelapp
cp .env.example .env
nano .env
```

Fill in everything, using the values from step 13. If any of these keys are missing from `.env.example`, add them:

```dotenv
APP_NAME="Your App"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravelapp
DB_USERNAME=dbuser
DB_PASSWORD=dbpassword

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_CALLBACK_URL=https://yourdomain.com/auth/google/callback

ANALYTICS_PROPERTY_ID=
GOOGLE_ANALYTICS_ID=

RESEND_API_KEY=
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

`APP_URL` uses `https://` even though the certificate isn't issued until step 19. Laravel uses it to build links.

Generate the application key, then restrict who can read the file. `.env` holds your database password and API keys, and PHP-FPM only needs group read access:

```bash
php artisan key:generate
sudo chown $USER:www-data .env
sudo chmod 640 .env
```

### 15. Run migrations

```bash
php artisan migrate --force --seed
```

`--force` is required in production. `--seed` is required here because it creates the user groups the app depends on.

**Making yourself an admin:** register an account through the site first, then promote it. This assumes the seeder created a group with `is_admin` set:

```bash
php artisan tinker
```

```php
\App\Models\User::where('email', 'you@example.com')->update([
    'group_id' => \App\Models\Group::where('is_admin', true)->value('id'),
]);
```

### 16. Build frontend assets

```bash
npm install
npm run build
```

### 17. Cache config, routes, and views

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Re-run these any time you change `.env`, or Laravel keeps using the old cached values.

Running Artisan as your own user can create log and cache files that PHP-FPM (`www-data`) can't write to, which causes 500 errors. Fix the ownership once more:

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

### 18. Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/laravelapp
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laravelapp/public;
    client_max_body_size 8M;

    server_tokens off;
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

    # Never execute PHP from the uploads folder (must come before the .php block)
    location ~* ^/storage/.*\.php$ {
        deny all;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and remove the default:

```bash
sudo ln -s /etc/nginx/sites-available/laravelapp /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

`http://yourdomain.com` should now load the app once DNS has propagated. Confirm that before moving on.

### 19. Open the firewall and get an SSL certificate

If `ufw` is active:

```bash
sudo ufw status
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw reload
```

`Nginx Full` opens ports 80 and 443. If your hosting provider has its own firewall panel (Hostinger: hPanel → VPS → Firewall), make sure 80 and 443 are allowed there too.

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Enter an email, accept the terms, and choose to **redirect HTTP to HTTPS** when asked. Then confirm renewal works:

```bash
sudo systemctl status certbot.timer
sudo certbot renew --dry-run
```

### 20. Final checks

```bash
sudo systemctl status php8.4-fpm
sudo systemctl status nginx
sudo systemctl status postgresql
curl -I https://yourdomain.com
```

The response headers should not contain `Server: nginx/1.x.x` or `X-Powered-By: PHP/...`.

Then test each integration from the browser:

- [ ] **Uploads:** upload a post image and confirm there are no errors in `storage/logs/laravel.log`.
- [ ] **Google sign-in:** click "Sign in with Google" and confirm you land back on the site, logged in.
- [ ] **Turnstile:** the widget appears on the login and register forms, and submitting works.
- [ ] **Email:** send a test message from the server:

  ```bash
  php artisan tinker
  ```

  ```php
  Mail::raw('Test email', fn ($m) => $m->to('you@example.com')->subject('Test'));
  ```

  Check Resend's **Emails** log if it doesn't arrive.
- [ ] **Analytics:** accept the cookie banner, then open the admin Analytics page. Real-time data can take a few minutes to appear.

---

### Recommended next steps

- **Review the legal pages.** The footer links to `/cookies`, `/privacy` and `/terms`. Replace any project names and contact details in them with your own.
- **Lock down SSH.** Use key-based login, disable root login, and install `fail2ban`.
- **Enable automatic security updates** with `unattended-upgrades`.
- **Back up the database.** A daily cron job running `pg_dump`, with the result copied off the server:

  ```bash
  mkdir -p ~/backups
  PGPASSWORD='dbpassword' pg_dump -U dbuser -h 127.0.0.1 laravelapp | gzip > ~/backups/laravelapp-$(date +%F).sql.gz
  ```

  Also back up `.env`, `storage/app/public` (user uploads), and the analytics credentials file.
- **Keep dependencies updated.** Run `composer audit` and `npm audit` regularly, and bump `tinymce` when security releases ship.

---

### Redeploying updates

```bash
cd /var/www/laravelapp
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
sudo systemctl restart php8.4-fpm
```

Your uploaded favicons, `background.gif` and the `.env` file aren't touched by `git pull`, so they survive redeploys.

Consider wrapping this in a `deploy.sh` script, or moving to CI/CD (GitHub Actions with SSH deploy) as the project grows.

---

### Troubleshooting

| Symptom | Likely cause |
|---|---|
| 502 Bad Gateway | PHP-FPM not running, or wrong socket path in the Nginx config |
| 500 error, blank page | Temporarily set `APP_DEBUG=true` and check `storage/logs/laravel.log`. Set it back to `false` afterward |
| "Permission denied" on storage or cache | Re-run the chown/chmod commands from step 10 |
| Migrations fail to connect | Check the `.env` DB credentials and that PostgreSQL is running (`sudo systemctl status postgresql`) |
| Migration permission errors | The DB user doesn't own the database. Run `ALTER DATABASE laravelapp OWNER TO dbuser;` |
| Composer install fails on memory | `COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-dev` |
| File uploads fail with "failed to open stream" | `storage/app/temp` is missing or not writable by `www-data`, or `upload_tmp_dir` doesn't point to it. Restart `php8.4-fpm` |
| `nginx -t` says `listen` directive is not allowed here | The config is missing its opening `server {` line, or `sites-enabled/laravelapp` isn't a symlink |
| Certbot fails the HTTP-01 challenge | DNS hasn't propagated, or port 80 is blocked in `ufw` or the provider firewall |
| `www.yourdomain.com` doesn't resolve | Add the missing `www` A record (step 0) |
| Changed `.env` but nothing changes | Run `php artisan config:cache` (or `config:clear`) |
| Google login: `redirect_uri_mismatch` | `GOOGLE_CALLBACK_URL` and the URI in Google Cloud don't match character for character |
| Google login: "Access blocked" / only you can sign in | The OAuth app is still in Testing mode. Publish it under Google Auth Platform → Audience |
| Analytics dashboard empty or erroring | The service account isn't a Viewer on the GA4 property, the Analytics Data API isn't enabled, or `ANALYTICS_PROPERTY_ID` is the `G-` Measurement ID instead of the numeric Property ID |
| Emails don't arrive | The Resend domain isn't verified yet, or `MAIL_FROM_ADDRESS` uses a different domain. Check the Resend dashboard logs |
| Turnstile widget missing or always failing | The site key's hostname list doesn't include your domain, or the site key and secret key are swapped |
| Uploads fail, or "must be 5 MB or smaller" appears for files under 5 MB | `upload_max_filesize` or `post_max_size` in `php.ini`, or `client_max_body_size` in Nginx, is too low. Use `5M` / `8M` / `8M`, then restart `php8.4-fpm` and reload Nginx |
| `413 Request Entity Too Large` | `client_max_body_size` in Nginx is smaller than the upload |
| Favicon missing, or the manifest fails to load | `storage/app/public/assets/img/favicon/` is empty, `php artisan storage:link` hasn't been run, or the icon paths in `site.webmanifest` still start with `/android-chrome-` instead of `/storage/assets/img/favicon/` |
| Plain page background, or a broken image on category pages and the 404 page | `storage/app/public/assets/img/items/background.gif` is missing, or `php artisan storage:link` hasn't been run. The file name must match exactly |
| `laravel.log` "permission denied" after running Artisan | Artisan created files owned by your user. Re-run the `chown` and `chmod` on `storage` and `bootstrap/cache` from step 17 |

---

## Contributing

Contributions are welcome. Fork the repository, create a branch for your change, and open a pull request. For larger changes, please open an issue first so we can agree on the approach.

## Security Vulnerabilities

If you discover a security vulnerability, please don't open a public issue. Report it privately using the **Report a vulnerability** button in the repository's **Security** tab. Reports will be addressed promptly.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
