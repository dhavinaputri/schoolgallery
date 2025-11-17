# Railway Deployment Guide - Storage Configuration

## Quick Start

Untuk deploy ke Railway dengan storage yang bekerja:

### 1. Update `.env` untuk Production

Di file `.env` atau Railway environment variables, set:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://eduspot.up.railway.app

# Storage Configuration untuk Railway
FILESYSTEM_DISK=local
FILESYSTEM_ROOT=/app/storage/app/public

# Database (already configured)
DB_CONNECTION=mysql
DB_DATABASE=railway
DB_HOST=mainline.proxy.rlwy.net
DB_PORT=53762
DB_USERNAME=root
DB_PASSWORD=...

# Cache, Session (gunakan database)
CACHE_STORE=database
SESSION_DRIVER=database
```

### 2. Configure Volume Mount in Railway

Di Railway dashboard:
1. Go to your project
2. Variables tab
3. Add persistent volume:
   - **Volume Mount Path**: `/app/storage`
   - This persists your images across deployments

### 3. Deployment

Push ke Railway atau redeploy:
```bash
git push railway main
```

Railway akan:
1. Run `start-server.sh`
2. Create directories di `/app/storage/app/public/`
3. Migrate existing images (jika ada di `storage/app/public/`)
4. Create symlink `public/storage` → `/app/storage/app/public`
5. Start server

### 4. Verify Installation

Check Deployment Logs:
```
✅ Storage symlink created: public/storage -> /app/storage/app/public
✅ Image migration completed!
```

---

## File Locations After Deployment

```
Railway Container:
├── /app/public/
│   ├── index.php
│   ├── storage → /app/storage/app/public  (symlink)
│   └── ...
│
└── /app/storage/  (VOLUME MOUNT - persists data)
    └── app/
        └── public/
            ├── galleries/
            ├── news/
            ├── avatars/
            ├── teachers/
            └── submissions/
```

---

## Upload Flow

### User uploads foto via admin panel:

1. **Upload request** → Controller
   ```php
   $imagePath = StorageHelper::storeFile($file, 'galleries');
   // Returns: "galleries/1kuXeE4gd.jpg"
   ```

2. **StorageHelper detects Railway environment**
   ```php
   if (self::isRailwayEnvironment()) {  // ✅ TRUE
       $targetPath = '/app/storage/app/public/galleries/';
       $file->move($targetPath, $fileName);
   }
   ```

3. **File saved to volume mount**
   ```
   /app/storage/app/public/galleries/1kuXeE4gd.jpg ✅
   ```

4. **Path stored in database**
   ```sql
   galleries | image: "galleries/1kuXeE4gd.jpg"
   ```

---

## Access Flow

### User views gallery page:

1. **View renders image tag**
   ```php
   <img src="{{ asset('storage/galleries/1kuXeE4gd.jpg') }}" />
   ```

2. **Laravel generates URL**
   ```
   https://eduspot.up.railway.app/storage/galleries/1kuXeE4gd.jpg
   ```

3. **Browser requests the URL**
   ```
   GET /storage/galleries/1kuXeE4gd.jpg
   ```

4. **Web server (PHP) resolves symlink**
   ```
   public/storage/galleries/1kuXeE4gd.jpg
   ↓
   /app/storage/app/public/galleries/1kuXeE4gd.jpg ✅
   ```

5. **File returned to browser** ✅✅✅

---

## Common Issues & Solutions

### Issue 1: Images return 404

**Cause**: Symlink tidak dibuat atau path salah

**Solution**:
1. Check deployment logs untuk error
2. Redeploy: `git push railway main`
3. Check volume mount configured di Railway

```bash
# Manual check (jika ada SSH access):
ls -la /app/storage/app/public/
ls -la public/storage
```

### Issue 2: "Permission denied" errors

**Cause**: Folder permissions tidak setara

**Solution**: Seharusnya otomatis dari `start-server.sh`:
```bash
chmod -R 755 /app/storage/app/public
```

Jika masih error, redeploy dan check logs.

### Issue 3: Old images tidak muncul setelah migrate

**Cause**: File belum di-copy ke volume mount

**Solution**: 
1. First deployment otomatis migrate (jika images ada di `storage/app/public/`)
2. Jika tetap tidak muncul, run manual:
   ```bash
   bash migrate-images.sh
   ```

### Issue 4: "Cannot create directory" error

**Cause**: Volume mount tidak ter-setup dengan benar

**Solution**:
1. Check Railway dashboard → Volume tab
2. Pastikan mount path: `/app/storage`
3. Redeploy application

---

## Performance Tips

### Image Optimization

Add image compression untuk reduce storage:
```php
// In StorageHelper::storeFile()
// Optional: Compress image before saving
if (str_contains($file->getMimeType(), 'image')) {
    // Use Intervention Image library to optimize
    Image::make($file)->save($targetPath . $fileName, 80);
}
```

### Caching

Enable HTTP caching untuk storage assets:
```php
// In public/index.php or web server config
header('Cache-Control: public, max-age=31536000'); // 1 year
```

---

## Database Backup

Make sure to backup MySQL database regularly:
- Database persists in Railway MySQL service
- But storage is persisted via volume mount
- Both need proper backup strategy

---

## Monitoring Storage

### Check current usage:
```bash
df -h /app/storage/
du -sh /app/storage/
```

### Monitor growth:
```bash
# Add to cron job
du -sh /app/storage/app/public/galleries/ >> /app/storage/size_log.txt
```

---

## Rollback / Troubleshooting

### If something goes wrong:

1. **Check logs**
   ```
   Railway Dashboard → Logs → Deployment & Runtime
   ```

2. **Verify environment**
   ```
   Railway Dashboard → Variables
   Check: FILESYSTEM_ROOT, APP_ENV, etc.
   ```

3. **Manual migration**
   ```bash
   php artisan storage:link  # Local only, won't work on Railway
   bash migrate-images.sh    # Use this instead
   ```

4. **Full reset** (careful!)
   ```bash
   # Delete volume and redeploy
   # This will lose all images!
   git push railway main --force
   ```

---

## Next Steps

1. ✅ Update `.env` dengan FILESYSTEM_ROOT
2. ✅ Configure volume mount di Railway
3. ✅ Push code: `git push railway main`
4. ✅ Check deployment logs
5. ✅ Upload test image via admin
6. ✅ Verify image loads in gallery

Done! 🎉
