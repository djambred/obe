<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FacultyResource\Pages;
use App\Filament\Admin\Resources\FacultyResource\RelationManagers;
use App\Models\Faculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Institusi';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('university_id')
                    ->label('Universitas')
                    ->relationship('university', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih universitas yang menaungi fakultas ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Fakultas')
                    ->placeholder('Contoh: FT, FE, FMIPA')
                    ->helperText('Kode unik fakultas (biasanya singkatan)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Fakultas')
                    ->placeholder('Contoh: Fakultas Teknik')
                    ->helperText('Nama lengkap fakultas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Section::make('Visi, Misi, dan Tujuan')
                    ->description('Visi, misi, dan tujuan fakultas yang sejalan dengan universitas')
                    ->schema([
                        Forms\Components\Textarea::make('vision')
                            ->label('Visi')
                            ->placeholder('Tulis visi fakultas yang sejalan dengan visi universitas...')
                            ->helperText('Visi fakultas (turunan dari visi universitas)')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('mission')
                            ->label('Misi')
                            ->simple(
                                Forms\Components\Textarea::make('item')
                                    ->label('Poin Misi')
                                    ->placeholder('Tulis satu poin misi...')
                                    ->rows(2)
                                    ->required()
                            )
                            ->defaultItems(3)
                            ->addActionLabel('Tambah Misi')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['item'] ? substr($state['item'], 0, 50) . '...' : null)
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('objectives')
                            ->label('Tujuan')
                            ->simple(
                                Forms\Components\Textarea::make('item')
                                    ->label('Poin Tujuan')
                                    ->placeholder('Tulis satu poin tujuan...')
                                    ->rows(2)
                                    ->required()
                            )
                            ->defaultItems(3)
                            ->addActionLabel('Tambah Tujuan')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['item'] ? substr($state['item'], 0, 50) . '...' : null)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tulis deskripsi singkat tentang fakultas...')
                    ->helperText('Deskripsi singkat tentang fakultas')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('logo')
                    ->visibility('public')
                    ->label('Logo Fakultas')
                    ->helperText('Upload logo fakultas (format: JPG, PNG, max 2MB)')
                    ->image()
                    ->disk('minio')
                    ->directory('faculties/logos')
                    ->maxSize(2048),
                Forms\Components\TextInput::make('dean_name')
                    ->label('Nama Dekan')
                    ->placeholder('Prof. Dr. Nama Dekan')
                    ->helperText('Nama lengkap Dekan saat ini')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('phone')
                    ->label('Telepon')
                    ->placeholder('021-12345678')
                    ->helperText('Nomor telepon fakultas')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->placeholder('fakultas@universitas.ac.id')
                    ->helperText('Email resmi fakultas')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('accreditation')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DatePicker::make('accreditation_date'),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('university.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dean_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation_date')
                    ->date()
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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'view' => Pages\ViewFaculty::route('/{record}'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
