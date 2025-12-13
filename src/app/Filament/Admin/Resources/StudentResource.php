<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('nim')->label('NIM')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('name')->label('Nama')->required(),
                        Forms\Components\TextInput::make('email')->label('Email')->email()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')->label('Telepon'),
                    ])->columns(2),
                Forms\Components\Section::make('Akademik')
                    ->schema([
                        Forms\Components\Select::make('faculty_id')->label('Fakultas')->relationship('faculty', 'name')->preload(),
                        Forms\Components\Select::make('study_program_id')->label('Program Studi')->relationship('studyProgram', 'name')->preload(),
                        Forms\Components\TextInput::make('enrollment_year')->label('Angkatan')->numeric()->minValue(2000)->maxValue(2100),
                        Forms\Components\TextInput::make('class_group')->label('Kelas'),
                        Forms\Components\Select::make('status')->label('Status')->options([
                            'Aktif' => 'Aktif',
                            'Cuti' => 'Cuti',
                            'Lulus' => 'Lulus',
                            'Nonaktif' => 'Nonaktif',
                            'Dropout' => 'Dropout',
                        ])->default('Aktif'),
                    ])->columns(3),
                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\Select::make('gender')->label('Jenis Kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                        Forms\Components\DatePicker::make('birth_date')->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('birth_place')->label('Tempat Lahir'),
                        Forms\Components\TextInput::make('address')->label('Alamat')->columnSpanFull(),
                        Forms\Components\TextInput::make('city')->label('Kota'),
                        Forms\Components\TextInput::make('province')->label('Provinsi'),
                    ])->columns(2),
                Forms\Components\Section::make('Orang Tua / Wali')
                    ->schema([
                        Forms\Components\TextInput::make('parent_name')->label('Nama Wali'),
                        Forms\Components\TextInput::make('parent_phone')->label('Telepon Wali'),
                    ])->columns(2),
                Forms\Components\Section::make('Lainnya')
                    ->schema([
                        Forms\Components\Textarea::make('extras')->label('Tambahan (JSON)')->rows(3),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nim')->label('NIM')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')->label('Prodi')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('enrollment_year')->label('Angkatan')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                    'success' => 'Aktif',
                    'warning' => 'Cuti',
                    'info' => 'Lulus',
                    'danger' => 'Dropout',
                ])->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('study_program_id')->label('Program Studi')->relationship('studyProgram', 'name')->preload(),
                Tables\Filters\SelectFilter::make('status')->label('Status')->options([
                    'Aktif' => 'Aktif',
                    'Cuti' => 'Cuti',
                    'Lulus' => 'Lulus',
                    'Nonaktif' => 'Nonaktif',
                    'Dropout' => 'Dropout',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
