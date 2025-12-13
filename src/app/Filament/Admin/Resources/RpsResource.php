<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RpsResource\Pages;
use App\Filament\Admin\Resources\RpsResource\RelationManagers;
use App\Models\Rps;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RpsResource extends Resource
{
    protected static ?string $model = Rps::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Kurikulum & MK';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('RPS Form')
                    ->tabs([
                        // Tab 1: Identitas RPS
                        Forms\Components\Tabs\Tab::make('📋 Identitas RPS')
                            ->schema([
                                Forms\Components\Section::make('Informasi Institusi')
                                    ->description('Pilih fakultas dan program studi terlebih dahulu')
                                    ->schema([
                                        Forms\Components\Select::make('faculty_id')
                                            ->label('Fakultas')
                                            ->options(\App\Models\Faculty::pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set) {
                                                $set('study_program_id', null);
                                                $set('course_id', null);
                                            })
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('study_program_id')
                                            ->label('Program Studi')
                                            ->options(function (Forms\Get $get) {
                                                $facultyId = $get('faculty_id');
                                                if (!$facultyId) {
                                                    return [];
                                                }
                                                return \App\Models\StudyProgram::where('faculty_id', $facultyId)
                                                    ->pluck('name', 'id');
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set) {
                                                $set('course_id', null);
                                            })
                                            ->columnSpan(1)
                                            ->disabled(fn (Forms\Get $get) => !$get('faculty_id'))
                                            ->helperText('Pilih fakultas terlebih dahulu'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Informasi Mata Kuliah')
                                    ->schema([
                                        Forms\Components\Select::make('course_id')
                                            ->label('Mata Kuliah')
                                            ->options(function (Forms\Get $get) {
                                                $studyProgramId = $get('study_program_id');
                                                if (!$studyProgramId) {
                                                    return [];
                                                }
                                                return \App\Models\Course::where('study_program_id', $studyProgramId)
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($course) {
                                                        return [$course->id => "{$course->code} - {$course->name}"];
                                                    });
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->columnSpan(1)
                                            ->disabled(fn (Forms\Get $get) => !$get('study_program_id'))
                                            ->helperText('Pilih program studi terlebih dahulu'),
                                        Forms\Components\Select::make('lecturer_id')
                                            ->label('Pengembang RPS / Dosen Pengampu')
                                            ->relationship('lecturer', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('coordinator_id')
                                            ->label('Koordinator RPS')
                                            ->relationship('coordinator', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(1)
                                            ->helperText('Koordinator mata kuliah'),
                                        Forms\Components\Select::make('head_of_program_id')
                                            ->label('Ketua Program Studi')
                                            ->relationship('headOfProgram', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(1)
                                            ->helperText('Ketua prodi yang menyetujui RPS'),
                                        Forms\Components\Select::make('curriculum_id')
                                            ->label('Kurikulum')
                                            ->options(function (Forms\Get $get) {
                                                $studyProgramId = $get('study_program_id');
                                                if (!$studyProgramId) {
                                                    return [];
                                                }
                                                return \App\Models\Curriculum::where('study_program_id', $studyProgramId)
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($curriculum) {
                                                        return [$curriculum->id => "{$curriculum->code} - {$curriculum->name}"];
                                                    });
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(1)
                                            ->disabled(fn (Forms\Get $get) => !$get('study_program_id'))
                                            ->helperText('Pilih program studi terlebih dahulu'),
                                        Forms\Components\TextInput::make('academic_year')
                                            ->label('Tahun Akademik')
                                            ->placeholder('2024/2025')
                                            ->required()
                                            ->columnSpan(1)
                                            ->live(onBlur: true),
                                        Forms\Components\Select::make('semester')
                                            ->label('Semester')
                                            ->options([
                                                'Ganjil' => 'Ganjil',
                                                'Genap' => 'Genap',
                                                'Pendek' => 'Pendek'
                                            ])
                                            ->required()
                                            ->columnSpan(1)
                                            ->live(),
                                        Forms\Components\TextInput::make('version')
                                            ->label('Versi RPS')
                                            ->default('1.0')
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('class_code')
                                            ->label('Kode Kelas')
                                            ->placeholder('A')
                                            ->columnSpan(1)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                                // Check if RPS already exists
                                                $courseId = $get('course_id');
                                                $academicYear = $get('academic_year');
                                                $semester = $get('semester');
                                                $classCode = $state;

                                                if ($courseId && $academicYear && $semester) {
                                                    $exists = \App\Models\Rps::where('course_id', $courseId)
                                                        ->where('academic_year', $academicYear)
                                                        ->where('semester', $semester)
                                                        ->where('class_code', $classCode)
                                                        ->exists();

                                                    if ($exists) {
                                                        \Filament\Notifications\Notification::make()
                                                            ->warning()
                                                            ->title('RPS Sudah Ada')
                                                            ->body('RPS untuk mata kuliah ini dengan tahun akademik, semester, dan kelas yang sama sudah ada. Silakan gunakan kelas yang berbeda atau edit RPS yang sudah ada.')
                                                            ->persistent()
                                                            ->send();
                                                    }
                                                }
                                            }),

                                        Forms\Components\Placeholder::make('duplicate_check')
                                            ->label('')
                                            ->content(function (Forms\Get $get) {
                                                $courseId = $get('course_id');
                                                $academicYear = $get('academic_year');
                                                $semester = $get('semester');
                                                $classCode = $get('class_code');

                                                if (!$courseId || !$academicYear || !$semester) {
                                                    return '';
                                                }

                                                $exists = \App\Models\Rps::where('course_id', $courseId)
                                                    ->where('academic_year', $academicYear)
                                                    ->where('semester', $semester)
                                                    ->where('class_code', $classCode)
                                                    ->first();

                                                if ($exists) {
                                                    return '⚠️ **RPS sudah ada!** RPS untuk kombinasi ini sudah dibuat. Silakan ubah kelas atau edit RPS yang ada.';
                                                }

                                                return '✅ RPS belum ada untuk kombinasi ini.';
                                            })
                                            ->columnSpanFull()
                                            ->extraAttributes(['class' => 'text-sm']),
                                        Forms\Components\TextInput::make('student_quota')
                                            ->label('Kuota Mahasiswa')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(40)
                                            ->required()
                                            ->columnSpan(1),
                                    ])->columns(2),

                                Forms\Components\Section::make('Deskripsi Mata Kuliah')
                                    ->schema([
                                        Forms\Components\Textarea::make('course_description')
                                            ->label('Deskripsi Lengkap')
                                            ->placeholder('Tulis deskripsi mata kuliah secara lengkap...')
                                            ->rows(4)
                                            ->required()
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('learning_materials')
                                            ->label('Bahan Kajian / Materi Pembelajaran')
                                            ->placeholder('Tulis bahan kajian atau materi pembelajaran...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('prerequisites')
                                            ->label('Mata Kuliah Prasyarat')
                                            ->placeholder('Contoh: Algoritma Pemrograman, Struktur Data')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 2: Capaian Pembelajaran
                        Forms\Components\Tabs\Tab::make('🎯 Capaian Pembelajaran')
                            ->schema([
                                Forms\Components\Section::make('CPMK (Capaian Pembelajaran Mata Kuliah)')
                                    ->description('Pilih CPMK dari mata kuliah yang dipilih')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('clo_list')
                                            ->label('Daftar CPMK')
                                            ->options(function (Forms\Get $get) {
                                                $courseId = $get('course_id');
                                                if (!$courseId) {
                                                    return [];
                                                }
                                                return \App\Models\CourseLearningOutcome::where('course_id', $courseId)
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($clo) {
                                                        return [
                                                            $clo->code => sprintf(
                                                                "%s: %s (Bobot: %s%% | Bloom: %s)",
                                                                $clo->code,
                                                                $clo->description,
                                                                $clo->weight,
                                                                $clo->bloom_level
                                                            )
                                                        ];
                                                    })
                                                    ->toArray();
                                            })
                                            ->columns(1)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columnSpanFull()
                                            ->live()
                                            ->helperText('Gunakan kotak pencarian untuk menemukan CPMK. Contoh: "CPMK-01", "algoritma", "pemahaman"'),

                                        Forms\Components\Placeholder::make('cpmk_info')
                                            ->label('Informasi')
                                            ->content('Pilih mata kuliah terlebih dahulu untuk melihat CPMK yang tersedia. CPMK yang dipilih akan otomatis muncul di PDF RPS.')
                                            ->columnSpanFull()
                                            ->hidden(fn (Forms\Get $get) => !empty($get('course_id'))),
                                    ]),

                                Forms\Components\Section::make('📊 Matrix Kontribusi CPMK → CPL')
                                    ->description('Menunjukkan kontribusi setiap CPMK terhadap CPL (read-only)')
                                    ->schema([
                                        Forms\Components\Placeholder::make('cpmk_cpl_matrix')
                                            ->label('')
                                            ->content(function (Forms\Get $get) {
                                                $courseId = $get('course_id');
                                                if (!$courseId) {
                                                    return 'Pilih mata kuliah terlebih dahulu untuk melihat matrix kontribusi.';
                                                }

                                                $cpmks = \App\Models\CourseLearningOutcome::where('course_id', $courseId)
                                                    ->with(['programLearningOutcomes' => function($q) {
                                                        $q->orderBy('code');
                                                    }])
                                                    ->orderBy('code')
                                                    ->get();

                                                if ($cpmks->isEmpty()) {
                                                    return 'Tidak ada CPMK untuk mata kuliah ini.';
                                                }

                                                // Build HTML table
                                                $html = '<div class="overflow-x-auto"><table class="w-full text-sm border-collapse border border-gray-300">';

                                                // Get unique CPLs
                                                $cpls = collect();
                                                foreach ($cpmks as $cpmk) {
                                                    $cpls = $cpls->merge($cpmk->programLearningOutcomes);
                                                }
                                                $cpls = $cpls->unique('id')->sortBy('code');

                                                // Header
                                                $html .= '<thead><tr><th class="border border-gray-300 px-3 py-2 bg-blue-100 text-left font-semibold">CPMK</th>';
                                                foreach ($cpls as $cpl) {
                                                    $html .= '<th class="border border-gray-300 px-3 py-2 bg-blue-100 text-center font-semibold">' . htmlspecialchars($cpl->code) . '</th>';
                                                }
                                                $html .= '</tr></thead>';

                                                // Body
                                                $html .= '<tbody>';
                                                foreach ($cpmks as $cpmk) {
                                                    $html .= '<tr><td class="border border-gray-300 px-3 py-2 font-medium bg-blue-50">' . htmlspecialchars($cpmk->code) . '</td>';

                                                    foreach ($cpls as $cpl) {
                                                        $pivot = $cpmk->programLearningOutcomes->firstWhere('id', $cpl->id);
                                                        if ($pivot) {
                                                            $level = $pivot->pivot->contribution_level_numeric ?? 0;
                                                            $weight = $pivot->pivot->weight_percentage ?? 0;
                                                            $bgColor = $level >= 4 ? 'bg-green-100' : ($level >= 3 ? 'bg-yellow-100' : 'bg-gray-100');
                                                            $textColor = $level >= 4 ? 'text-green-800 font-bold' : ($level >= 3 ? 'text-yellow-800' : 'text-gray-600');
                                                            $html .= '<td class="border border-gray-300 px-3 py-2 text-center ' . $bgColor . ' ' . $textColor . '"><div>Level: ' . $level . '</div><div class="text-xs">' . number_format($weight, 0) . '%</div></td>';
                                                        } else {
                                                            $html .= '<td class="border border-gray-300 px-3 py-2 text-center bg-gray-50 text-gray-400">-</td>';
                                                        }
                                                    }
                                                    $html .= '</tr>';
                                                }
                                                $html .= '</tbody></table></div>';
                                                $html .= '<p class="mt-3 text-xs text-gray-600"><strong>Keterangan:</strong> Warna hijau (Level 4-5) = Kontribusi tinggi, Warna kuning (Level 3) = Kontribusi sedang, Warna abu (Level 0-2) = Kontribusi rendah</p>';

                                                return new \Illuminate\Support\HtmlString($html);
                                            })
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(),

                                Forms\Components\Section::make('Pemetaan CPL (Capaian Pembelajaran Lulusan)')
                                    ->description('CPL difilter berdasarkan Profil Lulusan yang dipilih')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('plo_mapped')
                                            ->label('CPL Terpetakan')
                                            ->options(function (Forms\Get $get) {
                                                $graduateProfileId = $get('graduate_profile_id');
                                                if (!$graduateProfileId) {
                                                    return [];
                                                }
                                                return \App\Models\ProgramLearningOutcome::where('graduate_profile_id', $graduateProfileId)
                                                    ->where('is_active', true)
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($plo) {
                                                        return [$plo->code => "{$plo->code}: {$plo->competency_description}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->descriptions(function (Forms\Get $get) {
                                                $graduateProfileId = $get('graduate_profile_id');
                                                if (!$graduateProfileId) {
                                                    return [];
                                                }
                                                return \App\Models\ProgramLearningOutcome::where('graduate_profile_id', $graduateProfileId)
                                                    ->where('is_active', true)
                                                    ->with('indicators')
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($plo) {
                                                        $indicatorCount = $plo->indicators->count();
                                                        $targetLabel = $plo->target_percentage ? "Target: {$plo->target_percentage}%" : "Target: -";
                                                        $coreLabel = $plo->is_core ? '⭐ Kompetensi Inti' : '📌 Kompetensi Pendukung';
                                                        return [$plo->code => "{$coreLabel} | {$indicatorCount} Indikator | {$targetLabel}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->columns(2)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columnSpanFull()
                                            ->helperText('Pilih Profil Lulusan terlebih dahulu untuk menampilkan CPL yang sesuai'),
                                    ]),

                                Forms\Components\Section::make('Bahan Kajian')
                                    ->description('Pilih bahan kajian yang relevan dengan mata kuliah')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('study_field_mapped')
                                            ->label('Bahan Kajian')
                                            ->options(function () {
                                                return \App\Models\StudyField::orderBy('name')
                                                    ->get()
                                                    ->mapWithKeys(function ($field) {
                                                        return [$field->code => "{$field->code}: {$field->name}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->descriptions(function () {
                                                return \App\Models\StudyField::orderBy('name')
                                                    ->get()
                                                    ->mapWithKeys(function ($field) {
                                                        return [$field->code => substr($field->description ?? 'Tidak ada deskripsi', 0, 100)];
                                                    })
                                                    ->toArray();
                                            })
                                            ->columns(2)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 3: Rencana Pembelajaran Mingguan
                        Forms\Components\Tabs\Tab::make('📅 Rencana Mingguan')
                            ->schema([
                                Forms\Components\Section::make('Rencana Pembelajaran 16 Minggu')
                                    ->description('Kelola rencana pembelajaran mingguan melalui tab "Pemetaan Mingguan" di bawah')
                                    ->schema([
                                        Forms\Components\Placeholder::make('weekly_plan_info')
                                            ->label('📋 Informasi Rencana Mingguan')
                                            ->content(new \Illuminate\Support\HtmlString(
                                                '<div class="space-y-3">' .
                                                '<p><strong>✅ Cara menggunakan:</strong></p>' .
                                                '<ol class="list-decimal list-inside space-y-1 ml-2">' .
                                                '<li>Simpan RPS terlebih dahulu di tab "Identitas RPS"</li>' .
                                                '<li>Pilih "Pemetaan Mingguan" dari menu relasi yang muncul di bawah form</li>' .
                                                '<li>Klik "Buat" untuk menambahkan minggu pembelajaran baru</li>' .
                                                '<li>Isi CPL, CPMK, Sub-CPMK, dan indikator penilaian untuk setiap minggu</li>' .
                                                '<li>Pastikan total bobot semua komponen = 100% per minggu</li>' .
                                                '<li>Gunakan tombol "Generate Semua Minggu" untuk auto-generate 16 minggu dengan pola default</li>' .
                                                '</ol>' .
                                                '</div>' .
                                                '<div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">' .
                                                '<p class="text-sm text-blue-800"><strong>💡 Tips:</strong> Minggu 8 biasanya untuk UTS review, minggu 16 untuk UAS review. Gunakan pola pemboboran yang konsisten.</p>' .
                                                '</div>'
                                            ))
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 4: Sistem Penilaian
                        Forms\Components\Tabs\Tab::make('📊 Penilaian')
                            ->schema([
                                Forms\Components\Section::make('Komponen Penilaian')
                                    ->description('Total bobot harus 100%')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_plan')
                                            ->label('Komponen Penilaian')
                                            ->schema([
                                                Forms\Components\TextInput::make('component')
                                                    ->label('Komponen')
                                                    ->placeholder('Contoh: Kehadiran, Tugas, UTS, UAS')
                                                    ->required()
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('weight')
                                                    ->label('Bobot (%)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->suffix('%')
                                                    ->required()
                                                    ->columnSpan(1),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Deskripsi')
                                                    ->placeholder('Penjelasan komponen penilaian')
                                                    ->rows(2)
                                                    ->columnSpan(3),
                                            ])
                                            ->columns(6)
                                            ->defaultItems(5)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['component'] ?? 'Komponen Baru')
                                            ->addActionLabel('Tambah Komponen')
                                            ->columnSpanFull(),

                                        Forms\Components\Placeholder::make('total_weight_info')
                                            ->label('Petunjuk')
                                            ->content('Pastikan total bobot = 100%. Contoh: Kehadiran 10%, Tugas 20%, Quiz 10%, UTS 30%, UAS 30%')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Konversi Nilai')
                                    ->schema([
                                        Forms\Components\Textarea::make('grading_system')
                                            ->label('Sistem Konversi Nilai')
                                            ->placeholder('A: 85-100, AB: 80-84, B: 75-79, BC: 70-74, C: 65-69, D: 55-64, E: 0-54')
                                            ->default('A: 85-100, AB: 80-84, B: 75-79, BC: 70-74, C: 65-69, D: 55-64, E: 0-54')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 5: Referensi & Media
                        Forms\Components\Tabs\Tab::make('📚 Referensi & Media')
                            ->schema([
                                Forms\Components\Section::make('Pustaka Utama')
                                    ->schema([
                                        Forms\Components\Repeater::make('main_references')
                                            ->label('Referensi Utama')
                                            ->simple(
                                                Forms\Components\Textarea::make('reference')
                                                    ->label('Referensi')
                                                    ->placeholder('Format: Penulis. (Tahun). Judul Buku. Penerbit.')
                                                    ->rows(2)
                                                    ->required()
                                            )
                                            ->defaultItems(3)
                                            ->addActionLabel('Tambah Referensi Utama')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Pustaka Pendukung')
                                    ->schema([
                                        Forms\Components\Repeater::make('supporting_references')
                                            ->label('Referensi Pendukung')
                                            ->simple(
                                                Forms\Components\Textarea::make('reference')
                                                    ->label('Referensi')
                                                    ->placeholder('Format: Penulis. (Tahun). Judul Artikel/Jurnal.')
                                                    ->rows(2)
                                            )
                                            ->defaultItems(2)
                                            ->addActionLabel('Tambah Referensi Pendukung')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Media & Software Pembelajaran')
                                    ->schema([
                                        Forms\Components\Textarea::make('learning_media')
                                            ->label('Media Pembelajaran')
                                            ->placeholder('Contoh: Proyektor, Whiteboard, Video, PPT, Modul')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('learning_software')
                                            ->label('Software/Tools')
                                            ->placeholder('Contoh: Visual Studio Code, Python, Google Colab, GitHub')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 6: Status & Approval Workflow
                        Forms\Components\Tabs\Tab::make('✅ Workflow & Persetujuan')
                            ->schema([
                                Forms\Components\Section::make('Status RPS & Workflow')
                                    ->description('Kelola status dan workflow persetujuan RPS')
                                    ->schema([
                                        Forms\Components\Placeholder::make('approval_status_display')
                                            ->label('Status Terkini')
                                            ->content(function (Forms\Get $get) {
                                                $status = $get('status') ?? 'Draft';

                                                $statusColors = [
                                                    'Draft' => 'gray',
                                                    'Submitted' => 'blue',
                                                    'Reviewed' => 'yellow',
                                                    'Approved' => 'green',
                                                    'Rejected' => 'red',
                                                    'Revision' => 'orange',
                                                ];

                                                $statusIcons = [
                                                    'Draft' => '📝',
                                                    'Submitted' => '📤',
                                                    'Reviewed' => '👁️',
                                                    'Approved' => '✅',
                                                    'Rejected' => '❌',
                                                    'Revision' => '🔄',
                                                ];

                                                $color = $statusColors[$status] ?? 'gray';
                                                $icon = $statusIcons[$status] ?? '📄';

                                                return new \Illuminate\Support\HtmlString(
                                                    '<div class="flex items-center gap-3">' .
                                                    '<div class="text-3xl">' . $icon . '</div>' .
                                                    '<div class="flex-1">' .
                                                    '<p class="text-sm text-gray-600">Status Saat Ini</p>' .
                                                    '<p class="text-xl font-bold text-' . $color . '-700">' . ucfirst($status) . '</p>' .
                                                    '</div>' .
                                                    '</div>'
                                                );
                                            })
                                            ->columnSpanFull(),

                                        Forms\Components\Select::make('status')
                                            ->label('Ubah Status')
                                            ->options([
                                                'Draft' => '📝 Draft - Sedang dikerjakan',
                                                'Submitted' => '📤 Submitted - Siap untuk review',
                                                'Reviewed' => '👁️ Reviewed - Sedang dalam review',
                                                'Approved' => '✅ Approved - Disetujui',
                                                'Rejected' => '❌ Rejected - Ditolak',
                                                'Revision' => '🔄 Revision - Perlu revisi',
                                            ])
                                            ->default('Draft')
                                            ->required()
                                            ->columnSpan(2),

                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktif / Non-aktif')
                                            ->default(true)
                                            ->helperText('Aktif = RPS dapat digunakan dalam pembelajaran')
                                            ->columnSpan(1),
                                    ])->columns(3),

                                Forms\Components\Section::make('Review Dosen Pengampu / Koordinator')
                                    ->description('Dosen pengampu melakukan review terhadap RPS')
                                    ->schema([
                                        Forms\Components\Select::make('reviewed_by')
                                            ->label('Direview oleh')
                                            ->relationship('reviewer', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(2),

                                        Forms\Components\DateTimePicker::make('reviewed_at')
                                            ->label('Tanggal Review')
                                            ->columnSpan(1),

                                        Forms\Components\Textarea::make('review_notes')
                                            ->label('Catatan Review')
                                            ->placeholder('Masukkan komentar atau saran untuk perbaikan...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(3)->collapsible(),

                                Forms\Components\Section::make('Persetujuan Kaprodi')
                                    ->description('Ketua Program Studi memberikan persetujuan final')
                                    ->schema([
                                        Forms\Components\TextInput::make('kaprodi_approver_name')
                                            ->label('Disetujui oleh Kaprodi')
                                            ->placeholder('Nama Ketua Program Studi')
                                            ->helperText('Masukkan nama Ketua Program Studi yang memberikan persetujuan')
                                            ->columnSpan(2),

                                        Forms\Components\DateTimePicker::make('kaprodi_approved_at')
                                            ->label('Tanggal Persetujuan Kaprodi')
                                            ->columnSpan(1),

                                        Forms\Components\Textarea::make('kaprodi_approval_notes')
                                            ->label('Catatan Persetujuan Kaprodi')
                                            ->placeholder('Catatan dari Kaprodi...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(3)->collapsible(),

                                Forms\Components\Section::make('Persetujuan Dekan')
                                    ->description('Dekan memberikan persetujuan tertinggi')
                                    ->schema([
                                        Forms\Components\TextInput::make('dean_approver_name')
                                            ->label('Disetujui oleh Dekan')
                                            ->placeholder('Nama Dekan')
                                            ->helperText('Masukkan nama Dekan yang memberikan persetujuan final')
                                            ->columnSpan(2),

                                        Forms\Components\DateTimePicker::make('dean_approved_at')
                                            ->label('Tanggal Persetujuan Dekan')
                                            ->columnSpan(1),

                                        Forms\Components\Textarea::make('dean_approval_notes')
                                            ->label('Catatan Persetujuan Dekan')
                                            ->placeholder('Catatan dari Dekan...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(3)->collapsible(),

                                Forms\Components\Section::make('Timeline Persetujuan')
                                    ->description('Ringkasan timeline persetujuan RPS')
                                    ->schema([
                                        Forms\Components\Placeholder::make('approval_timeline')
                                            ->label('')
                                            ->content(function (Forms\Get $get) {
                                                $steps = [
                                                    ['status' => 'Draft', 'icon' => '📝', 'label' => 'RPS Dibuat', 'date' => null],
                                                    ['status' => 'Reviewed', 'icon' => '👁️', 'label' => 'Review Koordinator/Pengampu', 'date' => $get('reviewed_at')],
                                                    ['status' => 'Reviewed', 'icon' => '✔️', 'label' => 'Persetujuan Kaprodi', 'date' => $get('kaprodi_approved_at')],
                                                    ['status' => 'Approved', 'icon' => '✅', 'label' => 'Persetujuan Dekan', 'date' => $get('dean_approved_at')],
                                                ];

                                                $html = '<div class="space-y-3">';
                                                $html .= '<div class="relative">';

                                                foreach ($steps as $index => $step) {
                                                    $hasDate = !empty($step['date']);
                                                    $dateStr = $hasDate ? (new \DateTime($step['date']))->format('d M Y H:i') : 'Menunggu...';
                                                    $statusClass = $hasDate ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600';

                                                    $html .= '<div class="flex gap-4 mb-4">';
                                                    $html .= '<div class="flex flex-col items-center">';
                                                    $html .= '<div class="w-10 h-10 rounded-full ' . $statusClass . ' flex items-center justify-center text-lg font-bold">' . $step['icon'] . '</div>';
                                                    if ($index < count($steps) - 1) {
                                                        $html .= '<div class="w-1 h-12 bg-gray-300 my-2"></div>';
                                                    }
                                                    $html .= '</div>';
                                                    $html .= '<div class="pt-2">';
                                                    $html .= '<p class="font-semibold text-gray-900">' . $step['label'] . '</p>';
                                                    $html .= '<p class="text-sm text-gray-600">' . $dateStr . '</p>';
                                                    $html .= '</div>';
                                                    $html .= '</div>';
                                                }

                                                $html .= '</div>';
                                                $html .= '</div>';

                                                return new \Illuminate\Support\HtmlString($html);
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('course.code')
                    ->label('Kode MK')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('course.name')
                    ->label('Mata Kuliah')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('lecturer.name')
                    ->label('Pengembang')
                    ->sortable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('coordinator.name')
                    ->label('Koordinator RPS')
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('headOfProgram.name')
                    ->label('Ketua Prodi')
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('semester')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ganjil' => 'success',
                        'Genap' => 'info',
                        'Pendek' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('class_code')
                    ->label('Kelas')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Submitted' => 'warning',
                        'Reviewed' => 'info',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('document_file')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('syllabus_file')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contract_file')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('reviewed_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('previewPdf')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Rps $record): string => route('rps.preview-pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Rps $record): string => route('rps.download-pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRps::route('/'),
            'create' => Pages\CreateRps::route('/create'),
            'view' => Pages\ViewRps::route('/{record}'),
            'edit' => Pages\EditRps::route('/{record}/edit'),
        ];
    }
}
