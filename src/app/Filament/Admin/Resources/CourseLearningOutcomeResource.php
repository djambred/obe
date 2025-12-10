<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseLearningOutcomeResource\Pages;
use App\Filament\Admin\Resources\CourseLearningOutcomeResource\RelationManagers;
use App\Models\CourseLearningOutcome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseLearningOutcomeResource extends Resource
{
    protected static ?string $model = CourseLearningOutcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Capaian Pembelajaran';

    protected static ?int $navigationSort = 42;

    protected static ?string $navigationLabel = 'CPMK';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih mata kuliah untuk CPMK ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode CPMK')
                    ->placeholder('Contoh: CPMK01, CPMK-01')
                    ->helperText('Kode unik Capaian Pembelajaran Mata Kuliah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Rumusan CPMK')
                    ->placeholder('Tulis rumusan lengkap CPMK (Mahasiswa mampu...)...')
                    ->helperText('Rumusan lengkap CPMK yang ingin dicapai')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('bloom_cognitive_level')
                    ->label('Level Kognitif (C)')
                    ->options([
                        'C1' => 'C1 - Mengingat',
                        'C2' => 'C2 - Memahami',
                        'C3' => 'C3 - Menerapkan',
                        'C4' => 'C4 - Menganalisis',
                        'C5' => 'C5 - Mengevaluasi',
                        'C6' => 'C6 - Mencipta'
                    ])
                    ->helperText('Level kognitif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_affective_level')
                    ->label('Level Afektif (A)')
                    ->options([
                        'A1' => 'A1 - Menerima',
                        'A2' => 'A2 - Menanggapi',
                        'A3' => 'A3 - Menilai',
                        'A4' => 'A4 - Mengelola',
                        'A5' => 'A5 - Menghayati'
                    ])
                    ->helperText('Level afektif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_psychomotor_level')
                    ->label('Level Psikomotorik (P)')
                    ->options([
                        'P1' => 'P1 - Imitasi',
                        'P2' => 'P2 - Manipulasi',
                        'P3' => 'P3 - Presisi',
                        'P4' => 'P4 - Artikulasi',
                        'P5' => 'P5 - Naturalisasi'
                    ])
                    ->helperText('Level psikomotorik Taksonomi Bloom'),
                Forms\Components\TextInput::make('weight')
                    ->label('Bobot (%)')
                    ->helperText('Bobot CPMK dalam penilaian (0-100%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(0),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan CPMK')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('CPMK yang masih digunakan')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bloom_cognitive_level'),
                Tables\Columns\TextColumn::make('bloom_affective_level'),
                Tables\Columns\TextColumn::make('bloom_psychomotor_level'),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot (%)')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListCourseLearningOutcomes::route('/'),
            'create' => Pages\CreateCourseLearningOutcome::route('/create'),
            'view' => Pages\ViewCourseLearningOutcome::route('/{record}'),
            'edit' => Pages\EditCourseLearningOutcome::route('/{record}/edit'),
        ];
    }
}
