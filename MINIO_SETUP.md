# MinIO Setup untuk Production

## Problem
Error di production: `League\Flysystem\UnableToCheckDirectoryExistence`

## Penyebab
1. Bucket MinIO belum dibuat
2. Direktori yang diperlukan belum ada
3. Konfigurasi filesystem belum di-set ke MinIO

## Solusi

### 1. Pastikan .env di Production
```env
FILESYSTEM_DISK=minio

# MinIO Configuration
MINIO_ACCESS_KEY=minioadmin  # Ganti dengan credential production
MINIO_SECRET_KEY=minioadmin  # Ganti dengan credential production
MINIO_REGION=us-east-1
MINIO_BUCKET=obe
MINIO_ENDPOINT=http://minio:9000  # Atau URL MinIO production
MINIO_URL=http://localhost:9000/obe  # Public URL untuk akses file
```

### 2. Jalankan Command Setup
```bash
php artisan minio:setup
```

Command ini akan:
- ✅ Cek/buat bucket `obe`
- ✅ Buat direktori: `universities/logos`, `faculties/logos`, `study-programs/logos`, `temp`
- ✅ Set bucket policy ke public read
- ✅ Test write/read file

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

## Verifikasi

### Test Upload via Tinker
```bash
php artisan tinker
```

```php
Storage::disk('minio')->put('test.txt', 'Hello MinIO');
Storage::disk('minio')->exists('test.txt'); // Harus return true
Storage::disk('minio')->url('test.txt'); // Lihat URL file
```

### Cek via MinIO Console
1. Buka: `http://localhost:9001` (MinIO Console)
2. Login: minioadmin / minioadmin
3. Cek bucket `obe` → harus ada direktori yang dibuat

## Troubleshooting

### Error: "Access Denied"
- Cek credential di .env (MINIO_ACCESS_KEY & MINIO_SECRET_KEY)
- Pastikan MinIO container running: `docker ps | grep minio`

### Error: "Connection refused"
- Cek MINIO_ENDPOINT
- Dari dalam container PHP, endpoint = `http://minio:9000`
- Dari luar container (browser), endpoint = `http://localhost:9000`

### Error: "Bucket not found"
- Jalankan `php artisan minio:setup` untuk buat bucket

### File tidak bisa diakses public
- Cek MINIO_URL di .env
- Pastikan bucket policy sudah di-set (command setup otomatis set ini)
- Untuk MinIO, akses file: `http://localhost:9000/obe/path/to/file.png`

## Production Notes

1. **Ganti Credential Default**
   ```env
   MINIO_ACCESS_KEY=production_access_key
   MINIO_SECRET_KEY=production_secret_key_yang_kuat
   ```

2. **HTTPS untuk Production**
   ```env
   MINIO_ENDPOINT=https://minio.yourdomain.com
   MINIO_URL=https://minio.yourdomain.com/obe
   ```

3. **Backup Bucket**
   - Setup MinIO replication atau backup schedule
   - Export bucket policy: `mc admin policy export myminio obe`

4. **Monitoring**
   - Monitor disk usage: `mc admin info myminio`
   - Cek health: `curl http://localhost:9000/minio/health/live`

## Resource Files yang Menggunakan File Upload

1. **UniversityResource** - Logo universitas (`universities/logos/`)
2. **FacultyResource** - Logo fakultas (`faculties/logos/`)
3. **StudyProgramResource** - Logo prodi (`study-programs/logos/`)

Semua menggunakan `FileUpload::make()` yang otomatis simpan ke disk default (minio).
