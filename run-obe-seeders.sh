#!/bin/bash

# Script untuk menjalankan seeder CPL, CPMK, Sub-CPMK, dan RPS
# Berjalan di dalam Docker container

echo "======================================"
echo "OBE System - Seeder Script (Docker)"
echo "======================================"
echo ""

# Docker container name
CONTAINER="obe_php"

echo "🐳 Running commands inside Docker container: $CONTAINER"
echo ""

echo "1️⃣  Checking database connection..."
docker exec -it $CONTAINER php artisan db:show

echo ""
echo "2️⃣  Installing DomPDF package..."
docker exec -it $CONTAINER composer require barryvdh/laravel-dompdf
if [ $? -eq 0 ]; then
    echo "✅ DomPDF installed successfully"
else
    echo "⚠️  DomPDF installation failed or already installed"
fi

echo ""
echo "3️⃣  Running CPL (Program Learning Outcomes) Seeder..."
docker exec -it $CONTAINER php artisan db:seed --class=ProgramLearningOutcomeSeeder
if [ $? -eq 0 ]; then
    echo "✅ CPL Seeder completed successfully"
else
    echo "❌ CPL Seeder failed"
    exit 1
fi

echo ""
echo "4️⃣  Running CPMK (Course Learning Outcomes) Seeder..."
docker exec -it $CONTAINER php artisan db:seed --class=CourseLearningOutcomeSeeder
if [ $? -eq 0 ]; then
    echo "✅ CPMK Seeder completed successfully"
else
    echo "❌ CPMK Seeder failed"
    exit 1
fi

echo ""
echo "5️⃣  Running Sub-CPMK and Performance Indicators Seeder..."
docker exec -it $CONTAINER php artisan db:seed --class=SubCourseLearningOutcomeSeeder
if [ $? -eq 0 ]; then
    echo "✅ Sub-CPMK Seeder completed successfully"
else
    echo "❌ Sub-CPMK Seeder failed"
    exit 1
fi

echo ""
echo "6️⃣  Running RPS Seeder..."
docker exec -it $CONTAINER php artisan db:seed --class=RpsSeeder
if [ $? -eq 0 ]; then
    echo "✅ RPS Seeder completed successfully"
else
    echo "❌ RPS Seeder failed"
    exit 1
fi

echo ""
echo "======================================"
echo "✨ All seeders completed successfully!"
echo "======================================"
echo ""
echo "Summary:"
echo "- CPL: 23 records (Sikap, Pengetahuan, KU, KK)"
echo "- CPMK: 25 records (5 mata kuliah)"
echo "- Sub-CPMK: Multiple records with performance indicators"
echo "- RPS: 2 records (Algoritma, Machine Learning)"
echo ""
echo "Check data with:"
echo "  docker exec -it $CONTAINER php artisan tinker"
echo "  Then run: App\\Models\\ProgramLearningOutcome::count()"
echo ""
