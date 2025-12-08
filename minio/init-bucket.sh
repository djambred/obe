#!/bin/bash

# Initialize MinIO bucket script
# This script should be run after MinIO starts

echo "Waiting for MinIO to be ready..."
sleep 10

# Install mc (MinIO Client) if not installed
if ! command -v mc &> /dev/null; then
    echo "Installing MinIO Client..."
    wget https://dl.min.io/client/mc/release/linux-amd64/mc
    chmod +x mc
    sudo mv mc /usr/local/bin/
fi

# Configure MinIO client
mc alias set myminio http://localhost:9000 minioadmin minioadmin

# Create bucket if not exists
mc mb myminio/obe --ignore-existing

# Set public policy for certain paths (optional)
mc anonymous set download myminio/obe/public

# Create directory structure
mc mb myminio/obe/documents --ignore-existing
mc mb myminio/obe/images --ignore-existing
mc mb myminio/obe/rps --ignore-existing
mc mb myminio/obe/curriculum --ignore-existing
mc mb myminio/obe/profiles --ignore-existing

echo "MinIO bucket 'obe' initialized successfully!"
