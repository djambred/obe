<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GraduateProfileResource\Pages;
use App\Filament\Admin\Resources\GraduateProfileResource\RelationManagers;
use App\Models\GraduateProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GraduateProfileResource extends Resource
{
    protected static ?string $model = GraduateProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = '🎯 Learning Outcomes';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Profil Lulusan (PL)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program studi untuk profil lulusan ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Profil Lulusan')
                    ->placeholder('Contoh: PL01, PL02')
                    ->helperText('Kode unik profil lulusan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Profil Lulusan')
                    ->placeholder('Contoh: Software Engineer, Data Analyst')
                    ->helperText('Nama profil/peran lulusan di dunia kerja')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tulis deskripsi lengkap profil lulusan, kompetensi yang diharapkan...')
                    ->helperText('Deskripsi lengkap profil lulusan dan kompetensinya')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('career_prospects')
                    ->label('Prospek Karir')
                    ->placeholder('Format JSON: ["Jabatan 1", "Jabatan 2"] atau teks biasa')
                    ->helperText('Daftar prospek karir/jabatan yang dapat dicapai (JSON array)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('work_areas')
                    ->label('Bidang Kerja')
                    ->placeholder('Format JSON: ["Industri A", "Sektor B"] atau teks biasa')
                    ->helperText('Bidang/industri tempat bekerja (JSON array)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan profil lulusan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('Profil lulusan yang masih relevan dengan kurikulum saat ini')
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
            'index' => Pages\ListGraduateProfiles::route('/'),
            'create' => Pages\CreateGraduateProfile::route('/create'),
            'view' => Pages\ViewGraduateProfile::route('/{record}'),
            'edit' => Pages\EditGraduateProfile::route('/{record}/edit'),
        ];
    }
}
