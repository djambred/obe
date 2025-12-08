<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudyProgramResource\Pages;
use App\Filament\Admin\Resources\StudyProgramResource\RelationManagers;
use App\Models\StudyProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyProgramResource extends Resource
{
    protected static ?string $model = StudyProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = '🏛️ Institusi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih fakultas yang menaungi program studi ini')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Prodi')
                    ->placeholder('Contoh: TIF, SI, AK')
                    ->helperText('Kode unik program studi (biasanya singkatan)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Program Studi')
                    ->placeholder('Contoh: Teknik Informatika')
                    ->helperText('Nama lengkap program studi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('level')
                    ->label('Jenjang')
                    ->options([
                        'D3' => 'Diploma 3 (D3)',
                        'D4' => 'Diploma 4 (D4)',
                        'S1' => 'Sarjana (S1)',
                        'S2' => 'Magister (S2)',
                        'S3' => 'Doktor (S3)',
                        'Profesi' => 'Profesi',
                        'Spesialis' => 'Spesialis'
                    ])
                    ->helperText('Pilih jenjang pendidikan program studi')
                    ->required(),
                Forms\Components\Textarea::make('vision')
                    ->label('Visi')
                    ->placeholder('Tulis visi program studi yang sejalan dengan visi fakultas...')
                    ->helperText('Visi program studi (turunan dari visi fakultas)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('mission')
                    ->label('Misi')
                    ->placeholder('Tulis misi program studi (dalam format JSON array atau teks)...')
                    ->helperText('Misi program studi (turunan dari misi fakultas)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('objectives')
                    ->label('Tujuan')
                    ->placeholder('Tulis tujuan program studi (dalam format JSON array atau teks)...')
                    ->helperText('Tujuan program studi (turunan dari tujuan fakultas)')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tulis deskripsi singkat tentang program studi...')
                    ->helperText('Deskripsi singkat tentang program studi')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('head_of_program')
                    ->label('Ketua Program Studi')
                    ->placeholder('Dr. Nama Kaprodi')
                    ->helperText('Nama lengkap Ketua Program Studi saat ini')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('secretary')
                    ->label('Sekretaris Program Studi')
                    ->placeholder('Dr. Nama Sekretaris')
                    ->helperText('Nama lengkap Sekretaris Program Studi')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('accreditation')
                    ->label('Akreditasi')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'Unggul' => 'Unggul',
                        'Baik Sekali' => 'Baik Sekali',
                        'Baik' => 'Baik'
                    ])
                    ->helperText('Status akreditasi program studi dari BAN-PT/LAM')
                    ->default(null),
                Forms\Components\DatePicker::make('accreditation_date')
                    ->label('Tanggal Akreditasi')
                    ->helperText('Tanggal terbit sertifikat akreditasi'),
                Forms\Components\TextInput::make('accreditation_number')
                    ->label('Nomor SK Akreditasi')
                    ->placeholder('Contoh: 12345/SK/BAN-PT/Akred/S/XII/2024')
                    ->helperText('Nomor Surat Keputusan akreditasi')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('standard_study_period')
                    ->required()
                    ->numeric()
                    ->default(8),
                Forms\Components\TextInput::make('degree_awarded')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('faculty.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('head_of_program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secretary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accreditation_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('standard_study_period')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('degree_awarded')
                    ->searchable(),
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
            'index' => Pages\ListStudyPrograms::route('/'),
            'create' => Pages\CreateStudyProgram::route('/create'),
            'view' => Pages\ViewStudyProgram::route('/{record}'),
            'edit' => Pages\EditStudyProgram::route('/{record}/edit'),
        ];
    }
}
