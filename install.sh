#!/bin/bash
# Self Hosted Google Drive (DriveDAV) Installer with HTTPS Setup Option
# This script installs Apache, PHP, required modules, downloads your PHP files
# from GitHub, creates necessary folders, sets proper permissions, adjusts PHP's
# size limits, and optionally sets up HTTPS with Certbot.
#
# Run as root: sudo bash install.sh
# Your PHP files are hosted at:
#   https://github.com/david-xyz-abc/drivedav
# They will be fetched from the "main" branch via raw GitHub URLs.

set -e  # Exit on error

# Log output (optional, for troubleshooting)
LOGFILE="/var/log/selfhostedgdrive_install.log"
exec > >(tee -a "$LOGFILE") 2>&1

echo "======================================"
echo "Self Hosted Google Drive (DriveDAV) Installer"
echo "======================================"

# Ensure the script is run as root
if [ "$(id -u)" -ne 0 ]; then
  echo "ERROR: This script must be run as root. Use: sudo bash install.sh"
  exit 1
fi

# Set the base URL to fetch the PHP files from your GitHub repository.
BASE_URL="https://raw.githubusercontent.com/david-xyz-abc/drivedav/main"

# List of required PHP files (adjust if more files are needed)
FILES=("index.php" "authenticate.php" "explorer.php" "logout.php")

# Update package lists
echo "Updating package lists..."
apt-get update

# Install Apache, PHP, required modules, and wget for file fetching
echo "Installing Apache, PHP, and required modules..."
apt-get install -y apache2 php libapache2-mod-php php-cli php-json php-mbstring php-xml wget

# Define directories for the application and WebDAV storage
APP_DIR="/var/www/html/selfhostedgdrive"
WEBDAV_DIR="/var/www/html/webdav/Home"

# Create the application directory
echo "Creating application directory at $APP_DIR..."
mkdir -p "$APP_DIR"

# Download the PHP files from GitHub into the application directory
echo "Downloading PHP files from GitHub..."
for file in "${FILES[@]}"; do
  FILE_URL="${BASE_URL}/${file}"
  echo "Fetching ${file} from ${FILE_URL}..."
  wget -q -O "$APP_DIR/$file" "$FILE_URL" || { echo "ERROR: Failed to download ${file}"; exit 1; }
done

# Set ownership and permissions for the application directory
echo "Setting ownership and permissions for $APP_DIR..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 755 "$APP_DIR"

# Create the WebDAV folder for file storage (as used by explorer.php)
echo "Creating WebDAV directory at $WEBDAV_DIR..."
mkdir -p "$WEBDAV_DIR"
chown -R www-data:www-data "$(dirname "$WEBDAV_DIR")"
chmod -R 755 "$(dirname "$WEBDAV_DIR")"

# Locate the php.ini file used by Apache
PHP_INI=$(php --ini | grep "Loaded Configuration" | sed -E 's|^.*:\s+||' | head -n 1)
if [ -z "$PHP_INI" ]; then
  echo "ERROR: Unable to detect php.ini. Exiting."
  exit 1
fi

echo "Found php.ini at: $PHP_INI"
echo "Backing up php.ini to ${PHP_INI}.backup..."
cp "$PHP_INI" "${PHP_INI}.backup"

# Adjust PHP configuration to allow larger uploads
echo "Adjusting PHP size limits in $PHP_INI..."
sed -i 's/^\s*upload_max_filesize\s*=.*/upload_max_filesize = 50M/' "$PHP_INI"
sed -i 's/^\s*post_max_size\s*=.*/post_max_size = 100M/' "$PHP_INI"
sed -i 's/^\s*memory_limit\s*=.*/memory_limit = 256M/' "$PHP_INI"
echo "PHP configuration updated (backup saved as ${PHP_INI}.backup)"

# Optionally, enable Apache mod_rewrite if required by your application
echo "Enabling Apache mod_rewrite..."
a2enmod rewrite

# Restart Apache to apply changes
echo "Restarting Apache..."
systemctl restart apache2

# Optional: HTTPS setup using Certbot
read -p "Enter your domain name for HTTPS configuration (or press Enter to skip): " DOMAIN
if [ ! -z "$DOMAIN" ]; then
  echo "Installing Certbot and the Apache plugin..."
  apt-get install -y certbot python3-certbot-apache
  
  echo "Obtaining and installing SSL certificate for $DOMAIN..."
  # Replace your-email@example.com with your actual email address.
  certbot --apache -d "$DOMAIN" --non-interactive --agree-tos --redirect -m your-email@example.com
  
  echo "HTTPS setup complete for $DOMAIN."
else
  echo "Skipping HTTPS configuration."
fi

echo "======================================"
echo "Installation Complete!"
echo "Access your application at: http://your_server_address/selfhostedgdrive/"
echo "For HTTPS, ensure your domain points to this server."
echo "Log file: $LOGFILE"
echo "======================================"
