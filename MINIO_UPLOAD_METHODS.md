# MinIO Upload Solutions - Local & Production

## 🎯 Tiga Metode Upload yang Sudah Diimplementasi

### Method 1: Pre-Signed URL (RECOMMENDED untuk Production) ✅
**Keuntungan:**
- ✅ No CORS issues
- ✅ Direct browser → MinIO (fast)
- ✅ No backend bottleneck
- ✅ Secure dengan signed URL
- ✅ Works dengan private bucket

**Cara Kerja:**
```
Frontend → Laravel API → Generate signed URL
Frontend → Upload direct ke MinIO (via signed URL)
```

**Implementation:**

```javascript
// Frontend: Get upload URL
const response = await fetch('/api/files/upload-url', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    filename: file.name,
    folder: 'universities', // or faculties, study-programs, etc
    mime_type: file.type
  })
});

const { upload_url, path, public_url } = await response.json();

// Direct upload to MinIO
await fetch(upload_url, {
  method: 'PUT',
  body: file,
  headers: {
    'Content-Type': file.type
  }
});

// Save path to database
console.log('File uploaded to:', path);
console.log('Public URL:', public_url);
```

**Perfect untuk:**
- Production dengan traffic tinggi
- Upload file besar
- Private files yang perlu access control

---

### Method 2: Upload via Backend Proxy ✅
**Keuntungan:**
- ✅ 100% no CORS
- ✅ Simple frontend code
- ✅ Backend validation & processing
- ✅ Auto authentication via Laravel session

**Cara Kerja:**
```
Frontend → Laravel → MinIO
```

**Implementation:**

```javascript
// Frontend: Upload via backend
const formData = new FormData();
formData.append('file', file);
formData.append('folder', 'universities');

const response = await fetch('/api/files/upload', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`
  },
  body: formData
});

const { path, url } = await response.json();
console.log('Uploaded to:', path);
```

**Perfect untuk:**
- Local development
- Fallback ketika signed URL gagal
- Perlu validasi/processing di backend
- File kecil-menengah (< 10MB)

---

### Method 3: Nginx Proxy Upload ✅
**Keuntungan:**
- ✅ Direct browser → Nginx → MinIO
- ✅ CORS handled by Nginx
- ✅ No PHP bottleneck
- ✅ Support streaming large files

**Cara Kerja:**
```
Frontend → Nginx /minio-upload/ → MinIO
```

**Implementation:**

```javascript
// Frontend: Upload via Nginx proxy
const response = await fetch('/minio-upload/obe/universities/logos/' + filename, {
  method: 'PUT',
  body: file,
  headers: {
    'Content-Type': file.type
  }
});

if (response.ok) {
  const url = '/s3/obe/universities/logos/' + filename;
  console.log('Uploaded, accessible at:', url);
}
```

**Perfect untuk:**
- Public uploads
- No authentication needed
- Fastest upload (no PHP overhead)

---

## 📁 File Structure

```
/root/perkuliahan/obe/
├── src/
│   ├── app/
│   │   └── Http/
│   │       └── Controllers/
│   │           └── Api/
│   │               └── FileUploadController.php  ← Backend controller
│   └── routes/
│       └── api.php  ← API routes
└── nginx/
    └── default.conf  ← Nginx config dengan 3 locations
```

---

## 🚀 Setup untuk Local Development

### 1. MinIO Configuration
```env
# .env
FILESYSTEM_DISK=minio
MINIO_ACCESS_KEY=minioadmin
MINIO_SECRET_KEY=minioadmin
MINIO_REGION=us-east-1
MINIO_BUCKET=obe
MINIO_ENDPOINT=http://minio:9000
MINIO_URL=http://localhost:9000/obe
```

### 2. Setup Bucket
```bash
./setup-minio.sh
```

### 3. Test Upload
```bash
# Method 1: Pre-signed URL
curl -X POST http://localhost/api/files/upload-url \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"filename":"test.png","folder":"universities","mime_type":"image/png"}'

# Method 2: Backend proxy
curl -X POST http://localhost/api/files/upload \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "file=@test.png" \
  -F "folder=universities"

# Method 3: Nginx proxy
curl -X PUT http://localhost/minio-upload/obe/universities/logos/test.png \
  --data-binary @test.png \
  -H "Content-Type: image/png"
```

---

## 🌐 Setup untuk Production

### 1. Update .env
```env
FILESYSTEM_DISK=minio
MINIO_ENDPOINT=http://minio:9000  # Internal connection
MINIO_URL=https://yourdomain.com/s3/obe  # Public HTTPS URL
```

### 2. Nginx Configuration
Already configured in `nginx/default.conf`:
- `/s3/` - MinIO S3 API access (for downloads)
- `/minio-upload/` - Upload proxy dengan CORS

### 3. Setup Bucket
```bash
php artisan minio:setup
php artisan minio:public
```

### 4. Test dari Production
```javascript
// Method 1 (recommended)
const { upload_url } = await fetch('/api/files/upload-url', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    filename: 'logo.png',
    folder: 'universities',
    mime_type: 'image/png'
  })
}).then(r => r.json());

