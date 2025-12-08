<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudyFieldResource\Pages;
use App\Filament\Admin\Resources\StudyFieldResource\RelationManagers;
use App\Models\StudyField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyFieldResource extends Resource
{
    protected static ?string $model = StudyField::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationGroup = '📚 Kurikulum';

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program studi untuk bahan kajian ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Bahan Kajian')
                    ->placeholder('Contoh: BK01, BK02')
                    ->helperText('Kode unik bahan kajian')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Bahan Kajian')
                    ->placeholder('Contoh: Pemrograman Berorientasi Objek')
                    ->helperText('Nama lengkap bahan kajian/bidang keilmuan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tulis deskripsi lengkap tentang bahan kajian ini...')
                    ->helperText('Deskripsi detail bahan kajian dan cakupannya')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Matematika dan Sains' => 'Matematika dan Sains',
                        'Rekayasa' => 'Rekayasa',
                        'Komputasi' => 'Komputasi',
                        'Sistem Informasi' => 'Sistem Informasi',
                        'Keahlian' => 'Keahlian',
                        'Soft Skills' => 'Soft Skills',
                        'Humaniora' => 'Humaniora',
                        'Lainnya' => 'Lainnya'
                    ])
                    ->helperText('Kategori/kelompok bahan kajian')
                    ->required(),
                Forms\Components\Textarea::make('sub_fields')
                    ->label('Sub Bahan Kajian')
                    ->placeholder('Format JSON: ["Sub 1", "Sub 2", "Sub 3"]')
                    ->helperText('Daftar sub bahan kajian dalam format JSON array')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan (semakin kecil semakin atas)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('Bahan kajian yang masih digunakan dalam kurikulum')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
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
            'index' => Pages\ListStudyFields::route('/'),
            'create' => Pages\CreateStudyField::route('/create'),
            'view' => Pages\ViewStudyField::route('/{record}'),
            'edit' => Pages\EditStudyField::route('/{record}/edit'),
        ];
    }
}
