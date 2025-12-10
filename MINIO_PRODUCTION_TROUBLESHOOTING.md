# MinIO Production Troubleshooting

## Error: "Could not resolve host: minio.djambred.my.id"

### Root Cause
`MINIO_ENDPOINT` di `.env` menggunakan hostname yang tidak bisa di-resolve dari PHP container.

### Solutions

#### Solution 1: Use Docker Internal Hostname (RECOMMENDED)

Jika MinIO running in container yang sama docker-compose:

**Update `.env`:**
```env
MINIO_ENDPOINT=http://minio:9000
MINIO_URL=https://yourdomain.com/s3/obe
```

**Why it works:**
- `minio` adalah service name di docker-compose.yml
- Docker internal DNS otomatis resolve `minio` ke MinIO container
- Hanya bekerja kalau PHP dan MinIO di network yang sama

#### Solution 2: Use Localhost

Jika MinIO port 9000 exposed ke localhost:

**Update `.env`:**
```env
MINIO_ENDPOINT=http://localhost:9000
MINIO_URL=https://yourdomain.com/s3/obe
```

#### Solution 3: Use Internal IP/Hostname

Jika MinIO di server/container dengan IP/hostname berbeda:

**Update `.env`:**
```env
MINIO_ENDPOINT=http://192.168.x.x:9000
# atau
MINIO_ENDPOINT=http://minio.internal.local:9000
```

**Setup:**
- Add entry ke `/etc/hosts` atau configure DNS
- Example: `192.168.1.100  minio.internal.local`

#### Solution 4: Use HTTPS MinIO (Advanced)

Jika MinIO sudah punya SSL certificate:

**Update `.env`:**
```env
MINIO_ENDPOINT=https://minio.yourdomain.com:9000
MINIO_URL=https://yourdomain.com/s3/obe
```

**Setup:**
- Configure nginx reverse proxy dengan SSL
- Ensure MinIO punya valid SSL certificate
- Allow self-signed certificates (if needed):

```php
// In MinioSetup.php or AppServiceProvider
$client = new S3Client([
    'verify' => false, // Only for self-signed certs!
    // ... other config
]);
```

---

## How to Determine Correct MINIO_ENDPOINT

### 1. Check Container Network

```bash
# List running containers
docker ps --format "table {{.Names}}\t{{.Networks}}"

# Inspect PHP container network
docker inspect <php-container-name> | grep -A 10 NetworkSettings
```

### 2. Check Docker Compose Network

```bash
# View docker-compose services
docker compose config | grep -A 5 "services:"

# Check network connections
docker network ls
docker network inspect <network-name>
```

### 3. Test Connectivity from PHP Container

```bash
# Test if minio:9000 is reachable
docker compose exec php ping minio

# Test HTTP connection
docker compose exec php curl http://minio:9000/minio/health/live

# Test health endpoint
docker compose exec php curl http://localhost:9000/minio/health/live
```

---

## Configuration Helper Script

Use the interactive script to determine correct endpoint:

```bash
./setup-minio-config.sh
```

Script akan:
1. List semua running containers
2. Ask where MinIO is located
3. Test connectivity
4. Update .env automatically

---

## Complete Setup Procedure untuk Production

### Step 1: Determine MinIO Endpoint

```bash
./setup-minio-config.sh
```

### Step 2: Verify .env Configuration

```env
FILESYSTEM_DISK=minio
MINIO_ACCESS_KEY=minioadmin
MINIO_SECRET_KEY=minioadmin
MINIO_REGION=us-east-1
MINIO_BUCKET=obe
MINIO_ENDPOINT=???  # Set correctly based on Step 1
MINIO_URL=https://yourdomain.com/s3/obe
```

### Step 3: Clear Config Cache

```bash
docker compose exec php php artisan config:clear
```

### Step 4: Run Setup Commands

```bash
docker compose exec php php artisan minio:setup
docker compose exec php php artisan minio:public
```

### Step 5: Test

```bash
# Test bucket access
docker compose exec php curl http://minio:9000/obe

# Test file upload via Filament
# Go to Admin → Universities → Edit any record → Upload logo
```

---

## Common Issues & Fixes

### Issue 1: Container Name Not Found

**Error:**
```
cURL error 6: Could not resolve host: minio.example.com
```

**Fix:**
- Check container is running: `docker ps | grep minio`
- Use service name from docker-compose.yml: `minio` (not `obe_minio`)
- Or use `localhost` if port exposed

### Issue 2: Network Not Connected

**Error:**
```
Connection refused / No route to host
```

**Fix:**
- Ensure PHP and MinIO in same docker network
- Check docker-compose.yml networks configuration
- Restart containers: `docker compose down && docker compose up -d`

### Issue 3: Port Not Exposed

**Error:**
```
Connection refused on localhost:9000
```

**Fix:**
- Check docker-compose.yml ports section for MinIO
- Use internal hostname `minio:9000` instead of `localhost:9000`
- Or expose port: add `ports: ["9000:9000"]` to MinIO service

### Issue 4: SSL Certificate Issues

**Error:**
```
cURL error 60: SSL certificate problem
```

**Fix:**
- For self-signed: use `http://` instead of `https://`
- Or disable verify in code (not recommended)
- Or install valid certificate

---

## Production Checklist

- [ ] Determine correct MINIO_ENDPOINT
  - [ ] Run `./setup-minio-config.sh`
  - [ ] Test connectivity from PHP container
- [ ] Update .env with correct values
- [ ] Change default credentials
  - [ ] MINIO_ACCESS_KEY
  - [ ] MINIO_SECRET_KEY
- [ ] Set MINIO_URL to production domain (HTTPS)
- [ ] Clear config cache
- [ ] Run `php artisan minio:setup`
- [ ] Run `php artisan minio:public`
- [ ] Test file upload in Filament
- [ ] Test file access via public URL
- [ ] Setup backup strategy
- [ ] Monitor disk usage

---

## Production Credentials

### DO NOT use default credentials in production!

Change credentials in `.env`:
```env
MINIO_ACCESS_KEY=your-secure-access-key
MINIO_SECRET_KEY=your-secure-secret-key
```

Then update MinIO:
```bash
# Set new root user/password in MinIO
docker compose exec minio mc admin user add minio <NEW_ACCESS> <NEW_SECRET>

# OR restart with new credentials
docker compose down minio
# Update docker-compose.yml with new MINIO_ROOT_USER and MINIO_ROOT_PASSWORD
docker compose up -d minio
```

---

## Nginx Configuration for Production

Ensure nginx `/s3/` location is configured:

```nginx
location /s3/ {
    proxy_pass http://minio:9000/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_request_buffering off;
    client_max_body_size 0;  # No limit
    
    # Timeouts for large files
    proxy_connect_timeout 600s;
    proxy_send_timeout 600s;
    proxy_read_timeout 600s;
}
```

---

## Useful Debugging Commands

```bash
# Check if MinIO is responding
docker compose exec php curl -v http://minio:9000/minio/health/live

# List bucket contents
docker compose exec php curl http://minio:9000/obe/

# Check MinIO logs
docker compose logs minio

# Test Laravel Storage
docker compose exec php php artisan tinker
> Storage::disk('minio')->exists('test.txt')

# Test connectivity from PHP
docker compose exec php sh -c 'echo "Trying minio:9000..." && nc -zv minio 9000'
```

---

## Support

If still having issues:
1. Check `.env` is correct
2. Verify containers are running: `docker ps`
3. Check logs: `docker compose logs php minio`
4. Try debugging commands above
5. Ensure network connectivity between containers
