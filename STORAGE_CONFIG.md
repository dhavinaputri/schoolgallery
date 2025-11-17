# Storage Configuration Guide

## Problem Analysis

Masalah 404 pada storage terjadi karena **ketidakkonsistenan path** antara 4 komponen:

1. **StorageHelper.php** - path hardcoded ke `/app/public/storage`
2. **config/filesystems.php** - path ke `/app/storage/app` (via FILESYSTEM_ROOT)
3. **start-server.sh** - mixed paths antara `/app/public/storage` dan `/app/storage/app/public`
4. **View** - menggunakan `asset('storage/...')` yang mencari di `public/storage/`

Result: File disimpan di satu tempat, tapi diakses dari tempat lain → **404**

---

## Solution: Centralized Storage at `/app/storage/app/public`

Semua komponen sekarang menggunakan **satu path tunggal** untuk production:

```
/app/storage/app/public/  ← Volume mount di Railway
    ├── galleries/
    ├── news/
    ├── avatars/
    ├── teachers/
    └── submissions/
```

Dan symlink di public folder:
```
public/storage → /app/storage/app/public
```

---

## File Structure

```
public/
  ├── storage/  (symlink → /app/storage/app/public)
  ├── index.php
  └── ...

/app/
  ├── storage/
  │   └── app/
  │       └── public/  ← VOLUME MOUNT (persisted across deployments)
  │           ├── galleries/
  │           ├── news/
  │           ├── avatars/
  │           ├── teachers/
  │           └── submissions/
  └── public/
      └── storage/ (symlink)
```

---

## Configuration Changes

### 1. `.env` (Production - Railway)
```env
FILESYSTEM_DISK=local
FILESYSTEM_ROOT=/app/storage/app/public
```

### 2. `.env` (Local Development)
```env
FILESYSTEM_DISK=local
# Jangan set FILESYSTEM_ROOT, biarkan default ke storage/app/public
```

### 3. `config/filesystems.php`
```php
'public' => [
    'driver' => 'local',
    'root' => env('FILESYSTEM_ROOT', storage_path('app/public')),  // ← Uses FILESYSTEM_ROOT
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],

'links' => [
    public_path('storage') => env('FILESYSTEM_ROOT', storage_path('app/public')),
],
```

### 4. `app/Helpers/StorageHelper.php`
```php
public static function getStoragePath($path = '')
{
    if (self::isRailwayEnvironment()) {
        $basePath = env('FILESYSTEM_ROOT', '/app/storage/app/public');  // ← Uses FILESYSTEM_ROOT
        return $path ? $basePath . '/' . ltrim($path, '/') : $basePath;
    }
    return public_path('storage/' . ltrim($path, '/'));
}
```

### 5. `start-server.sh`
- Membuat directories di `/app/storage/app/public/`
- Migrate existing images dari `storage/app/public/` → `/app/storage/app/public/`
- Membuat symlink `public/storage` → `/app/storage/app/public`

---

## Deployment Flow (Railway)

1. **Container starts**
   - `start-server.sh` executes

2. **Directories created**
   ```bash
   mkdir -p /app/storage/app/public/{galleries,news,avatars,teachers,submissions}
   ```

3. **Database migrations run**
   ```bash
   php artisan migrate --force
   ```

4. **Existing images migrated**
   ```bash
   cp -r storage/app/public/* /app/storage/app/public/
   ```

5. **Symlink created**
   ```bash
   ln -sf /app/storage/app/public public/storage
   ```

6. **Server starts**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8080
   ```

---

## How It Works in Production

### Uploading Files
1. User uploads foto via admin panel
2. `StorageHelper::storeFile()` dipanggil
3. File disimpan ke `/app/storage/app/public/galleries/` (Railway environment detection)
4. Path `galleries/filename.jpg` disimpan di database

### Accessing Files
1. View renders: `<img src="{{ asset('storage/galleries/filename.jpg') }}" />`
2. Laravel generates URL: `https://eduspot.up.railway.app/storage/galleries/filename.jpg`
3. Web server handles request ke `public/storage/galleries/filename.jpg`
4. Symlink resolves ke `/app/storage/app/public/galleries/filename.jpg` ✅
5. File served successfully!

---

## Testing

### Local Development
```bash
# Run migrations
php artisan migrate

# Upload test image via admin panel
# Check at: http://localhost:8000/storage/galleries/filename.jpg

# Files stored in: storage/app/public/galleries/
```

### Railway Production
1. Push code ke Railway
2. Deployment runs `start-server.sh`
3. Existing images auto-migrated
4. Symlink created
5. New uploads go to `/app/storage/app/public/`
6. All images accessible via `https://eduspot.up.railway.app/storage/galleries/`

---

## Troubleshooting

### Images still 404?
1. Check symlink: `ls -la public/storage`
   - Should show: `storage -> /app/storage/app/public`
   
2. Check volume mount: `ls -la /app/storage/app/public/`
   - Should have: `galleries/`, `news/`, `avatars/`, etc.

3. Check database paths: 
   ```sql
   SELECT image FROM galleries LIMIT 5;
   ```
   - Should return: `galleries/filename.jpg` (relative path)

### Files not persisting after redeploy?
- Make sure `/app/storage` is configured as volume mount in Railway
- Volume configuration: Mount path `/app/storage`

### Permission denied?
```bash
# Railway: chmod is already set in start-server.sh
# Ensure volume has proper permissions:
chmod -R 755 /app/storage/app/public
```

---

## Migration Script

Jika ingin migrasi manual after first deploy:
```bash
bash migrate-images.sh
```

Script ini copy gambar dari `storage/app/public/` ke `/app/storage/app/public/`

---

## Summary

✅ **Sebelum perbaikan**: 4 path berbeda, file chaos, 404 everywhere  
✅ **Sesudah perbaikan**: 1 path konsisten, file terorganisir, everything works!

Path terpusat: `/app/storage/app/public/`
