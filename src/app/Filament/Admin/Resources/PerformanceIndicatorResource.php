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

    protected static ?string $navigationGroup = '🎯 Learning Outcomes';

    protected static ?int $navigationSort = 44;

    protected static ?string $navigationLabel = 'Indikator Kinerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sub_course_learning_outcome_id')
                    ->label('Sub-CPMK (Opsional)')
                    ->relationship('subCourseLearningOutcome', 'code')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih Sub-CPMK jika indikator terkait Sub-CPMK spesifik')
                    ->default(null),
                Forms\Components\Select::make('course_learning_outcome_id')
                    ->label('CPMK')
                    ->relationship('courseLearningOutcome', 'code')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih CPMK yang terkait dengan indikator kinerja ini')
                    ->default(null),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Indikator')
                    ->placeholder('Contoh: IK01, IND-01')
                    ->helperText('Kode unik Indikator Kinerja')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Indikator')
                    ->placeholder('Tulis deskripsi lengkap indikator kinerja...')
                    ->helperText('Deskripsi lengkap indikator yang akan diukur')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('criteria')
                    ->label('Kriteria Penilaian')
                    ->placeholder('Format JSON: kriteria penilaian untuk setiap level')
                    ->helperText('Kriteria detail untuk mengukur pencapaian (JSON)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rubric')
                    ->label('Rubrik Penilaian')
                    ->placeholder('Format JSON: rubrik detail (sangat baik, baik, cukup, kurang)')
                    ->helperText('Rubrik penilaian dengan skala (JSON)')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('weight')
                    ->label('Bobot (%)')
                    ->helperText('Bobot indikator dalam penilaian (0-100%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(0),
                Forms\Components\Select::make('assessment_type')
                    ->label('Jenis Penilaian')
                    ->options([
                        'Tugas' => 'Tugas',
                        'Quiz' => 'Quiz',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                        'Praktikum' => 'Praktikum',
                        'Proyek' => 'Proyek',
                        'Presentasi' => 'Presentasi',
                        'Portfolio' => 'Portfolio'
                    ])
                    ->helperText('Jenis penilaian yang digunakan'),
                Forms\Components\TextInput::make('passing_grade')
                    ->label('Nilai Minimal Kelulusan')
                    ->helperText('Nilai minimal yang harus dicapai (0-100)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(70),
                Forms\Components\Textarea::make('grading_scale')
                    ->label('Skala Penilaian')
                    ->placeholder('Format JSON: {"A": "85-100", "B": "70-84", ...}')
                    ->helperText('Skala konversi nilai (JSON)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan indikator')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('Indikator yang masih digunakan')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subCourseLearningOutcome.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('courseLearningOutcome.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessment_type'),
                Tables\Columns\TextColumn::make('passing_grade')
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
            'index' => Pages\ListPerformanceIndicators::route('/'),
            'create' => Pages\CreatePerformanceIndicator::route('/create'),
            'view' => Pages\ViewPerformanceIndicator::route('/{record}'),
            'edit' => Pages\EditPerformanceIndicator::route('/{record}/edit'),
        ];
    }
}