// Upload akan ke: https://yourdomain.com/s3/obe/...?signature=...
await fetch(upload_url, {
  method: 'PUT',
  body: file
});
```

---

## 🔍 Which Method to Use?

### Local Development
**Use Method 2** (Backend Proxy):
- Simple
- No setup hassle
- Good for testing

```javascript
// Just FormData upload
const formData = new FormData();
formData.append('file', file);
formData.append('folder', 'universities');
await fetch('/api/files/upload', { method: 'POST', body: formData });
```

### Production
**Use Method 1** (Pre-Signed URL):
- Best performance
- Scalable
- Secure
- No backend bottleneck

```javascript
// Get signed URL first
const { upload_url, path } = await fetch('/api/files/upload-url', {
  method: 'POST',
  body: JSON.stringify({ filename: file.name, folder: 'universities' })
}).then(r => r.json());

// Then upload direct
await fetch(upload_url, { method: 'PUT', body: file });
```

### Public Uploads (No Auth)
**Use Method 3** (Nginx Proxy):
- Fastest
- No authentication
- Direct upload

```javascript
await fetch(`/minio-upload/obe/${folder}/${filename}`, {
  method: 'PUT',
  body: file
});
```

---

## 🔧 API Endpoints

### POST /api/files/upload-url
Generate pre-signed upload URL

**Request:**
```json
{
  "filename": "logo.png",
  "folder": "universities",
  "mime_type": "image/png"
}
```

**Response:**
```json
{
  "success": true,
  "upload_url": "https://domain.com/s3/obe/universities/logos/01ABC.png?signature=...",
  "path": "universities/logos/01ABC.png",
  "public_url": "https://domain.com/s3/obe/universities/logos/01ABC.png",
  "expires_in": 900
}
```

### POST /api/files/upload
Upload file via backend

**Request:**
```
multipart/form-data
- file: <binary>
- folder: "universities"
```

**Response:**
```json
{
  "success": true,
  "path": "universities/logos/01ABC.png",
  "url": "https://domain.com/s3/obe/universities/logos/01ABC.png",
  "size": 123456,
  "mime_type": "image/png"
}
```

### POST /api/files/download-url
Generate temporary download URL

**Request:**
```json
{
  "path": "universities/logos/01ABC.png"
}
```

**Response:**
```json
{
  "success": true,
  "url": "https://domain.com/s3/obe/universities/logos/01ABC.png?signature=...",
  "expires_in": 3600
}
```

### DELETE /api/files/delete
Delete file from MinIO

**Request:**
```json
{
  "path": "universities/logos/01ABC.png"
}
```

**Response:**
```json
{
  "success": true,
  "message": "File deleted successfully"
}
```

---

## 🛠️ Troubleshooting

### Upload gagal (CORS error)
✅ **Use Method 1 atau 2** (bukan Method 3 untuk authenticated uploads)

### Upload lambat
✅ **Use Method 1** (Pre-signed URL) - Direct ke MinIO, bypass PHP

### "Could not resolve host"
✅ Check `.env`:
```env
MINIO_ENDPOINT=http://minio:9000  # NOT https://minio.domain.com
```

### File uploaded tapi tidak bisa diakses (404)
✅ Ensure MINIO_URL correct:
```env
MINIO_URL=https://yourdomain.com/s3/obe  # Production
MINIO_URL=http://localhost:9000/obe     # Local
```

### Mixed content error (HTTPS page, HTTP request)
✅ AppServiceProvider otomatis handle ini, pastikan MINIO_URL pakai HTTPS

---

## 📊 Performance Comparison

| Method | Speed | Backend Load | CORS | Auth | Best For |
|--------|-------|--------------|------|------|----------|
| Pre-Signed URL | ⚡⚡⚡ | Low | ✅ | ✅ | Production |
| Backend Proxy | ⚡⚡ | High | ✅ | ✅ | Local Dev |
| Nginx Proxy | ⚡⚡⚡ | None | ✅ | ❌ | Public Uploads |

---

## ✅ Checklist

### Local Setup
- [ ] Run `./setup-minio.sh`
- [ ] Test Method 2 (backend proxy)
- [ ] Verify files accessible via `http://localhost:9000/obe/...`

### Production Setup
- [ ] Update .env with correct endpoints
- [ ] Run `php artisan minio:setup && php artisan minio:public`
- [ ] Test Method 1 (pre-signed URL)
- [ ] Verify HTTPS access works
- [ ] Test from Filament admin panel

---

## 🎉 Summary

**Sudah Ready:**
✅ 3 metode upload (pilih sesuai kebutuhan)
✅ API endpoints lengkap dengan auth
✅ Nginx proxy dengan CORS
✅ Pre-signed URL support
✅ Local & Production configs
✅ AppServiceProvider auto-convert URLs

**Tinggal Pakai:**
```bash
# Setup bucket
./setup-minio.sh

# Test upload
curl -X POST /api/files/upload -F "file=@test.png" -F "folder=universities"
```

Done! 🚀
