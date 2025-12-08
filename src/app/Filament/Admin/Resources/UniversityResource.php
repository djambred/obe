<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UniversityResource\Pages;
use App\Models\University;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = '🏛️ Institusi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Contoh: UNIV001')
                            ->helperText('Kode unik untuk identifikasi universitas')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Universitas')
                            ->placeholder('Contoh: Universitas Indonesia')
                            ->helperText('Nama lengkap universitas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->helperText('Upload logo universitas (format: JPG, PNG, max 2MB)')
                            ->image()
                            ->disk('minio')
                            ->directory('universities/logos')
                            ->maxSize(2048),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tulis deskripsi singkat tentang universitas...')
                            ->helperText('Deskripsi singkat tentang universitas')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Visi, Misi, dan Tujuan')
                    ->schema([
                        Forms\Components\Textarea::make('vision')
                            ->label('Visi')
                            ->placeholder('Tulis visi universitas...')
                            ->helperText('Pernyataan visi universitas untuk masa depan')
                            ->required()
                            ->rows(3),
                        Forms\Components\Repeater::make('mission')
                            ->label('Misi')
                            ->helperText('Tambahkan satu atau lebih pernyataan misi')
                            ->simple(
                                Forms\Components\Textarea::make('item')
                                    ->label('Misi')
                                    ->placeholder('Tulis pernyataan misi...')
                                    ->required()
                            )
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('objectives')
                            ->label('Tujuan')
                            ->helperText('Tambahkan tujuan-tujuan strategis universitas')
                            ->simple(
                                Forms\Components\Textarea::make('item')
                                    ->label('Tujuan')
                                    ->placeholder('Tulis tujuan strategis...')
                                    ->required()
                            )
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Kontak & Informasi')
                    ->schema([
                        Forms\Components\TextInput::make('rector_name')
                            ->label('Nama Rektor')
                            ->placeholder('Prof. Dr. Nama Rektor')
                            ->helperText('Nama lengkap Rektor saat ini')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->placeholder('info@universitas.ac.id')
                            ->helperText('Email resmi universitas')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->placeholder('021-12345678')
                            ->helperText('Nomor telepon universitas')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->placeholder('https://www.universitas.ac.id')
                            ->helperText('URL website resmi universitas')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->placeholder('Jl. Universitas No. 1, Kota, Provinsi')
                            ->helperText('Alamat lengkap kampus utama')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Akreditasi')
                    ->schema([
                        Forms\Components\TextInput::make('accreditation')
                            ->label('Akreditasi')
                            ->placeholder('A, B, C, atau Unggul')
                            ->helperText('Status akreditasi institusi dari BAN-PT')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('accreditation_date')
                            ->label('Tanggal Akreditasi')
                            ->helperText('Tanggal terbit sertifikat akreditasi'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Status aktif universitas dalam sistem')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('minio')
                    ->square(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rector_name')
                    ->label('Rektor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation')
                    ->label('Akreditasi')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
