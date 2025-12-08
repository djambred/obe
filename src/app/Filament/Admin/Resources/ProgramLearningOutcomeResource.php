<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProgramLearningOutcomeResource\Pages;
use App\Filament\Admin\Resources\ProgramLearningOutcomeResource\RelationManagers;
use App\Models\ProgramLearningOutcome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramLearningOutcomeResource extends Resource
{
    protected static ?string $model = ProgramLearningOutcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = '🎯 Learning Outcomes';

    protected static ?int $navigationSort = 41;

    protected static ?string $navigationLabel = 'CPL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program studi untuk CPL ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode CPL')
                    ->placeholder('Contoh: CPL01, CPL-S-01')
                    ->helperText('Kode unik Capaian Pembelajaran Lulusan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Rumusan CPL')
                    ->placeholder('Tulis rumusan lengkap Capaian Pembelajaran Lulusan...')
                    ->helperText('Rumusan lengkap CPL (contoh: Mampu mengembangkan sistem perangkat lunak...)')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Sikap' => 'Sikap (S)',
                        'Pengetahuan' => 'Pengetahuan (P)',
                        'Keterampilan Umum' => 'Keterampilan Umum (KU)',
                        'Keterampilan Khusus' => 'Keterampilan Khusus (KK)'
                    ])
                    ->helperText('Kategori CPL sesuai SN-Dikti')
                    ->required(),
                Forms\Components\Select::make('bloom_cognitive_level')
                    ->label('Taksonomi Bloom (Kognitif)')
                    ->options([
                        'C1 - Mengingat' => 'C1 - Mengingat',
                        'C2 - Memahami' => 'C2 - Memahami',
                        'C3 - Menerapkan' => 'C3 - Menerapkan',
                        'C4 - Menganalisis' => 'C4 - Menganalisis',
                        'C5 - Mengevaluasi' => 'C5 - Mengevaluasi',
                        'C6 - Mencipta' => 'C6 - Mencipta'
                    ])
                    ->helperText('Level kognitif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_affective_level')
                    ->label('Taksonomi Bloom (Afektif)')
                    ->options([
                        'A1 - Menerima' => 'A1 - Menerima',
                        'A2 - Menanggapi' => 'A2 - Menanggapi',
                        'A3 - Menilai' => 'A3 - Menilai',
                        'A4 - Mengelola' => 'A4 - Mengelola',
                        'A5 - Menghayati' => 'A5 - Menghayati'
                    ])
                    ->helperText('Level afektif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_psychomotor_level')
                    ->label('Taksonomi Bloom (Psikomotorik)')
                    ->options([
                        'P1 - Imitasi' => 'P1 - Imitasi',
                        'P2 - Manipulasi' => 'P2 - Manipulasi',
                        'P3 - Presisi' => 'P3 - Presisi',
                        'P4 - Artikulasi' => 'P4 - Artikulasi',
                        'P5 - Naturalisasi' => 'P5 - Naturalisasi'
                    ])
                    ->helperText('Level psikomotorik Taksonomi Bloom'),
                Forms\Components\Textarea::make('sndikti_reference')
                    ->label('Rujukan SN-Dikti')
                    ->placeholder('Tulis rujukan ke SN-Dikti terkait...')
                    ->helperText('Rujukan ke Standar Nasional Pendidikan Tinggi')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('kkni_level')
                    ->label('Level KKNI')
                    ->placeholder('Contoh: Level 6 - Mampu mengaplikasikan...')
                    ->helperText('Level dan deskripsi KKNI yang relevan (JSON atau teks)')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('industry_reference')
                    ->label('Rujukan Industri')
                    ->placeholder('Format JSON: standar/kompetensi industri yang relevan')
                    ->helperText('Rujukan ke standar industri/profesi (ACM, IEEE, dll)')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan CPL')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('CPL yang masih digunakan dalam kurikulum')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('bloom_cognitive_level'),
                Tables\Columns\TextColumn::make('bloom_affective_level'),
                Tables\Columns\TextColumn::make('bloom_psychomotor_level'),
                Tables\Columns\TextColumn::make('order')
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
            'index' => Pages\ListProgramLearningOutcomes::route('/'),
            'create' => Pages\CreateProgramLearningOutcome::route('/create'),
            'view' => Pages\ViewProgramLearningOutcome::route('/{record}'),
            'edit' => Pages\EditProgramLearningOutcome::route('/{record}/edit'),
        ];
    }
}
