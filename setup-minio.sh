#!/bin/bash

# MinIO Setup Script for OBE Project
# This script automates MinIO bucket creation, configuration, and testing

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Main script
main() {
    print_header "MinIO Setup Script for OBE"

    # Check if docker compose is available
    print_info "Checking Docker Compose..."
    if ! command -v docker &> /dev/null && ! command -v docker-compose &> /dev/null; then
        print_error "Docker or Docker Compose not found"
        exit 1
    fi
    print_success "Docker Compose found"

    # Check if PHP container is running
    print_info "Checking PHP container status..."
    if ! docker compose ps php | grep -q "Up"; then
        print_warning "PHP container not running, starting containers..."
        docker compose up -d
        sleep 10
    fi
    print_success "PHP container is running"

    # Step 1: Clear config cache
    print_header "Step 1: Clear Config Cache"
    print_info "Clearing Laravel config cache..."
    if docker compose exec -T php php artisan config:clear; then
        print_success "Config cache cleared"
    else
        print_error "Failed to clear config cache"
        exit 1
    fi

    # Step 2: Setup MinIO bucket
    print_header "Step 2: Setup MinIO Bucket"
    print_info "Creating MinIO bucket and directories..."
    if docker compose exec -T php php artisan minio:setup; then
        print_success "MinIO setup completed"
    else
        print_error "Failed to setup MinIO"
        exit 1
    fi

    # Step 3: Set bucket to public
    print_header "Step 3: Set Bucket to Public"
    print_info "Setting MinIO bucket to public read..."
    if docker compose exec -T php php artisan minio:public; then
        print_success "MinIO bucket set to public"
    else
        print_error "Failed to set bucket public"
        exit 1
    fi

    # Step 4: Test MinIO access
    print_header "Step 4: Testing MinIO Access"
    
    print_info "Testing bucket listing..."
    if docker compose exec -T php curl -s http://minio:9000/obe/ &> /dev/null; then
        print_success "Bucket listing successful"
    else
        print_error "Failed to list bucket"
        exit 1
    fi

    print_info "Testing file upload..."
    if docker compose exec -T php curl -s -X GET "http://minio:9000/obe/universities/logos/.gitkeep" &> /dev/null; then
        print_success "File access test passed"
    else
        print_warning "Could not verify file access (bucket may be empty)"
    fi

    # Step 5: Display configuration
    print_header "Step 5: Configuration Summary"
    
    echo -e "${BLUE}MinIO Configuration:${NC}"
    echo "  Bucket Name: obe"
    echo "  Endpoint: http://minio:9000"
    echo "  Browser URL: http://localhost:9000/obe"
    echo "  Console URL: http://localhost:9001"
    echo "  Username: minioadmin"
    echo "  Password: minioadmin"
    
    echo -e "\n${BLUE}Environment Variables (.env):${NC}"
    echo "  FILESYSTEM_DISK=minio"
    echo "  MINIO_ACCESS_KEY=minioadmin"
    echo "  MINIO_SECRET_KEY=minioadmin"
    echo "  MINIO_REGION=us-east-1"
    echo "  MINIO_BUCKET=obe"
    echo "  MINIO_ENDPOINT=http://minio:9000"
    echo "  MINIO_URL=http://localhost:9000/obe"
    
    echo -e "\n${BLUE}For Production:${NC}"
    echo "  Update MINIO_URL to: https://yourdomain.com/s3/obe"
    echo "  Ensure Nginx /s3/ path is configured"

    # Step 6: Useful commands
    print_header "Useful Commands"
    
    echo -e "${YELLOW}Test MinIO connection:${NC}"
    echo "  docker compose exec php php artisan tinker"
    echo "  > Storage::disk('minio')->exists('test.txt')"
    echo "  > Storage::disk('minio')->put('test.txt', 'hello')"
    echo "  > Storage::disk('minio')->url('test.txt')"
    echo "  > Storage::disk('minio')->delete('test.txt')"
    
    echo -e "\n${YELLOW}Access MinIO Console:${NC}"
    echo "  URL: http://localhost:9001"
    echo "  Username: minioadmin"
    echo "  Password: minioadmin"
    
    echo -e "\n${YELLOW}View file logs:${NC}"
    echo "  docker compose logs minio"
    
    echo -e "\n${YELLOW}Reset everything:${NC}"
    echo "  docker compose down -v minio"
    echo "  rm -rf ./minio/data"
    echo "  docker compose up -d minio"
    echo "  ./setup-minio.sh"

    # Success message
    print_header "Setup Complete! 🎉"
    print_success "MinIO is ready to use"
    print_info "You can now upload and download files through the Filament admin panel"
}

# Run main function
main "$@"
