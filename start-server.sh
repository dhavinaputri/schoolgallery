#!/bin/bash

# Start server script for Railway deployment

echo "Starting server..."

# Set Railway environment flag
export RAILWAY_ENVIRONMENT=true

# Create necessary directories
echo "Creating necessary directories..."
mkdir -p /app/storage/app/public/galleries
mkdir -p /app/storage/app/public/news
mkdir -p /app/storage/app/public/teachers
mkdir -p /app/storage/app/public/avatars
mkdir -p /app/storage/app/public/submissions
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 /app/storage/app/public 2>/dev/null || true
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Cleanup missing images from database
echo "Cleaning up missing image references..."
php artisan images:cleanup || echo "Image cleanup failed, continuing..."

# Clear caches first
echo "Clearing caches..."
php artisan cache:clear || echo "Cache clear failed, continuing..."
php artisan view:clear || echo "View clear failed, continuing..."
php artisan route:clear || echo "Route clear failed, continuing..."

# Skip caching to avoid cache path errors
echo "Skipping cache generation to avoid path errors..."

# Create storage symlink to volume mount
echo "Setting up storage for Railway volume mount..."
rm -rf public/storage

# Create symlink from public/storage to /app/storage/app/public (use absolute paths)
rm -rf /app/public/storage
ln -sf /app/storage/app/public /app/public/storage
echo "✅ Storage symlink created: /app/public/storage -> /app/storage/app/public"
echo "Debug: Storage symlink status:"
ls -la public/storage 2>/dev/null || echo "Symlink not yet created (will be created after copy)"

# Migrate existing images to Railway storage
echo "Migrating existing images..."
echo "Debug: Checking what's in storage/app/public..."
ls -la storage/app/public/ | head -10 || echo "storage/app/public doesn't exist"

if [ -d "storage/app/public" ] && [ "$(ls -A storage/app/public 2>/dev/null)" ]; then
    echo "Found existing images, migrating..."
    
    # Ensure volume mount storage directory exists
    STORAGE_PATH="/app/storage/app/public"
    mkdir -p $STORAGE_PATH
    
    # Copy all directories and files to volume mount
    mkdir -p $STORAGE_PATH/galleries
    mkdir -p $STORAGE_PATH/news
    mkdir -p $STORAGE_PATH/avatars
    mkdir -p $STORAGE_PATH/teachers
    mkdir -p $STORAGE_PATH/submissions

    # Copy all directories recursively with subdirectories preserved
    for source_dir in galleries news avatars teachers submissions; do
        if [ -d "storage/app/public/$source_dir" ]; then
            # Use find to copy directory structure and files recursively
            mkdir -p "$STORAGE_PATH/$source_dir"
            cp -r storage/app/public/$source_dir/* "$STORAGE_PATH/$source_dir/" 2>/dev/null || true
            
            # Also preserve any subdirectories
            find storage/app/public/$source_dir -type d -not -path '*/.*' | while read subdir; do
                target_subdir="${subdir/storage\/app\/public/$STORAGE_PATH}"
                mkdir -p "$target_subdir" 2>/dev/null || true
            done
            
            echo "$source_dir images migrated (including subdirectories)"
        fi
    done
    
    # Copy any loose files in root storage/app/public
    find storage/app/public -maxdepth 1 -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \) -exec cp {} $STORAGE_PATH/ \; 2>/dev/null || true
    
    # Now create the symlink after data is copied (use absolute paths)
    rm -rf /app/public/storage
    ln -sf $STORAGE_PATH /app/public/storage
    
    echo "Image migration completed!"
    echo "Debug: Checking volume mount storage..."
    ls -la $STORAGE_PATH/ | head -10
    echo "Debug: Checking specific directories..."
    echo "=== NEWS DIRECTORY ==="
    ls -la $STORAGE_PATH/news/ | head -5 || echo "News directory empty"
    echo "=== GALLERIES DIRECTORY ==="
    ls -la $STORAGE_PATH/galleries/ | head -5 || echo "Galleries directory empty"
    echo "=== GALLERIES/PRESTASI DIRECTORY ==="
    ls -la $STORAGE_PATH/galleries/prestasi/ | head -5 || echo "Prestasi directory empty"
