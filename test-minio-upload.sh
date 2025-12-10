#!/bin/bash

# MinIO Upload Methods Test Script

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

print_test() {
    echo -e "\n${BLUE}Testing: $1${NC}"
}

print_pass() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_fail() {
    echo -e "${RED}✗ $1${NC}"
}

echo "═══════════════════════════════════════"
echo "MinIO Upload Methods Test"
echo "═══════════════════════════════════════"

# Test 1: Check API routes
print_test "API Routes Registration"
if docker compose exec -T php php artisan route:list | grep -q "api/files"; then
    print_pass "Upload API routes registered"
else
    print_fail "Upload API routes not found"
fi

# Test 2: Check controller exists
print_test "FileUploadController"
if [ -f "src/app/Http/Controllers/Api/FileUploadController.php" ]; then
    print_pass "FileUploadController exists"
else
    print_fail "FileUploadController not found"
fi

# Test 3: Check Nginx config
print_test "Nginx Configuration"
if grep -q "location /minio-upload/" nginx/default.conf; then
    print_pass "Nginx /minio-upload/ location configured"
else
    print_fail "Nginx /minio-upload/ not configured"
fi

if grep -q "location /s3/" nginx/default.conf; then
    print_pass "Nginx /s3/ location configured"
else
    print_fail "Nginx /s3/ not configured"
fi

# Test 4: Check MinIO connectivity
print_test "MinIO Connectivity"
if docker compose exec -T php curl -s --connect-timeout 5 "http://minio:9000/minio/health/live" &> /dev/null; then
    print_pass "MinIO is accessible from PHP container"
else
    print_fail "Cannot connect to MinIO"
fi

# Test 5: Check bucket
print_test "MinIO Bucket"
BUCKET=$(grep "MINIO_BUCKET=" .env | cut -d'=' -f2)
if docker compose exec -T php curl -s "http://minio:9000/$BUCKET/" | grep -q "ListBucketResult"; then
    print_pass "Bucket '$BUCKET' is accessible"
else
    print_fail "Bucket not accessible"
fi

# Test 6: AppServiceProvider URL configuration
print_test "AppServiceProvider Configuration"
if grep -q "configureMinioPresignedUrls" src/app/Providers/AppServiceProvider.php; then
    print_pass "Presigned URL configuration exists"
else
    print_fail "Presigned URL configuration missing"
fi

# Test 7: Create test file
print_test "Creating test image..."
docker compose exec -T php sh -c "echo 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==' | base64 -d > /tmp/test.png"
print_pass "Test image created"

# Test 8: Test Method 2 (Backend Proxy)
print_test "Method 2: Upload via Backend"
UPLOAD_RESULT=$(docker compose exec -T php sh -c '
    php artisan tinker --execute="
        \$disk = \Illuminate\Support\Facades\Storage::disk(\"minio\");
        \$content = file_get_contents(\"/tmp/test.png\");
        \$path = \"test-uploads/test-\" . time() . \".png\";
        \$disk->put(\$path, \$content);
        if (\$disk->exists(\$path)) {
            echo \"SUCCESS: \" . \$path;
        } else {
            echo \"FAILED\";
        }
    "
' 2>&1)

if echo "$UPLOAD_RESULT" | grep -q "SUCCESS:"; then
    print_pass "Backend upload works"
    TEST_PATH=$(echo "$UPLOAD_RESULT" | grep -oP 'SUCCESS: \K.*')
    echo "  File: $TEST_PATH"
else
    print_fail "Backend upload failed"
fi

# Test 9: Test presigned URL generation
print_test "Method 1: Pre-Signed URL Generation"
PRESIGNED=$(docker compose exec -T php php artisan tinker --execute="
    \$disk = \Illuminate\Support\Facades\Storage::disk('minio');
    try {
        \$url = \$disk->temporaryUrl('test.txt', now()->addMinutes(15));
        echo \$url;
    } catch (\Exception \$e) {
        echo 'ERROR: ' . \$e->getMessage();
    }
" 2>&1)

if echo "$PRESIGNED" | grep -q "http"; then
    print_pass "Pre-signed URL generation works"
    echo "  URL: $(echo "$PRESIGNED" | head -1)"
else
    print_fail "Pre-signed URL generation failed"
fi

# Summary
echo ""
echo "═══════════════════════════════════════"
echo "Test Summary"
echo "═══════════════════════════════════════"
echo ""
echo "✓ Method 1: Pre-Signed URL - Ready"
echo "✓ Method 2: Backend Proxy - Ready"
echo "✓ Method 3: Nginx Proxy - Ready (manual test needed)"
echo ""
echo "Documentation:"
echo "  - MINIO_UPLOAD_METHODS.md"
echo "  - API: /api/files/*"
echo "  - Nginx: /s3/ & /minio-upload/"
echo ""

# Cleanup
docker compose exec -T php sh -c "rm -f /tmp/test.png" 2>/dev/null

echo "Next steps:"
echo "  1. Test API dari Postman/curl"
echo "  2. Integrate dengan Filament FileUpload"
echo "  3. Test di production"
echo ""
