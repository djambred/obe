<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContinuousImprovementResource\Pages;
use App\Filament\Admin\Resources\ContinuousImprovementResource\RelationManagers;
use App\Models\ContinuousImprovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContinuousImprovementResource extends Resource
{
    protected static ?string $model = ContinuousImprovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = '📊 Assessment & Evaluasi';

    protected static ?int $navigationSort = 51;

    protected static ?string $navigationLabel = 'Continuous Improvement';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program studi yang melakukan perbaikan berkelanjutan')
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->helperText('Pilih mata kuliah terkait (kosongkan jika perbaikan di level program studi)')
                    ->default(null),
                Forms\Components\TextInput::make('academic_year')
                    ->label('Tahun Akademik')
                    ->placeholder('Contoh: 2024/2025')
                    ->helperText('Tahun ajaran pelaksanaan perbaikan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                        'Pendek' => 'Pendek',
                        'Tahunan' => 'Tahunan',
                    ])
                    ->helperText('Semester pelaksanaan')
                    ->required(),
                Forms\Components\Select::make('improvement_area')
                    ->label('Area Perbaikan')
                    ->options([
                        'Kurikulum' => 'Kurikulum',
                        'Pembelajaran' => 'Pembelajaran',
                        'Penilaian' => 'Penilaian',
                        'Sarana Prasarana' => 'Sarana Prasarana',
                        'SDM Dosen' => 'SDM Dosen',
                        'Layanan Mahasiswa' => 'Layanan Mahasiswa',
                        'Research & Publication' => 'Research & Publication',
                        'Kerjasama' => 'Kerjasama',
                        'Tata Kelola' => 'Tata Kelola',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->helperText('Pilih area/aspek yang akan diperbaiki')
                    ->required(),
                Forms\Components\Textarea::make('issue_identified')
                    ->label('Isu/Masalah yang Teridentifikasi')
                    ->placeholder('Tulis masalah atau gap yang ditemukan...')
                    ->helperText('Deskripsi masalah atau kesenjangan yang perlu diperbaiki')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('root_cause')
                    ->label('Akar Penyebab (Root Cause)')
                    ->placeholder('Tulis analisis akar penyebab masalah...')
                    ->helperText('Analisis akar penyebab menggunakan metode seperti 5 Why, Fishbone, dll')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('improvement_plan')
                    ->label('Rencana Perbaikan')
                    ->placeholder('Tulis rencana perbaikan secara detail...')
                    ->helperText('Rencana perbaikan yang akan dilakukan (strategi, metode, resources)')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('action_items')
                    ->label('Item Tindakan')
                    ->placeholder('1. Tindakan pertama\n2. Tindakan kedua\n3. dst...')
                    ->helperText('Daftar action items atau task yang harus dikerjakan')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('planned_start_date')
                    ->label('Tanggal Mulai (Rencana)')
                    ->helperText('Tanggal rencana mulai pelaksanaan')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('planned_end_date')
                    ->label('Tanggal Selesai (Rencana)')
                    ->helperText('Tanggal target selesai')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('actual_start_date')
                    ->label('Tanggal Mulai (Aktual)')
                    ->helperText('Tanggal aktual pelaksanaan dimulai')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\DatePicker::make('actual_end_date')
                    ->label('Tanggal Selesai (Aktual)')
                    ->helperText('Tanggal aktual selesai pelaksanaan')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('pic_user_id')
                    ->label('Penanggung Jawab (PIC)')
                    ->relationship('pic', 'name')
                    ->searchable()
                    ->helperText('Pilih user sebagai Person In Charge')
                    ->default(null),
                Forms\Components\Textarea::make('stakeholders')
                    ->label('Stakeholder Terlibat')
                    ->placeholder('Contoh: Ketua Prodi, Dosen Pengampu, Lab Manager, Mahasiswa, dll')
                    ->helperText('Daftar pihak-pihak yang terlibat dalam perbaikan')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Planned' => '📋 Planned - Dalam Perencanaan',
                        'In Progress' => '🔄 In Progress - Sedang Berjalan',
                        'On Hold' => '⏸️ On Hold - Ditunda Sementara',
                        'Completed' => '✅ Completed - Selesai',
                        'Cancelled' => '❌ Cancelled - Dibatalkan',
                    ])
                    ->helperText('Status pelaksanaan perbaikan')
                    ->required(),
                Forms\Components\TextInput::make('progress_percentage')
                    ->label('Persentase Progress')
                    ->placeholder('0')
                    ->helperText('Progress pelaksanaan dalam persen (0-100%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(0),
                Forms\Components\Textarea::make('implementation_notes')
                    ->label('Catatan Pelaksanaan')
                    ->placeholder('Tulis catatan atau kendala selama pelaksanaan...')
                    ->helperText('Catatan, kendala, atau pembelajaran selama implementasi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('results')
                    ->label('Hasil Perbaikan')
                    ->placeholder('Tulis hasil yang dicapai dari perbaikan...')
                    ->helperText('Hasil atau outcome yang dicapai setelah perbaikan dilaksanakan')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_effective')
                    ->label('Perbaikan Efektif')
                    ->helperText('Centang jika perbaikan terbukti efektif berdasarkan evaluasi')
                    ->inline(false),
                Forms\Components\Textarea::make('effectiveness_evidence')
                    ->label('Bukti Efektivitas')
                    ->placeholder('Tulis bukti atau indikator efektivitas perbaikan...')
                    ->helperText('Data, indikator, atau bukti yang menunjukkan efektivitas perbaikan')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('evidence_files')
                    ->label('File Bukti')
                    ->helperText('Upload dokumen pendukung (laporan, foto, data, dll)')
                    ->multiple()
                    ->directory('continuous-improvements/evidence')
                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(10240)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('improvement_area'),
                Tables\Columns\TextColumn::make('planned_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('planned_end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pic_user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_effective')
                    ->boolean(),
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
            'index' => Pages\ListContinuousImprovements::route('/'),
            'create' => Pages\CreateContinuousImprovement::route('/create'),
            'view' => Pages\ViewContinuousImprovement::route('/{record}'),
            'edit' => Pages\EditContinuousImprovement::route('/{record}/edit'),
        ];
    }
}
