# MinIO Setup untuk Production

## Problem
Di production, file images di MinIO tidak bisa diakses karena:
1. Mixed content error (HTTPS page + HTTP MinIO request)
2. Internal hostname `minio:9000` tidak bisa diakses dari browser
3. MinIO tidak ter-setup dengan public access

## Solusi

### 1. Setup MinIO (Di Container)

#### A. Buat bucket dan set public access
```bash
docker compose exec php php artisan minio:setup
docker compose exec php php artisan minio:public
```

#### B. Verify bucket sudah public
```bash
docker compose exec php curl -s http://minio:9000/obe/ | head -5
```

Seharusnya menampilkan XML listing bucket contents.

### 2. Configure untuk Production

#### Update `.env` di production:

```env
# Minimal config (untuk akses internal dari PHP)
MINIO_ENDPOINT=http://minio:9000
MINIO_URL=https://yourdomain.com/s3/obe

# Atau jika di server yang sama:
MINIO_ENDPOINT=http://localhost:9000
MINIO_URL=https://yourdomain.com/s3/obe

# Untuk presigned URLs (file upload/download)
# AppServiceProvider otomatis akan convert ke:
# https://yourdomain.com/s3/obe/path/to/file
```

### 3. Setup Nginx Reverse Proxy

#### Option A: Existing Nginx Config (RECOMMENDED)

`nginx/default.conf` sudah ada `/s3/` location yang proxy ke MinIO:

```nginx
location /s3/ {
    proxy_pass http://minio:9000/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_request_buffering off;
    client_max_body_size 100M;
}
```

Dengan setup ini:
- Browser request: `https://yourdomain.com/s3/obe/universities/logos/file.png`
- Nginx proxy ke: `http://minio:9000/obe/universities/logos/file.png`
- Automatic HTTPS ✓
- Works dari browser ✓

#### Option B: Separate MinIO Domain (Alternative)

Jika ingin MinIO di subdomain terpisah:

```nginx
server {
    listen 443 ssl http2;
    server_name s3.yourdomain.com;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    
    location / {
        proxy_pass http://minio:9000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_request_buffering off;
    }
}
```

Kemudian update `.env`:
```env
MINIO_URL=https://s3.yourdomain.com/obe
```

### 4. AppServiceProvider URL Conversion

File `app/Providers/AppServiceProvider.php` otomatis:
- Mendeteksi request dari HTTPS
- Convert presigned URLs dari `http://minio:9000` → `https://yourdomain.com/s3`
- Handle file upload/download dengan proper HTTPS

Tidak perlu konfigurasi manual untuk ini.

### 5. Verification Checklist

- [ ] MinIO bucket publik: `php artisan minio:public`
- [ ] `.env` punya `MINIO_URL=https://yourdomain.com/s3/obe`
- [ ] Nginx `/s3/` location terkonfigurasi
- [ ] SSL certificate valid
- [ ] Test akses file: `curl https://yourdomain.com/s3/obe/universities/logos/test.png`

### 6. Troubleshooting

#### File upload berhasil tapi gambar tidak muncul (404)
✓ Pastikan bucket publik: `php artisan minio:public`
✓ Cek file sudah di MinIO: browse `http://localhost:9001` (console)

#### Mixed content error
✓ Pastikan `MINIO_URL` pakai HTTPS
✓ AppServiceProvider akan auto-convert URLs

#### Slow file download
✓ Increase nginx timeout di `/s3/` location:
```nginx
proxy_connect_timeout 300s;
proxy_send_timeout 300s;
proxy_read_timeout 300s;
```

#### Upload file besar gagal
✓ Check `client_max_body_size` di nginx (default 100M)
✓ Ubah kalau perlu lebih besar:
```nginx
client_max_body_size 500M;
```

## Commands Reference

```bash
# Setup MinIO bucket dan directories
docker compose exec php php artisan minio:setup

# Set bucket ke public read
docker compose exec php php artisan minio:public

# Clear config cache (jika ubah .env)
docker compose exec php php artisan config:clear

# Test S3 connection
docker compose exec php php artisan tinker
```

Dalam tinker:
```php
Storage::disk('minio')->exists('test.txt') // false jika file belum ada
Storage::disk('minio')->put('test.txt', 'hello') // buat file
Storage::disk('minio')->url('test.txt') // dapatkan public URL
Storage::disk('minio')->delete('test.txt') // hapus file
```

## Architecture

```
Browser (HTTPS)
    ↓
Nginx /s3/ (proxy_pass)
    ↓
MinIO (HTTP internal)
    ↓
File in MinIO storage

User uploads file → PHP → MinIO → Browser GET via Nginx /s3/ path
```

## Important Notes

1. **MinIO URL Configuration**
   - `MINIO_ENDPOINT`: Internal connection dari PHP ke MinIO (bisa HTTP)
   - `MINIO_URL`: Public URL yang diakses browser (harus HTTPS di production)

2. **AppServiceProvider**
   - Otomatis replace `minio:9000` dengan domain + `/s3` path
   - Hanya terjadi kalau `request()->secure()` = true
   - Presigned URLs otomatis jadi HTTPS

3. **Security**
   - MinIO bucket sudah public (hanya GET diizinkan)
   - PUT/DELETE require authentication
   - Jangan expose MinIO port langsung, selalu via Nginx proxy

4. **Performance**
   - Nginx proxy buffering disabled untuk streaming large files
   - Timeouts diatur untuk handle file besar
   - CDN dapat ditambah di depan untuk faster distribution
