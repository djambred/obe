<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubCourseLearningOutcomeResource\Pages;
use App\Filament\Admin\Resources\SubCourseLearningOutcomeResource\RelationManagers;
use App\Models\SubCourseLearningOutcome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubCourseLearningOutcomeResource extends Resource
{
    protected static ?string $model = SubCourseLearningOutcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = '🎯 Learning Outcomes';

    protected static ?int $navigationSort = 43;

    protected static ?string $navigationLabel = 'Sub-CPMK';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_learning_outcome_id')
                    ->label('CPMK')
                    ->relationship('courseLearningOutcome', 'code')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih CPMK induk untuk Sub-CPMK ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Sub-CPMK')
                    ->placeholder('Contoh: SCPMK01, Sub-01')
                    ->helperText('Kode unik Sub Capaian Pembelajaran Mata Kuliah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Rumusan Sub-CPMK')
                    ->placeholder('Tulis rumusan detail Sub-CPMK...')
                    ->helperText('Rumusan spesifik sub kompetensi yang ingin dicapai')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('bloom_cognitive_level')
                    ->label('Level Kognitif')
                    ->options(['C1'=>'C1 - Mengingat','C2'=>'C2 - Memahami','C3'=>'C3 - Menerapkan','C4'=>'C4 - Menganalisis','C5'=>'C5 - Mengevaluasi','C6'=>'C6 - Mencipta'])
                    ->helperText('Level kognitif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_affective_level')
                    ->label('Level Afektif')
                    ->options(['A1'=>'A1 - Menerima','A2'=>'A2 - Menanggapi','A3'=>'A3 - Menilai','A4'=>'A4 - Mengelola','A5'=>'A5 - Menghayati'])
                    ->helperText('Level afektif Taksonomi Bloom'),
                Forms\Components\Select::make('bloom_psychomotor_level')
                    ->label('Level Psikomotorik')
                    ->options(['P1'=>'P1 - Imitasi','P2'=>'P2 - Manipulasi','P3'=>'P3 - Presisi','P4'=>'P4 - Artikulasi','P5'=>'P5 - Naturalisasi'])
                    ->helperText('Level psikomotorik Taksonomi Bloom'),
                Forms\Components\TextInput::make('week_number')
                    ->label('Minggu Ke-')
                    ->placeholder('Contoh: 1, 2, 3')
                    ->helperText('Minggu pertemuan di mana Sub-CPMK ini diajarkan (1-16)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(16)
                    ->default(null),
                Forms\Components\Textarea::make('learning_materials')
                    ->label('Materi Pembelajaran')
                    ->placeholder('Tulis materi yang akan diajarkan...')
                    ->helperText('Materi pembelajaran untuk mencapai Sub-CPMK ini')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('learning_methods')
                    ->label('Metode Pembelajaran')
                    ->placeholder('Contoh: Ceramah, Diskusi, Praktikum')
                    ->helperText('Metode yang digunakan dalam pembelajaran')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Durasi (menit)')
                    ->helperText('Durasi pembelajaran dalam menit (contoh: 150 untuk 3 SKS)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('menit')
                    ->default(150),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan Sub-CPMK dalam pertemuan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('courseLearningOutcome.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bloom_cognitive_level'),
                Tables\Columns\TextColumn::make('bloom_affective_level'),
                Tables\Columns\TextColumn::make('bloom_psychomotor_level'),
                Tables\Columns\TextColumn::make('week_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
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
            'index' => Pages\ListSubCourseLearningOutcomes::route('/'),
            'create' => Pages\CreateSubCourseLearningOutcome::route('/create'),
            'view' => Pages\ViewSubCourseLearningOutcome::route('/{record}'),
            'edit' => Pages\EditSubCourseLearningOutcome::route('/{record}/edit'),
        ];
    }
}
