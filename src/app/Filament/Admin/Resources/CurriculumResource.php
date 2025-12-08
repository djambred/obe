<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CurriculumResource\Pages;
use App\Filament\Admin\Resources\CurriculumResource\RelationManagers;
use App\Models\Curriculum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = '📚 Kurikulum';

    protected static ?int $navigationSort = 20;

    protected static ?string $pluralModelLabel = 'Curriculums';

    protected static ?string $modelLabel = 'Curriculum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih program studi untuk kurikulum ini')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kurikulum')
                            ->placeholder('Contoh: K2024')
                            ->helperText('Kode unik untuk kurikulum ini')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kurikulum')
                            ->placeholder('Contoh: Kurikulum 2024')
                            ->helperText('Nama kurikulum')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('academic_year_start')
                            ->label('Tahun Mulai')
                            ->placeholder('2024')
                            ->helperText('Tahun akademik mulai berlaku')
                            ->required()
                            ->numeric()
                            ->minValue(2020)
                            ->maxValue(2050),
                        Forms\Components\TextInput::make('academic_year_end')
                            ->label('Tahun Berakhir')
                            ->placeholder('2028')
                            ->helperText('Tahun akademik berakhir (opsional, kosongkan jika masih berlaku)')
                            ->numeric()
                            ->minValue(2020)
                            ->maxValue(2050),
                        Forms\Components\DatePicker::make('effective_date')
                            ->label('Tanggal Efektif')
                            ->helperText('Tanggal mulai berlaku kurikulum')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Kurikulum yang sedang aktif digunakan')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Struktur SKS')
                    ->description('Distribusi SKS berdasarkan tipe mata kuliah sesuai OBE 2025')
                    ->schema([
                        Forms\Components\TextInput::make('total_credits')
                            ->label('Total SKS')
                            ->helperText('Total SKS yang harus ditempuh untuk lulus')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(144),
                        Forms\Components\TextInput::make('mandatory_university_credits')
                            ->label('SKS Wajib Universitas')
                            ->helperText('Total SKS Mata Kuliah Wajib Universitas (MKWU)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(8),
                        Forms\Components\TextInput::make('mandatory_faculty_credits')
                            ->label('SKS Wajib Fakultas')
                            ->helperText('Total SKS Mata Kuliah Wajib Fakultas')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(12),
                        Forms\Components\TextInput::make('mandatory_program_credits')
                            ->label('SKS Wajib Prodi')
                            ->helperText('Total SKS Mata Kuliah Wajib Program Studi')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(110),
                        Forms\Components\TextInput::make('elective_credits')
                            ->label('SKS Pilihan')
                            ->helperText('Total SKS Mata Kuliah Pilihan')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(8),
                        Forms\Components\TextInput::make('concentration_credits')
                            ->label('SKS Konsentrasi')
                            ->helperText('Total SKS Mata Kuliah Konsentrasi/Peminatan')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(6),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Kurikulum')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tulis deskripsi singkat tentang kurikulum ini...')
                            ->helperText('Deskripsi singkat dan tujuan kurikulum')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('structure')
                            ->label('Struktur Kurikulum')
                            ->placeholder('Format JSON: struktur mata kuliah per semester')
                            ->helperText('Struktur lengkap kurikulum dalam format JSON (per semester)')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('concentration_list')
                            ->label('Daftar Konsentrasi')
                            ->placeholder('Format JSON: ["Konsentrasi 1", "Konsentrasi 2"]')
                            ->helperText('Daftar konsentrasi/peminatan yang tersedia (JSON array)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('document_file')
                            ->label('Dokumen Kurikulum')
                            ->helperText('Upload dokumen kurikulum resmi (PDF, max 10MB)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('minio')
                            ->directory('curriculum/documents')
                            ->maxSize(10240),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Kode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Kurikulum')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')->label('Program Studi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('academic_year_start')->label('Tahun Mulai')->sortable(),
                Tables\Columns\TextColumn::make('academic_year_end')->label('Tahun Berakhir')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('total_credits')->label('Total SKS')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCurriculums::route('/'),
            'create' => Pages\CreateCurriculum::route('/create'),
            'view' => Pages\ViewCurriculum::route('/{record}'),
            'edit' => Pages\EditCurriculum::route('/{record}/edit'),
        ];
    }
}
