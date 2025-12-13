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

    protected static ?string $navigationGroup = 'Kurikulum & MK';

    protected static ?int $navigationSort = 20;

    protected static ?string $pluralModelLabel = 'Kurikulum';

    protected static ?string $modelLabel = 'Kurikulum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar (DIKTI/OBE)')
                    ->schema([
                        Forms\Components\Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih program studi untuk kurikulum ini')
                            ->required(),
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
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Kurikulum yang sedang aktif digunakan')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Struktur SKS (Sederhana)')
                    ->description('Distribusi SKS sesuai kebijakan DIKTI & OBE – target total umumnya 144 SKS untuk S1')
                    ->schema([
                        Forms\Components\TextInput::make('total_credits')
                            ->label('Total SKS')
                            ->helperText('Total SKS kelulusan (standar: 145 SKS)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(145)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $total = (int) $state;
                                $mkwu = (int) ($get('mandatory_university_credits') ?? 20);
                                $sisa = $total - $mkwu;

                                // Auto-distribute: 80% Wajib Prodi, 20% Pilihan/Peminatan
                                $wajibProdi = (int) ($sisa * 0.8);
                                $pilihan = $sisa - $wajibProdi;

                                $set('mandatory_program_credits', $wajibProdi);
                                $set('elective_credits', $pilihan);
                            }),
                        Forms\Components\TextInput::make('mandatory_university_credits')
                            ->label('MKWU (Wajib Universitas)')
                            ->helperText('SKS Wajib Universitas (standar: 20 SKS)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(20)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $total = (int) ($get('total_credits') ?? 145);
                                $mkwu = (int) $state;
                                $sisa = $total - $mkwu;

                                // Auto-distribute: 80% Wajib Prodi, 20% Pilihan/Peminatan
                                $wajibProdi = (int) ($sisa * 0.8);
                                $pilihan = $sisa - $wajibProdi;

                                $set('mandatory_program_credits', $wajibProdi);
                                $set('elective_credits', $pilihan);
                            }),
                        Forms\Components\Placeholder::make('calculated_info')
                            ->label('Perhitungan Otomatis')
                            ->content(function (callable $get) {
                                $total = (int) ($get('total_credits') ?? 145);
                                $mkwu = (int) ($get('mandatory_university_credits') ?? 20);
                                $wajibProdi = (int) ($get('mandatory_program_credits') ?? 101);
                                $pilihan = (int) ($get('elective_credits') ?? 24);
                                $sum = $mkwu + $wajibProdi + $pilihan;

                                $statusBadge = $sum === $total
                                    ? '✅ Sesuai'
                                    : '⚠️ Tidak sesuai (selisih: ' . abs($total - $sum) . ' SKS)';

                                return new \Illuminate\Support\HtmlString("
                                    <div class='space-y-2'>
                                        <div class='text-sm'>
                                            <strong>Sisa setelah MKWU:</strong> " . ($total - $mkwu) . " SKS
                                        </div>
                                        <div class='text-sm'>
                                            <strong>Distribusi:</strong><br>
                                            • MKWU: {$mkwu} SKS<br>
                                            • Wajib Prodi: {$wajibProdi} SKS<br>
                                            • Pilihan/Peminatan: {$pilihan} SKS<br>
                                            <strong>Total: {$sum} SKS</strong> {$statusBadge}
                                        </div>
                                    </div>
                                ");
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('mandatory_program_credits')
                            ->label('SKS Wajib Prodi')
                            ->helperText('Total SKS MK wajib Prodi (otomatis dihitung, dapat disesuaikan)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(101)
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('elective_credits')
                            ->label('SKS Pilihan/Peminatan')
                            ->helperText('Total SKS MK pilihan termasuk peminatan (otomatis dihitung, dapat disesuaikan)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(24)
                            ->live(onBlur: true),
                    ])->columns(2),

                Forms\Components\Section::make('Catatan Kurikulum')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tujuan kurikulum, acuan DIKTI/OBE, kebijakan internal singkat')
                            ->helperText('Ringkas dan jelas – sesuai kebijakan DIKTI/OBE')
                            ->rows(3)
                            ->columnSpanFull(),
                        // Bidang-bidang detail disederhanakan untuk memudahkan adopsi
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Kurikulum')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')->label('Program Studi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('academic_year_start')->label('Tahun Mulai')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('total_credits')->label('Total SKS')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diubah')->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('study_program_id')->label('Program Studi')->relationship('studyProgram', 'name')->preload(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
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
