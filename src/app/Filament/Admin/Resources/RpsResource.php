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

    protected static ?string $navigationGroup = '📝 RPS';

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
                                                        return [$clo->code => "{$clo->code}: {$clo->description}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->descriptions(function (Forms\Get $get) {
                                                $courseId = $get('course_id');
                                                if (!$courseId) {
                                                    return [];
                                                }
                                                return \App\Models\CourseLearningOutcome::where('course_id', $courseId)
                                                    ->orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($clo) {
                                                        return [$clo->code => "Bobot: {$clo->weight}% | Bloom Level: {$clo->bloom_level}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->columns(1)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columnSpanFull()
                                            ->live(),

                                        Forms\Components\Placeholder::make('cpmk_info')
                                            ->label('Informasi')
                                            ->content('Pilih mata kuliah terlebih dahulu untuk melihat CPMK yang tersedia. CPMK yang dipilih akan otomatis muncul di PDF RPS.')
                                            ->columnSpanFull()
                                            ->hidden(fn (Forms\Get $get) => !empty($get('course_id'))),
                                    ]),

                                Forms\Components\Section::make('Pemetaan CPL (Capaian Pembelajaran Lulusan)')
                                    ->description('Pilih CPL yang dipetakan dari mata kuliah ini')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('plo_mapped')
                                            ->label('CPL Terpetakan')
                                            ->options(function () {
                                                return \App\Models\ProgramLearningOutcome::orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($plo) {
                                                        return [$plo->code => "{$plo->code}: {$plo->description}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->descriptions(function () {
                                                return \App\Models\ProgramLearningOutcome::orderBy('code')
                                                    ->get()
                                                    ->mapWithKeys(function ($plo) {
                                                        $category = match($plo->category) {
                                                            'Sikap' => '👤 Sikap',
                                                            'Pengetahuan' => '📚 Pengetahuan',
                                                            'Keterampilan Umum' => '🔧 Keterampilan Umum',
                                                            'Keterampilan Khusus' => '💻 Keterampilan Khusus',
                                                            default => $plo->category
                                                        };
                                                        return [$plo->code => "{$category} | KKNI Level: {$plo->kkni_level}"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->columns(2)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columnSpanFull(),
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
                                    ->description('Isi rencana pembelajaran untuk setiap minggu (minggu 8 = UTS, minggu 17 = UAS)')
                                    ->schema([
                                        Forms\Components\Repeater::make('weekly_plan')
                                            ->label('Pertemuan Mingguan')
                                            ->schema([
                                                Forms\Components\TextInput::make('week')
                                                    ->label('Minggu Ke-')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(17)
                                                    ->default(fn ($get) => count($get('../../weekly_plan') ?? []) + 1)
                                                    ->required()
                                                    ->columnSpan(1),
                                                Forms\Components\Select::make('sub_cpmk')
                                                    ->label('Sub-CPMK')
                                                    ->multiple()
                                                    ->options(function (Forms\Get $get) {
                                                        $courseId = $get('../../course_id');
                                                        if (!$courseId) {
                                                            return [];
                                                        }
                                                        return \App\Models\SubCourseLearningOutcome::whereHas('courseLearningOutcome', function ($query) use ($courseId) {
                                                            $query->where('course_id', $courseId);
                                                        })
                                                        ->orderBy('code')
                                                        ->get()
                                                        ->mapWithKeys(function ($subClo) {
                                                            return [$subClo->code => "{$subClo->code}: " . substr($subClo->description, 0, 60) . "..."];
                                                        })
                                                        ->toArray();
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->columnSpan(2),
                                                Forms\Components\Select::make('indicators')
                                                    ->label('Indikator Penilaian')
                                                    ->multiple()
                                                    ->options(function (Forms\Get $get) {
                                                        $subCpmkCodes = $get('sub_cpmk');
                                                        if (!$subCpmkCodes || !is_array($subCpmkCodes)) {
                                                            return [];
                                                        }
                                                        $subCpmkIds = \App\Models\SubCourseLearningOutcome::whereIn('code', $subCpmkCodes)->pluck('id');
                                                        return \App\Models\PerformanceIndicator::whereIn('sub_course_learning_outcome_id', $subCpmkIds)
                                                            ->orderBy('code')
                                                            ->get()
                                                            ->mapWithKeys(function ($indicator) {
                                                                return [$indicator->code => "{$indicator->code} ({$indicator->weight}%): " . substr($indicator->description, 0, 50)];
                                                            })
                                                            ->toArray();
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->columnSpan(3),
                                                Forms\Components\TagsInput::make('topics')
                                                    ->label('Topik/Materi Pembelajaran')
                                                    ->placeholder('Tekan Enter setelah setiap topik')
                                                    ->separator(',')
                                                    ->required()
                                                    ->columnSpan(3),
                                                Forms\Components\TextInput::make('learning_form')
                                                    ->label('Bentuk Pembelajaran')
                                                    ->placeholder('Contoh: Perkuliahan, Praktikum')
                                                    ->default('Perkuliahan')
                                                    ->columnSpan(2),
                                                Forms\Components\TagsInput::make('methods')
                                                    ->label('Metode Pembelajaran')
                                                    ->placeholder('Contoh: Ceramah, Diskusi, Praktik')
                                                    ->separator(',')
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('student_tasks')
                                                    ->label('Rencana Tugas Mahasiswa')
                                                    ->placeholder('Tugas yang harus dikerjakan mahasiswa')
                                                    ->columnSpan(2),
                                                Forms\Components\TagsInput::make('assessment')
                                                    ->label('Bentuk Penilaian')
                                                    ->placeholder('Contoh: Quiz, Tugas, Presentasi')
                                                    ->separator(',')
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('weight')
                                                    ->label('Bobot (%)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->suffix('%')
                                                    ->default(0)
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('duration')
                                                    ->label('Durasi (menit)')
                                                    ->numeric()
                                                    ->default(150)
                                                    ->suffix('menit')
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(6)
                                            ->defaultItems(16)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['week'] ? "Minggu {$state['week']}" : null)
                                            ->addActionLabel('Tambah Pertemuan')
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

                        // Tab 6: Status & Persetujuan
                        Forms\Components\Tabs\Tab::make('✅ Status & Approval')
                            ->schema([
                                Forms\Components\Section::make('Status Dokumen')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Status RPS')
                                            ->options([
                                                'Draft' => 'Draft',
                                                'Submitted' => 'Submitted',
                                                'Reviewed' => 'Reviewed',
                                                'Approved' => 'Approved',
                                                'Rejected' => 'Rejected',
                                                'Revision' => 'Revision',
                                            ])
                                            ->default('Draft')
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->columnSpan(1),
                                    ])->columns(2),

                                Forms\Components\Section::make('Review')
                                    ->schema([
                                        Forms\Components\Select::make('reviewed_by')
                                            ->label('Direview oleh')
                                            ->relationship('reviewer', 'name')
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\DateTimePicker::make('reviewed_at')
                                            ->label('Tanggal Review'),
                                        Forms\Components\Textarea::make('review_notes')
                                            ->label('Catatan Review')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Approval')
                                    ->schema([
                                        Forms\Components\TextInput::make('approved_by')
                                            ->label('Disetujui oleh')
                                            ->placeholder('Nama Ketua Program Studi'),
                                        Forms\Components\DateTimePicker::make('approved_at')
                                            ->label('Tanggal Persetujuan'),
                                        Forms\Components\Textarea::make('approval_notes')
                                            ->label('Catatan Persetujuan')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
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
