<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PerformanceIndicatorResource\Pages;
use App\Filament\Admin\Resources\PerformanceIndicatorResource\RelationManagers;
use App\Models\PerformanceIndicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerformanceIndicatorResource extends Resource
{
    protected static ?string $model = PerformanceIndicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Capaian Pembelajaran';

    protected static ?int $navigationSort = 44;

    protected static ?string $navigationLabel = 'Indikator Kinerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->description('Data identitas indikator kinerja')
                    ->schema([
                        Forms\Components\Select::make('course_learning_outcome_id')
                            ->label('CPMK')
                            ->relationship('courseLearningOutcome', 'code')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code}: {$record->description}")
                            ->helperText('Pilih CPMK yang akan diukur')
                            ->required()
                            ->live()
                            ->columnSpan(1),
                        Forms\Components\Select::make('sub_course_learning_outcome_id')
                            ->label('Sub-CPMK (Opsional)')
                            ->relationship('subCourseLearningOutcome', 'code')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code}: {$record->description}")
                            ->helperText('Pilih Sub-CPMK jika indikator terkait Sub-CPMK spesifik')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Indikator')
                            ->placeholder('Contoh: IK-01, IK-02')
                            ->helperText('Kode unik untuk identifikasi indikator')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->helperText('Urutan tampilan indikator (angka kecil muncul lebih dulu)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi & Kriteria')
                    ->description('Penjelasan lengkap tentang apa yang diukur')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Indikator')
                            ->placeholder('Contoh: Mahasiswa mampu mengidentifikasi dan menjelaskan konsep algoritma dengan tepat')
                            ->helperText('Jelaskan apa yang akan diukur dari indikator ini')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('criteria')
                            ->label('Kriteria Pencapaian')
                            ->placeholder('Contoh: Mampu menjelaskan minimal 3 konsep algoritma, memberikan contoh implementasi, menjelaskan kompleksitas')
                            ->helperText('Kriteria yang harus dipenuhi untuk mencapai indikator ini')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Rubrik Penilaian OBE')
                    ->description('Rubrik penilaian berbasis pencapaian (4 tingkat standar OBE)')
                    ->schema([
                        Forms\Components\Textarea::make('rubric')
                            ->label('Rubrik Penilaian')
                            ->placeholder("Format per baris:\n- Sangat Baik (86-100): Deskripsi kriteria...\n- Baik (71-85): Deskripsi kriteria...\n- Cukup (56-70): Deskripsi kriteria...\n- Kurang (0-55): Deskripsi kriteria...")
                            ->helperText('Tulis kriteria pencapaian untuk setiap tingkat penilaian. Gunakan format: Tingkat (rentang nilai): Deskripsi')
                            ->rows(8)
                            ->default("- Sangat Baik (86-100): Menguasai dan menerapkan semua konsep dengan sangat tepat dan mandiri\n- Baik (71-85): Menguasai sebagian besar konsep dan dapat menerapkan dengan baik\n- Cukup (56-70): Menguasai konsep dasar dan dapat menerapkan dengan bantuan\n- Kurang (0-55): Belum menguasai konsep dasar yang diperlukan")
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Penilaian & Bobot')
                    ->description('Pengaturan metode penilaian dan bobot')
                    ->schema([
                        Forms\Components\Select::make('assessment_type')
                            ->label('Jenis Penilaian')
                            ->options([
                                'Tugas' => 'Tugas',
                                'Quiz' => 'Quiz',
                                'UTS' => 'Ujian Tengah Semester',
                                'UAS' => 'Ujian Akhir Semester',
                                'Praktikum' => 'Praktikum',
                                'Proyek' => 'Proyek',
                                'Presentasi' => 'Presentasi',
                                'Portfolio' => 'Portfolio',
                                'Observasi' => 'Observasi',
                            ])
                            ->required()
                            ->helperText('Pilih metode penilaian yang akan digunakan')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('weight')
                            ->label('Bobot (%)')
                            ->helperText('Bobot indikator dalam total penilaian')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(0)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('passing_grade')
                            ->label('Nilai Minimal Kelulusan (%)')
                            ->helperText('Nilai minimal yang harus dicapai mahasiswa')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->default(70)
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Indikator masih digunakan dalam penilaian')
                            ->default(true)
                            ->required()
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Skala Penilaian')
                    ->description('Standar konversi nilai angka ke grade (A, B, C, D, E)')
                    ->schema([
                        Forms\Components\Select::make('grading_scale_level')
                            ->label('Level Skala Nilai')
                            ->options([
                                'Universitas' => 'Universitas',
                                'Fakultas' => 'Fakultas',
                                'Program Studi' => 'Program Studi',
                            ])
                            ->default('Universitas')
                            ->required()
                            ->live()
                            ->helperText('Pilih level standar penilaian yang digunakan')
                            ->columnSpan(1),

                        Forms\Components\Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => in_array($get('grading_scale_level'), ['Fakultas', 'Program Studi']))
                            ->required(fn (Forms\Get $get) => in_array($get('grading_scale_level'), ['Fakultas', 'Program Studi']))
                            ->live()
                            ->columnSpan(1),

                        Forms\Components\Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('grading_scale_level') === 'Program Studi')
                            ->required(fn (Forms\Get $get) => $get('grading_scale_level') === 'Program Studi')
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('grading_scale')
                            ->label('Tabel Konversi Nilai (Grade)')
                            ->placeholder("Format per baris:\nA: 86-100\nB: 71-85\nC: 56-70\nD: 41-55\nE: 0-40")
                            ->helperText('Standar universitas: A (86-100), B (71-85), C (56-70), D (41-55), E (0-40)')
                            ->default("A: 86-100\nB: 71-85\nC: 56-70\nD: 41-55\nE: 0-40")
                            ->rows(6)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('courseLearningOutcome.code')
                    ->label('CPMK')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('subCourseLearningOutcome.code')
                    ->label('Sub-CPMK')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('assessment_type')
                    ->label('Jenis Penilaian')
                    ->badge()
                    ->colors([
                        'primary' => 'Quiz',
                        'success' => 'Tugas',
                        'warning' => 'Praktikum',
                        'danger' => fn ($state) => in_array($state, ['UTS', 'UAS']),
                        'info' => fn ($state) => in_array($state, ['Proyek', 'Portfolio', 'Presentasi']),
                    ]),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot (%)')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('passing_grade')
                    ->label('Nilai Min')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('grading_scale_level')
                    ->label('Skala Nilai')
                    ->badge()
                    ->colors([
                        'primary' => 'Universitas',
                        'success' => 'Fakultas',
                        'warning' => 'Program Studi',
                    ])
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_learning_outcome_id')
                    ->label('Filter by CPMK')
                    ->relationship('courseLearningOutcome', 'code')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('assessment_type')
                    ->label('Filter by Jenis Penilaian')
                    ->options([
                        'Tugas' => 'Tugas',
                        'Quiz' => 'Quiz',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                        'Praktikum' => 'Praktikum',
                        'Proyek' => 'Proyek',
                        'Presentasi' => 'Presentasi',
                        'Portfolio' => 'Portfolio',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListPerformanceIndicators::route('/'),
            'create' => Pages\CreatePerformanceIndicator::route('/create'),
            'view' => Pages\ViewPerformanceIndicator::route('/{record}'),
            'edit' => Pages\EditPerformanceIndicator::route('/{record}/edit'),
        ];
    }
}
