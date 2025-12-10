#!/bin/bash

# MinIO Production Configuration Helper
# Use this to properly configure MinIO endpoint for production

set -e

print_header() {
    echo "================================"
    echo "$1"
    echo "================================"
}

print_info() {
    echo "[INFO] $1"
}

print_error() {
    echo "[ERROR] $1"
}

print_success() {
    echo "[SUCCESS] $1"
}

main() {
    print_header "MinIO Production Configuration"

    # Check current environment
    print_info "Current Docker Setup:"
    print_info "Checking container names..."
    
    if command -v docker &> /dev/null; then
        echo ""
        echo "Active containers:"
        docker ps --format "table {{.Names}}\t{{.Status}}" 2>/dev/null || echo "Could not list containers"
    else
        print_error "Docker not found"
        exit 1
    fi

    echo ""
    print_header "Configuration Options"

    echo ""
    echo "Option 1: Docker Internal Network (RECOMMENDED)"
    echo "  Use when all services in same docker-compose network"
    echo "  MINIO_ENDPOINT=http://minio:9000"
    echo "  MINIO_URL=https://yourdomain.com/s3/obe"
    echo ""

    echo "Option 2: Localhost"
    echo "  Use when MinIO exposed on localhost:9000"
    echo "  MINIO_ENDPOINT=http://localhost:9000"
    echo "  MINIO_URL=https://yourdomain.com/s3/obe"
    echo ""

    echo "Option 3: Custom Hostname"
    echo "  Use when MinIO on different server/host"
    echo "  MINIO_ENDPOINT=http://minio.internal.local:9000"
    echo "  MINIO_URL=https://yourdomain.com/s3/obe"
    echo ""

    echo "Option 4: HTTPS MinIO"
    echo "  Use when MinIO has SSL certificate"
    echo "  MINIO_ENDPOINT=https://minio.yourdomain.com:9000"
    echo "  MINIO_URL=https://yourdomain.com/s3/obe"
    echo ""

    print_header "Which Option?"
    
    echo "Q: Where is MinIO running?"
    echo "1. Same server, in docker containers (Option 1)"
    echo "2. Localhost with exposed port 9000 (Option 2)"
    echo "3. Different server on internal network (Option 3)"
    echo "4. HTTPS enabled MinIO server (Option 4)"
    echo ""

    read -p "Enter 1-4 or provide custom endpoint: " choice

    case $choice in
        1)
            endpoint="http://minio:9000"
            print_success "Using Docker internal network"
            ;;
        2)
            endpoint="http://localhost:9000"
            print_success "Using localhost"
            ;;
        3)
            read -p "Enter MinIO hostname/IP (e.g., minio.internal.local): " hostname
            endpoint="http://$hostname:9000"
            print_success "Using custom hostname: $hostname"
            ;;
        4)
            read -p "Enter MinIO HTTPS URL (e.g., https://minio.yourdomain.com): " url
            endpoint="$url"
            print_success "Using HTTPS MinIO: $url"
            ;;
        *)
            endpoint=$choice
            print_info "Using custom endpoint: $endpoint"
            ;;
    esac

    # Test connection
    echo ""
    print_info "Testing connection to: $endpoint"
    
    if command -v curl &> /dev/null; then
        if timeout 5 curl -s "$endpoint/minio/health/live" &> /dev/null; then
            print_success "Connection successful!"
        else
            print_error "Could not connect to MinIO"
            print_info "Endpoint might be incorrect or MinIO is not running"
            read -p "Continue anyway? (y/n): " confirm
            if [[ ! $confirm =~ ^[Yy]$ ]]; then
                exit 1
            fi
        fi
    else
        print_info "curl not available, skipping connection test"
    fi

    # Show .env configuration
    echo ""
    print_header ".env Configuration"
    
    echo ""
    echo "Update your .env file with:"
    echo ""
    echo "FILESYSTEM_DISK=minio"
    echo "MINIO_ACCESS_KEY=minioadmin"
    echo "MINIO_SECRET_KEY=minioadmin"
    echo "MINIO_REGION=us-east-1"
    echo "MINIO_BUCKET=obe"
    echo "MINIO_ENDPOINT=$endpoint"
    echo "MINIO_URL=https://yourdomain.com/s3/obe"
    echo ""

    # Save configuration
    read -p "Save to .env file? (y/n): " save
    if [[ $save =~ ^[Yy]$ ]]; then
        if [ -f ".env" ]; then
            print_info "Updating .env file..."
            
            # Backup original
            cp .env .env.backup
            print_info "Backup created: .env.backup"
            
            # Update values using sed (with proper escaping)
            endpoint_escaped=$(echo "$endpoint" | sed 's/[&/\]/\\&/g')
            
            if grep -q "MINIO_ENDPOINT=" .env; then
                sed -i "s|MINIO_ENDPOINT=.*|MINIO_ENDPOINT=$endpoint_escaped|" .env
            else
                echo "MINIO_ENDPOINT=$endpoint" >> .env
            fi
            
            print_success ".env updated"
            print_info "Original backed up to .env.backup"
        else
            print_error ".env file not found"
        fi
    fi

    # Next steps
    echo ""
    print_header "Next Steps"
    
    echo ""
    echo "1. Verify .env configuration"
    echo "   Check that MINIO_ENDPOINT is set correctly"
    echo ""
    
    echo "2. Clear config cache"
    echo "   docker compose exec php php artisan config:clear"
    echo ""
    
    echo "3. Run MinIO setup"
    echo "   docker compose exec php php artisan minio:setup"
    echo "   docker compose exec php php artisan minio:public"
    echo ""
    
    echo "4. Test file upload in Filament admin"
    echo "   Upload logo in Universities or Faculties resource"
    echo ""

    print_success "Configuration helper completed"
}

main "$@"
