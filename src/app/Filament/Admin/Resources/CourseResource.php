<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseResource\Pages;
use App\Filament\Admin\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = '📚 Kurikulum';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship('curriculum', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih kurikulum yang memuat mata kuliah ini')
                    ->required(),
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program studi yang menyelenggarakan mata kuliah ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode MK')
                    ->placeholder('Contoh: TIF101, EKO202')
                    ->helperText('Kode unik mata kuliah (biasanya kode prodi + nomor urut)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Mata Kuliah')
                    ->placeholder('Contoh: Pemrograman Web')
                    ->helperText('Nama lengkap mata kuliah dalam Bahasa Indonesia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('english_name')
                    ->label('Nama dalam Bahasa Inggris')
                    ->placeholder('Contoh: Web Programming')
                    ->helperText('Nama mata kuliah dalam Bahasa Inggris (opsional)')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('type')
                    ->label('Tipe Mata Kuliah')
                    ->options([
                        'Wajib Universitas' => 'Wajib Universitas (MKWU)',
                        'Wajib Fakultas' => 'Wajib Fakultas',
                        'Wajib Prodi' => 'Wajib Prodi',
                        'Pilihan' => 'Pilihan',
                        'Konsentrasi' => 'Konsentrasi'
                    ])
                    ->helperText('Pilih tipe/kategori mata kuliah sesuai OBE 2025')
                    ->required(),
                Forms\Components\TextInput::make('concentration')
                    ->label('Nama Konsentrasi')
                    ->placeholder('Contoh: Rekayasa Perangkat Lunak')
                    ->helperText('Diisi hanya jika tipe MK adalah Konsentrasi')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('credits')
                    ->label('Total SKS')
                    ->helperText('Total SKS mata kuliah (Teori + Praktik + Lapangan)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8)
                    ->default(3),
                Forms\Components\TextInput::make('theory_credits')
                    ->label('SKS Teori')
                    ->helperText('Jumlah SKS untuk pembelajaran teori')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(2),
                Forms\Components\TextInput::make('practice_credits')
                    ->label('SKS Praktik')
                    ->helperText('Jumlah SKS untuk pembelajaran praktik')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                Forms\Components\TextInput::make('field_credits')
                    ->label('SKS Lapangan')
                    ->helperText('Jumlah SKS untuk pembelajaran lapangan (field work)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\TextInput::make('semester')
                    ->label('Semester')
                    ->helperText('Semester ditawarkan (1-8)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(14)
                    ->default(1),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tulis deskripsi singkat mata kuliah, cakupan materi, dan tujuan pembelajaran...')
                    ->helperText('Deskripsi singkat tentang mata kuliah')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('prerequisites')
                    ->label('Prasyarat (Prerequisites)')
                    ->placeholder('Format JSON: ["TIF101", "TIF102"] atau teks biasa')
                    ->helperText('Mata kuliah yang harus diambil sebelumnya (JSON array kode MK)')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('corequisites')
                    ->label('Ko-Syarat (Corequisites)')
                    ->placeholder('Format JSON: ["TIF201"] atau teks biasa')
                    ->helperText('Mata kuliah yang harus diambil bersamaan (JSON array kode MK)')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('learning_media')
                    ->label('Media Pembelajaran')
                    ->placeholder('Contoh: Proyektor, Laptop, Whiteboard, E-learning')
                    ->helperText('Daftar media pembelajaran yang digunakan')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('learning_methods')
                    ->label('Metode Pembelajaran')
                    ->placeholder('Contoh: Ceramah, Diskusi, Praktikum, Project-Based Learning')
                    ->helperText('Metode pembelajaran yang diterapkan')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('assessment_methods')
                    ->label('Metode Penilaian')
                    ->placeholder('Format JSON: ["UTS 30%", "UAS 40%", "Tugas 20%", "Quiz 10%"]')
                    ->helperText('Metode dan bobot penilaian (JSON array)')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('references')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curriculum.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('english_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('concentration')
                    ->searchable(),
                Tables\Columns\TextColumn::make('credits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('theory_credits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('practice_credits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('field_credits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->numeric()
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
