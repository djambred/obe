#!/bin/bash

# MinIO Production Diagnostic Script
# Checks MinIO configuration, connectivity, and functionality

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Counters
PASS=0
FAIL=0
WARN=0

print_header() {
    echo -e "\n${BLUE}════════════════════════════════════════${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}════════════════════════════════════════${NC}\n"
}

print_test() {
    echo -e "${BLUE}→${NC} $1"
}

print_pass() {
    echo -e "${GREEN}✓${NC} $1"
    ((PASS++))
}

print_fail() {
    echo -e "${RED}✗${NC} $1"
    ((FAIL++))
}

print_warn() {
    echo -e "${YELLOW}⚠${NC} $1"
    ((WARN++))
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_result() {
    echo -e "\n${BLUE}════════════════════════════════════════${NC}"
    echo -e "Results: ${GREEN}${PASS} passed${NC}, ${RED}${FAIL} failed${NC}, ${YELLOW}${WARN} warnings${NC}"
    echo -e "${BLUE}════════════════════════════════════════${NC}\n"
    
    if [ $FAIL -gt 0 ]; then
        return 1
    fi
    return 0
}

# Check Docker
check_docker() {
    print_header "1. Docker & Containers Check"
    
    print_test "Checking Docker availability..."
    if command -v docker &> /dev/null; then
        print_pass "Docker is installed"
    else
        print_fail "Docker not found"
        return 1
    fi
    
    print_test "Checking Docker Compose..."
    if command -v docker-compose &> /dev/null || command -v docker &> /dev/null && docker compose version &> /dev/null; then
        print_pass "Docker Compose is available"
    else
        print_fail "Docker Compose not found"
        return 1
    fi
    
    print_test "Listing running containers..."
    if docker ps --format "table {{.Names}}\t{{.Status}}" | grep -E "minio|php|nginx"; then
        print_pass "Key containers are running"
    else
        print_warn "Could not verify all containers"
    fi
}

# Check .env configuration
check_env() {
    print_header "2. Environment Configuration Check"
    
    if [ ! -f ".env" ]; then
        print_fail ".env file not found"
        return 1
    fi
    print_pass ".env file exists"
    
    print_test "Checking FILESYSTEM_DISK..."
    if grep -q "FILESYSTEM_DISK=minio" .env; then
        print_pass "FILESYSTEM_DISK=minio"
    else
        print_fail "FILESYSTEM_DISK not set to minio"
    fi
    
    print_test "Checking MINIO_ENDPOINT..."
    ENDPOINT=$(grep "MINIO_ENDPOINT=" .env | cut -d'=' -f2)
    if [ -z "$ENDPOINT" ]; then
        print_fail "MINIO_ENDPOINT not set"
    else
        print_pass "MINIO_ENDPOINT=$ENDPOINT"
    fi
    
    print_test "Checking MINIO_URL..."
    URL=$(grep "MINIO_URL=" .env | cut -d'=' -f2)
    if [ -z "$URL" ]; then
        print_fail "MINIO_URL not set"
    else
        print_pass "MINIO_URL=$URL"
    fi
    
    print_test "Checking MINIO_BUCKET..."
    if grep -q "MINIO_BUCKET=" .env; then
        BUCKET=$(grep "MINIO_BUCKET=" .env | cut -d'=' -f2)
        print_pass "MINIO_BUCKET=$BUCKET"
    else
        print_fail "MINIO_BUCKET not set"
    fi
    
    print_test "Checking MINIO credentials..."
    if grep -q "MINIO_ACCESS_KEY=" .env && grep -q "MINIO_SECRET_KEY=" .env; then
        print_pass "MinIO credentials are set"
    else
        print_fail "MinIO credentials not set"
    fi
}

# Check connectivity
check_connectivity() {
    print_header "3. Connectivity Check"
    
    ENDPOINT=$(grep "MINIO_ENDPOINT=" .env | cut -d'=' -f2)
    
    if [ -z "$ENDPOINT" ]; then
        print_fail "Cannot test connectivity - MINIO_ENDPOINT not set"
        return 1
    fi
    
    print_test "Testing connectivity to: $ENDPOINT"
    
    if command -v curl &> /dev/null; then
        if docker compose exec -T php curl -s --connect-timeout 5 "$ENDPOINT/minio/health/live" &> /dev/null; then
            print_pass "MinIO is reachable and healthy"
        else
            print_fail "MinIO health check failed"
            print_info "Trying bucket listing..."
            
            if docker compose exec -T php curl -s --connect-timeout 5 "$ENDPOINT/" | grep -q "ListBucketResult\|Error" 2>/dev/null; then
                print_warn "MinIO responds but health check failed"
            else
                print_fail "Cannot connect to MinIO"
            fi
        fi
    else
        print_warn "curl not available for connectivity test"
    fi
    
    print_test "Testing from PHP container..."
    if docker compose exec -T php php artisan tinker --execute="
        try {
            \\\$disk = \\\Illuminate\\\Support\\\Facades\\\Storage::disk('minio');
            echo 'MinIO connection successful';
        } catch (\\\Exception \\\$e) {
            echo 'Error: ' . \\\$e->getMessage();
            exit(1);
        }
    " &> /tmp/minio_test.log; then
        print_pass "PHP MinIO connection works"
    else
        print_fail "PHP MinIO connection failed"
        if [ -f /tmp/minio_test.log ]; then
            print_info "Error details:"
            cat /tmp/minio_test.log | head -5 | sed 's/^/    /'
        fi
    fi
}

# Check bucket
check_bucket() {
    print_header "4. MinIO Bucket Check"
    
    ENDPOINT=$(grep "MINIO_ENDPOINT=" .env | cut -d'=' -f2)
    BUCKET=$(grep "MINIO_BUCKET=" .env | cut -d'=' -f2)
    
    if [ -z "$ENDPOINT" ] || [ -z "$BUCKET" ]; then
        print_fail "Cannot check bucket - configuration incomplete"
        return 1
    fi
    
    print_test "Listing bucket contents..."
    if docker compose exec -T php curl -s "$ENDPOINT/$BUCKET/" | grep -q "ListBucketResult\|Error"; then
        if docker compose exec -T php curl -s "$ENDPOINT/$BUCKET/" | grep -q "ListBucketResult"; then
            print_pass "Bucket '$BUCKET' is accessible"
        else
            print_fail "Bucket exists but access denied"
        fi
    else
        print_fail "Cannot list bucket"
    fi
    
    print_test "Checking bucket policy..."
    if docker compose exec -T php curl -s "$ENDPOINT/$BUCKET/?policy" | grep -q "Statement\|Error"; then
        if docker compose exec -T php curl -s "$ENDPOINT/$BUCKET/?policy" | grep -q "Statement"; then
            print_pass "Bucket has policy configured"
        else
            print_warn "Bucket policy not set or access denied"
        fi
    else
        print_warn "Could not verify bucket policy"
    fi
    
    print_test "Checking directories..."
    for dir in "universities/logos" "faculties/logos" "study-programs/logos" "temp"; do
        if docker compose exec -T php curl -s "$ENDPOINT/$BUCKET/$dir/" | grep -q "ListBucketResult"; then
            print_pass "Directory exists: $dir"
        else
            print_warn "Directory not found or not accessible: $dir"
        fi
    done
}

# Check Laravel integration
check_laravel() {
    print_header "5. Laravel Integration Check"
    
    print_test "Checking config cache..."
    if [ -f "src/bootstrap/cache/config.php" ]; then
        print_warn "Config cache exists - may be outdated"
        print_info "Run: php artisan config:clear"
    else
        print_pass "No stale config cache"
    fi
    
    print_test "Testing Laravel Storage facade..."
    if docker compose exec -T php php -r "
        require 'vendor/autoload.php';
        \\\$app = require 'bootstrap/app.php';
        \\\$app->make('Illuminate\\\Contracts\\\Http\\\Kernel')->handle(
            \\\Illuminate\\\Http\\\Request::capture()
        );
        \\\$disk = \\\Illuminate\\\Support\\\Facades\\\Storage::disk('minio');
        echo 'Storage facade works';
    " 2>&1 | grep -q "Storage facade works"; then
        print_pass "Laravel Storage facade works"
    else
        print_fail "Laravel Storage facade error"
    fi
    
    print_test "Testing file operations..."
    if docker compose exec -T php php artisan tinker --execute="
        \\\$disk = \\\Illuminate\\\Support\\\Facades\\\Storage::disk('minio');
        \\\$disk->put('diagnostic-test.txt', 'test-' . time());
        if (\\\$disk->exists('diagnostic-test.txt')) {
            echo 'File operations work';
            \\\$disk->delete('diagnostic-test.txt');
        } else {
            exit(1);
        }
    " 2>&1 | grep -q "File operations work"; then
        print_pass "File upload/download works"
    else
        print_warn "File operations test inconclusive"
    fi
}

# Check production settings
check_production() {
    print_header "6. Production Settings Check"
    
    print_test "Checking MINIO_URL format..."
    URL=$(grep "MINIO_URL=" .env | cut -d'=' -f2)
    if [[ $URL == https://* ]]; then
        print_pass "MINIO_URL uses HTTPS: $URL"
    else
        print_warn "MINIO_URL does not use HTTPS: $URL"
    fi
    
    print_test "Checking credentials..."
    KEY=$(grep "MINIO_ACCESS_KEY=" .env | cut -d'=' -f2)
    if [ "$KEY" = "minioadmin" ]; then
        print_warn "Using default MinIO credentials (minioadmin)"
        print_info "Change credentials in production!"
    else
        print_pass "Using custom credentials"
    fi
    
    print_test "Checking Nginx proxy configuration..."
    if [ -f "nginx/default.conf" ]; then
        if grep -q "location /s3/" nginx/default.conf; then
            print_pass "Nginx /s3/ proxy location configured"
        else
            print_warn "Nginx /s3/ proxy location not found"
        fi
    else
        print_warn "Nginx config file not found"
    fi
    
    print_test "Checking security headers..."
    if [ -f "src/app/Http/Middleware/SecurityHeaders.php" ]; then
        print_pass "SecurityHeaders middleware exists"
    else
        print_warn "SecurityHeaders middleware not found"
    fi
}

# Check logs
check_logs() {
    print_header "7. Recent Logs Check"
    
    print_test "PHP error logs..."
    if docker compose logs php 2>/dev/null | grep -i "minio\|s3\|storage" | tail -5; then
        print_info "Found MinIO-related logs"
    else
        print_info "No recent MinIO errors in PHP logs"
    fi
    
    print_test "MinIO logs..."
    if docker compose logs minio 2>/dev/null | tail -5 | grep -i "error\|warn"; then
        print_warn "Found warnings in MinIO logs"
    else
        print_pass "MinIO logs look clean"
    fi
}

# Generate report
generate_report() {
    print_header "Diagnostic Summary"
    
    echo -e "Date: $(date)"
    echo -e "Hostname: $(hostname)"
    echo -e "Docker: $(docker --version 2>/dev/null || echo 'not found')"
    echo -e "PHP: $(docker compose exec -T php php --version 2>/dev/null | head -1 || echo 'not accessible')"
    echo ""
    
    echo -e "Configuration:"
    grep "MINIO_" .env | grep -v "^#" | sed 's/^/  /'
    echo ""
    
    echo -e "Running Containers:"
    docker ps --format "table {{.Names}}\t{{.Status}}" | grep -E "minio|php|nginx" || echo "  (none found)"
    echo ""
}

# Main execution
main() {
    print_header "MinIO Production Diagnostic"
    echo "This script checks MinIO configuration and connectivity"
    
    generate_report
    
    check_docker || true
    check_env || true
    check_connectivity || true
    check_bucket || true
    check_laravel || true
    check_production || true
    check_logs || true
    
    print_result
    
    if [ $FAIL -eq 0 ]; then
        echo -e "${GREEN}All checks passed! MinIO should be working correctly.${NC}\n"
    else
        echo -e "${RED}Some checks failed. Review the issues above.${NC}\n"
        echo "Common fixes:"
        echo "  1. Run: php artisan config:clear"
        echo "  2. Run: ./setup-minio.sh"
        echo "  3. Check: ./setup-minio-config.sh"
        echo "  4. Verify .env has correct MINIO_ENDPOINT"
        echo ""
    fi
}

# Run main
main "$@"