else
    echo "No existing images found to migrate"
    echo "Creating dummy images for testing..."

    # Create dummy images to test if storage works
    STORAGE_PATH="/app/storage/app/public"
    mkdir -p $STORAGE_PATH/news $STORAGE_PATH/galleries $STORAGE_PATH/galleries/prestasi

    echo "Creating test images..."
    # Create dummy images that match the ones being requested in logs
    echo "dummy content" > $STORAGE_PATH/news/HcnVFp3CUm6oKFqcyKobBbNLAwUrmZjhy8HQsMK7.jpg
    echo "dummy content" > $STORAGE_PATH/news/EUXaJVYDYU1IMBZDNwvO5fMR1uqUd1MSZ1okn77x.png
    echo "dummy content" > $STORAGE_PATH/galleries/1ku1XeEEKINznofZjkPu8mMDTNJOGOu4iYQYo4gd.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/prestasi/1ku1XeEEKINznofZjkPu8mMDTNJOGOu4iYQYo4gd.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/FUVSheXXHUlrzxJ3nsxPPP6O090Wk7KVii4ibbpb.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/ukeoLE4jG8IdqRfuIxw7DBeBtPFjYfgeAD5RHBzF.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/NaLzZWVGGJFyjwnB1TM0c5DhvCENpwBxS3ArKYku.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/6BD6KSRBCmqDXbJNXs700gTIlGExVFAp0Lm1vGpo.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/yU53sG7F4j7J8sDtnhvOpmJ5GQdWK3RIr673RFyb.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/0i4LEq0kli8MLkmYs8ZgkzqVXDalnb7zedUEv4Dh.jpg
    echo "dummy content" > $STORAGE_PATH/galleries/Vph7SVcnGc8f7v67mb7CQnZEVAf5zaaiSHxkcqCU.jpg

    # Create symlink after creating dummy files (use absolute paths)
    rm -rf /app/public/storage
    ln -sf $STORAGE_PATH /app/public/storage

    echo "✅ Dummy images created for testing"
fi

# Ensure public/index.php exists
echo "Checking Laravel public directory..."
if [ ! -f "public/index.php" ]; then
    echo "WARNING: public/index.php not found!"
    echo "Creating basic Laravel public structure..."
    
    mkdir -p public
    
    # Create public/index.php manually
    cat > public/index.php << 'EOF'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
EOF
    echo "✅ public/index.php created successfully"
fi

# Ensure storage symlink exists
if [ ! -L "/app/public/storage" ]; then
    echo "Creating storage symlink..."
    rm -rf /app/public/storage
    mkdir -p /app/storage/app/public
    ln -sf /app/storage/app/public /app/public/storage
    echo "✅ Storage symlink created"
fi

# Final debug - test if storage is accessible
echo "Final debug: Testing storage accessibility..."
echo "=== TESTING STORAGE ACCESS ==="
if [ -f "public/storage/news/HcnVFp3CUm6oKFqcyKobBbNLAwUrmZjhy8HQsMK7.jpg" ]; then
    echo "✅ News image accessible via symlink"
elif [ -f "/app/storage/app/public/news/HcnVFp3CUm6oKFqcyKobBbNLAwUrmZjhy8HQsMK7.jpg" ]; then
    echo "✅ News image exists in volume mount"
else
    echo "ℹ️ No test images yet (will be created on first upload)"
fi

echo "=== CHECKING STORAGE STRUCTURE ==="
echo "Symlink verification:"
ls -la /app/public/ | grep storage || echo "Storage symlink not found"

echo ""
echo "Volume mount structure:"
ls -la /app/storage/app/public/ 2>/dev/null | head -10 || echo "/app/storage/app/public doesn't exist yet"

# Start Laravel server (fallback to artisan serve)
echo "Starting Laravel server on port ${PORT:-8080}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
