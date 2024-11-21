#!/bin/bash

# Directory containing the PHP files
WEB_ROOT="/var/www/html"
CSP_CONF="/etc/apache2/conf-available/security-headers.conf"

# PHP files to include for hashing
FILES=(
    "add_item.php"
    "delete_item.php"
    "home.php"
    "index.php"
    "items.php"
    "login.php"
    "logout.php"
    "modify_item.php"
    "register.php"
    "show_item.php"
    "show_user.php"
    "users.php"
)

# Function to compute a SHA-256 hash for a given string
compute_hash() {
    echo -n "$1" | openssl dgst -sha256 -binary | openssl base64
}

# Prepare the CSP components
SCRIPT_HASHES=""
STYLE_HASHES=""

# Iterate over each specified file
for FILE in "${FILES[@]}"; do
    FILE_PATH="$WEB_ROOT/$FILE"
    if [ -f "$FILE_PATH" ]; then
        # Extract inline scripts
        SCRIPTS=$(grep -oP '(?<=<script>).*?(?=</script>)' "$FILE_PATH")
        for SCRIPT in $SCRIPTS; do
            HASH=$(compute_hash "$SCRIPT")
            SCRIPT_HASHES="$SCRIPT_HASHES 'sha256-$HASH'"
        done

        # Extract inline styles
        STYLES=$(grep -oP '(?<=<style>).*?(?=</style>)' "$FILE_PATH")
        for STYLE in $STYLES; do
            HASH=$(compute_hash "$STYLE")
            STYLE_HASHES="$STYLE_HASHES 'sha256-$HASH'"
        done
    else
        echo "Warning: File $FILE_PATH not found, skipping."
    fi
done

# Append the dynamically computed CSP header to the configuration
sed -i '/Content-Security-Policy/d' "$CSP_CONF" # Remove any previous CSP header
echo "Header always set Content-Security-Policy \"default-src 'self'; script-src 'self' $SCRIPT_HASHES https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/; style-src 'self' $STYLE_HASHES https://cdn.jsdelivr.net; img-src 'self' data:; frame-src 'self' https://www.google.com/recaptcha/; frame-ancestors 'self';\"" >> "$CSP_CONF"

# Reload Apache to apply changes
service apache2 reload
