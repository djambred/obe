#!/bin/bash

# Script untuk mengecek data OBE yang sudah ter-seed
# Berjalan di dalam Docker container

CONTAINER="obe_php"

echo "======================================"
echo "Checking OBE Data in Database (Docker)"
echo "======================================"
echo ""

echo "🐳 Container: $CONTAINER"
echo ""
echo "📊 Counting records..."
echo ""

echo "1. CPL (Program Learning Outcomes):"
docker exec $CONTAINER php artisan tinker --execute="echo 'Total: ' . App\Models\ProgramLearningOutcome::count() . ' CPL';"

echo ""
echo "2. CPMK (Course Learning Outcomes):"
docker exec $CONTAINER php artisan tinker --execute="echo 'Total: ' . App\Models\CourseLearningOutcome::count() . ' CPMK';"

echo ""
echo "3. Sub-CPMK (Sub Course Learning Outcomes):"
docker exec $CONTAINER php artisan tinker --execute="echo 'Total: ' . App\Models\SubCourseLearningOutcome::count() . ' Sub-CPMK';"

echo ""
echo "4. Performance Indicators:"
docker exec $CONTAINER php artisan tinker --execute="echo 'Total: ' . App\Models\PerformanceIndicator::count() . ' Indicators';"

echo ""
echo "5. RPS (Rencana Pembelajaran Semester):"
docker exec $CONTAINER php artisan tinker --execute="echo 'Total: ' . App\Models\Rps::count() . ' RPS';"

echo ""
echo "======================================"
echo "Detailed Breakdown"
echo "======================================"
echo ""

echo "📚 CPL by Category:"
docker exec $CONTAINER php artisan tinker --execute="
\$cpl = App\Models\ProgramLearningOutcome::selectRaw('category, count(*) as total')
    ->groupBy('category')
    ->get();
foreach(\$cpl as \$item) {
    echo \$item->category . ': ' . \$item->total . ' CPL' . PHP_EOL;
}
"

echo ""
echo "📖 CPMK by Course:"
docker exec $CONTAINER php artisan tinker --execute="
\$cpmk = App\Models\CourseLearningOutcome::with('course')
    ->get()
    ->groupBy('course.code');
foreach(\$cpmk as \$code => \$items) {
    echo \$code . ': ' . \$items->count() . ' CPMK' . PHP_EOL;
}
"

echo ""
echo "📝 RPS Details:"
docker exec $CONTAINER php artisan tinker --execute="
\$rps = App\Models\Rps::with('course')->get();
foreach(\$rps as \$item) {
    echo \$item->course->code . ' - ' . \$item->course->name . ' (' . \$item->academic_year . ')' . PHP_EOL;
}
"

echo ""
echo "======================================"
echo "✅ Data check completed!"
echo "======================================"
