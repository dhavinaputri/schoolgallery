# Image 404 Fix - Railway Deployment

## Problem
Images were returning 404 on Railway deployment even though:
- Symlink was created correctly
- Files were migrated to volume mount
- start-server.sh executed successfully

## Root Cause
Laravel's `asset()` helper and web server routing were not properly resolving the symlink path `/app/public/storage` to the actual files in `/app/storage/app/public/`.

The issue occurred because:
1. Symlinks were created with relative paths instead of absolute paths
2. Laravel's static file serving couldn't properly follow the symlink
3. The web server (PHP built-in server) had issues resolving the symlink

## Solution Implemented

### 1. Fixed Symlink Creation in `start-server.sh`
Changed all symlink creation commands to use **absolute paths**:
```bash
# Before (relative path)
ln -sf /app/storage/app/public public/storage

# After (absolute path)
ln -sf /app/storage/app/public /app/public/storage
```

### 2. Added Storage Middleware
Created `app/Http/Middleware/ServeStorageFiles.php` to:
- Intercept all requests to `/storage/*`
- Directly serve files from the correct location
- Handle both Railway (volume mount) and local environments

### 3. Registered Middleware
Added middleware to `bootstrap/app.php` with `prepend()` to ensure it runs before other middleware.

## How It Works Now

### Request Flow
```
Browser Request: GET /storage/galleries/photo.jpg
         ↓
ServeStorageFiles Middleware
         ↓
Extract path: galleries/photo.jpg
         ↓
Determine location:
  - Railway: /app/storage/app/public/galleries/photo.jpg
  - Local: storage/app/public/galleries/photo.jpg
         ↓
Check if file exists
         ↓
Serve file with response()->file()
```

## Files Modified
1. `start-server.sh` - Fixed symlink creation with absolute paths
2. `app/Http/Middleware/ServeStorageFiles.php` - NEW middleware for direct file serving
3. `bootstrap/app.php` - Registered the middleware

## Testing
After deployment, verify:
1. Check symlink: `ls -la /app/public/storage`
2. Check files: `ls -la /app/storage/app/public/galleries/`
3. Test URL: `https://eduspot.up.railway.app/storage/galleries/filename.jpg`

Should return 200 with image content, not 404.

## Fallback
If middleware approach has issues, the symlink will still work as a fallback for direct file access.
