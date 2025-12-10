# MinIO Quick Start

## Untuk Development (Local)

### Setup MinIO dalam 1 Command:
```bash
./setup-minio.sh
```

Script ini otomatis:
✅ Clear config cache
✅ Create bucket `obe`
✅ Create directories (universities/logos, faculties/logos, etc)
✅ Set bucket public
✅ Test access
✅ Show configuration

### Setelah setup, tinggal:
1. Buka http://localhost:9001 (MinIO Console)
2. Login: `minioadmin` / `minioadmin`
3. Kembali ke Filament admin panel
4. Upload gambar di Universities, Faculties, atau Study Programs

Done! Gambar akan tersimpan di MinIO dan bisa diakses via `http://localhost:9000/obe/...`

---

## Untuk Production

### 1. Setup MinIO di Server
```bash
./setup-minio.sh
```

### 2. Update `.env`:
```env
# Untuk internal PHP connection (bisa tetap HTTP)
MINIO_ENDPOINT=http://minio:9000

# Untuk public URL access (HARUS HTTPS)
MINIO_URL=https://yourdomain.com/s3/obe
```

### 3. Ensure Nginx `/s3/` proxy sudah ada
Check di `nginx/default.conf`:
```nginx
location /s3/ {
    proxy_pass http://minio:9000/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_request_buffering off;
}
```

### 4. Clear cache & restart:
```bash
php artisan config:clear
docker compose restart nginx php
```

Done! Gambar akan accessible via HTTPS: `https://yourdomain.com/s3/obe/universities/logos/...`

---

## Troubleshooting

### "404 Not Found" saat upload
✅ Jalankan `./setup-minio.sh` lagi
✅ Check MinIO console apakah bucket publik

### "Mixed content" error di production
✅ Pastikan `MINIO_URL=https://...` (HTTPS, bukan HTTP)
✅ AppServiceProvider otomatis handle URL conversion

### Upload file tapi tidak muncul
✅ Check file sudah di MinIO console (localhost:9001)
✅ Check credentials di `.env` benar
✅ Check `FILESYSTEM_DISK=minio` di `.env`

### Upload besar (> 100MB) timeout
✅ Increase `client_max_body_size` di nginx `/s3/` location
✅ Increase proxy timeouts

---

## Useful Commands

```bash
# Setup everything automatically
./setup-minio.sh

# Test MinIO connection
docker compose exec php php artisan tinker
> Storage::disk('minio')->exists('test.txt')
> Storage::disk('minio')->put('test.txt', 'hello')
> Storage::disk('minio')->url('test.txt')

# View MinIO logs
docker compose logs minio

# Access MinIO Console
# URL: http://localhost:9001
# User: minioadmin / minioadmin

# Reset MinIO (delete everything)
docker compose down -v minio
rm -rf ./minio/data
docker compose up -d minio
./setup-minio.sh
```

---

## What Files Changed?

- ✅ `setup-minio.sh` - Automated setup script
- ✅ `.env` - Added MINIO configuration
- ✅ `.env.example` - Added MINIO configuration
- ✅ `app/Console/Commands/MinioSetup.php` - Create bucket & directories
- ✅ `app/Console/Commands/MinioPublic.php` - Set bucket to public
- ✅ `app/Providers/AppServiceProvider.php` - Auto-convert URLs to HTTPS
- ✅ `app/Http/Middleware/SecurityHeaders.php` - Security headers + CSP
- ✅ `app/Http/Middleware/ForceHttpsUrls.php` - Force HTTPS URLs
- ✅ `nginx/default.conf` - /s3/ proxy location
- ✅ `nginx/minio.conf` - MinIO reverse proxy config (reference)

---

## Architecture

```
Browser (User Upload)
    ↓
Filament Admin Panel (FileUpload component)
    ↓
Laravel Storage (FILESYSTEM_DISK=minio)
    ↓
MinIO (S3-compatible storage)
    ↓
Stored file in bucket

Later when viewing:
Browser: https://yourdomain.com/file.jpg
    ↓
Nginx /s3/ proxy
    ↓
MinIO internal HTTP
    ↓
Return file
```

---

## Next Steps

- 📝 Create backup strategy untuk MinIO bucket
- 📊 Setup MinIO replication untuk high availability
- 🔐 Change default credentials di production
- ⚡ Add CDN untuk faster file distribution
- 📈 Monitor disk usage dengan MinIO admin API
