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
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih mata kuliah untuk RPS ini')
                    ->required(),
                Forms\Components\Select::make('lecturer_id')
                    ->label('Koordinator/Dosen Pengampu')
                    ->relationship('lecturer', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Dosen koordinator yang bertanggung jawab atas RPS ini')
                    ->required(),
                Forms\Components\Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship('curriculum', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Kurikulum yang menjadi acuan RPS')
                    ->required(),
                Forms\Components\TextInput::make('academic_year')
                    ->label('Tahun Akademik')
                    ->placeholder('2024/2025')
                    ->helperText('Tahun akademik berlakunya RPS (format: YYYY/YYYY)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                        'Pendek' => 'Pendek'
                    ])
                    ->helperText('Semester pelaksanaan')
                    ->required(),
                Forms\Components\TextInput::make('version')
                    ->label('Versi RPS')
                    ->placeholder('1.0')
                    ->helperText('Nomor versi RPS (format: X.Y)')
                    ->required()
                    ->maxLength(255)
                    ->default('1.0'),
                Forms\Components\TextInput::make('class_code')
                    ->label('Kode Kelas')
                    ->placeholder('A, B, C, D')
                    ->helperText('Kode kelas/paralel (opsional)')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('student_quota')
                    ->label('Kuota Mahasiswa')
                    ->helperText('Jumlah maksimal mahasiswa per kelas')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(40),
                Forms\Components\Textarea::make('course_description')
                    ->label('Deskripsi Mata Kuliah')
                    ->placeholder('Tulis deskripsi lengkap mata kuliah...')
                    ->helperText('Deskripsi lengkap mata kuliah sesuai kurikulum')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('clo_list')
                    ->label('Daftar CPMK')
                    ->placeholder('Format JSON array atau teks daftar CPMK')
                    ->helperText('Daftar Capaian Pembelajaran Mata Kuliah (CPMK) dalam format JSON')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('plo_mapped')
                    ->label('Pemetaan CPL')
                    ->placeholder('Format JSON: mapping CPMK ke CPL')
                    ->helperText('Pemetaan CPMK ke CPL (Capaian Pembelajaran Lulusan)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('study_field_mapped')
                    ->label('Pemetaan Bahan Kajian')
                    ->placeholder('Format JSON: mapping ke bahan kajian')
                    ->helperText('Pemetaan mata kuliah ke bahan kajian/bidang keilmuan')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('weekly_plan')
                    ->label('Rencana Pembelajaran Mingguan')
                    ->placeholder('Format JSON: array rencana 16 minggu (pertemuan 1-16)')
                    ->helperText('Rencana pembelajaran per minggu (16 pertemuan) dalam format JSON')
                    ->rows(5)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('assessment_plan')
                    ->label('Rencana Penilaian')
                    ->placeholder('Format JSON: komponen penilaian dan bobotnya')
                    ->helperText('Rencana sistem penilaian dan bobot per komponen')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('assessment_rubric')
                    ->label('Rubrik Penilaian')
                    ->placeholder('Format JSON: detail rubrik per komponen penilaian')
                    ->helperText('Rubrik detail untuk setiap komponen penilaian')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('grading_system')
                    ->label('Sistem Penilaian')
                    ->placeholder('Format JSON: {"A": "85-100", "B": "70-84", ...}')
                    ->helperText('Sistem konversi nilai angka ke huruf')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('main_references')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('supporting_references')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('learning_media')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('learning_software')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('document_file')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('syllabus_file')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('contract_file')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('reviewed_by')
                    ->numeric()
                    ->default(null),
                Forms\Components\DateTimePicker::make('reviewed_at'),
                Forms\Components\Textarea::make('review_notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                Forms\Components\DateTimePicker::make('approved_at'),
                Forms\Components\Textarea::make('approval_notes')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecturer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('curriculum.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('class_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_quota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('syllabus_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('reviewed_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
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
            'index' => Pages\ListRps::route('/'),
            'create' => Pages\CreateRps::route('/create'),
            'view' => Pages\ViewRps::route('/{record}'),
            'edit' => Pages\EditRps::route('/{record}/edit'),
        ];
    }
}
