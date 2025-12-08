<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ObeAssessmentResource\Pages;
use App\Filament\Admin\Resources\ObeAssessmentResource\RelationManagers;
use App\Models\ObeAssessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObeAssessmentResource extends Resource
{
    protected static ?string $model = ObeAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = '📊 Assessment & Evaluasi';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Assessment OBE';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih mata kuliah yang akan diassess')
                    ->required(),
                Forms\Components\Select::make('rps_id')
                    ->label('RPS')
                    ->relationship('rps', 'id')
                    ->searchable()
                    ->helperText('Pilih RPS terkait (opsional)')
                    ->default(null),
                Forms\Components\TextInput::make('academic_year')
                    ->label('Tahun Akademik')
                    ->placeholder('Contoh: 2024/2025')
                    ->helperText('Tahun ajaran pelaksanaan assessment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                        'Pendek' => 'Pendek',
                    ])
                    ->helperText('Semester pelaksanaan')
                    ->required(),
                Forms\Components\TextInput::make('class_code')
                    ->label('Kode Kelas')
                    ->placeholder('Contoh: A, B, C, 01, 02')
                    ->helperText('Kode kelas/paralel (opsional)')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('total_students')
                    ->label('Jumlah Mahasiswa')
                    ->placeholder('0')
                    ->helperText('Total mahasiswa yang mengikuti kelas')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\TextInput::make('passed_students')
                    ->label('Mahasiswa Lulus')
                    ->placeholder('0')
                    ->helperText('Jumlah mahasiswa yang lulus (nilai ≥ batas kelulusan)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\TextInput::make('failed_students')
                    ->label('Mahasiswa Tidak Lulus')
                    ->placeholder('0')
                    ->helperText('Jumlah mahasiswa yang tidak lulus')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Textarea::make('clo_achievement')
                    ->label('Capaian CPMK (CLO)')
                    ->placeholder('Tulis analisis pencapaian CPMK...')
                    ->helperText('Analisis pencapaian Course Learning Outcomes (CPMK) per item')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('plo_achievement')
                    ->label('Capaian CPL (PLO)')
                    ->placeholder('Tulis analisis pencapaian CPL...')
                    ->helperText('Analisis pencapaian Program Learning Outcomes (CPL) yang dipetakan')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('statistics')
                    ->label('Statistik Nilai')
                    ->placeholder('Contoh: Mean: 78.5, Median: 80, Modus: 85, Std Dev: 8.2')
                    ->helperText('Statistik deskriptif nilai mahasiswa (mean, median, modus, standar deviasi)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('average_score')
                    ->label('Rata-rata Nilai')
                    ->placeholder('0.00')
                    ->helperText('Rata-rata nilai kelas (0-100)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('/100')
                    ->default(null),
                Forms\Components\TextInput::make('passing_rate')
                    ->label('Persentase Kelulusan')
                    ->placeholder('0.00')
                    ->helperText('Persentase mahasiswa yang lulus (0-100%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%')
                    ->default(null),
                Forms\Components\TextInput::make('clo_attainment_rate')
                    ->label('Tingkat Pencapaian CPMK')
                    ->placeholder('0.00')
                    ->helperText('Persentase pencapaian CPMK (0-100%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%')
                    ->default(null),
                Forms\Components\TextInput::make('plo_attainment_rate')
                    ->label('Tingkat Pencapaian CPL')
                    ->placeholder('0.00')
                    ->helperText('Persentase pencapaian CPL (0-100%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%')
                    ->default(null),
                Forms\Components\Textarea::make('strengths')
                    ->label('Kekuatan')
                    ->placeholder('Tulis kekuatan dalam pelaksanaan pembelajaran...')
                    ->helperText('Aspek-aspek yang sudah baik dalam pembelajaran')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('weaknesses')
                    ->label('Kelemahan')
                    ->placeholder('Tulis kelemahan atau area yang perlu perbaikan...')
                    ->helperText('Aspek-aspek yang perlu diperbaiki')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('analysis')
                    ->label('Analisis')
                    ->placeholder('Tulis analisis mendalam tentang hasil assessment...')
                    ->helperText('Analisis komprehensif hasil assessment dan faktor-faktor yang mempengaruhi')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('recommendations')
                    ->label('Rekomendasi')
                    ->placeholder('Tulis rekomendasi perbaikan...')
                    ->helperText('Rekomendasi untuk perbaikan pembelajaran di masa mendatang')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('improvement_actions')
                    ->label('Tindakan Perbaikan')
                    ->placeholder('Tulis rencana tindakan perbaikan...')
                    ->helperText('Rencana tindakan konkret untuk perbaikan (action plan)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('best_practices')
                    ->label('Best Practices')
                    ->placeholder('Tulis praktik terbaik yang dapat direplikasi...')
                    ->helperText('Praktik-praktik terbaik yang berhasil diterapkan')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('previous_improvements')
                    ->label('Perbaikan Sebelumnya')
                    ->placeholder('Tulis hasil dari perbaikan periode sebelumnya...')
                    ->helperText('Follow-up dari perbaikan yang dilakukan di periode sebelumnya')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('improvement_effective')
                    ->label('Perbaikan Efektif')
                    ->helperText('Centang jika tindakan perbaikan sebelumnya terbukti efektif')
                    ->inline(false),
                Forms\Components\TextInput::make('metabase_dashboard_url')
                    ->label('URL Dashboard Metabase')
                    ->placeholder('https://metabase.example.com/dashboard/123')
                    ->helperText('Link ke dashboard visualisasi data di Metabase (opsional)')
                    ->url()
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rps.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('class_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_students')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('passed_students')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('failed_students')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('passing_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clo_attainment_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plo_attainment_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('improvement_effective')
                    ->boolean(),
                Tables\Columns\TextColumn::make('metabase_dashboard_url')
                    ->searchable(),
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
            'index' => Pages\ListObeAssessments::route('/'),
            'create' => Pages\CreateObeAssessment::route('/create'),
            'view' => Pages\ViewObeAssessment::route('/{record}'),
            'edit' => Pages\EditObeAssessment::route('/{record}/edit'),
        ];
    }
}
