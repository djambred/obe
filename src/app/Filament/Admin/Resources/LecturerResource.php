<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LecturerResource\Pages;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Dosen';

    protected static ?string $navigationGroup = 'SDM Dosen';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\Section::make('Data Diri')
                                    ->schema([
                                        Forms\Components\FileUpload::make('photo')
                                            ->label('Foto')
                                            ->image()
                                            ->disk('minio')
                                            ->directory('profiles/lecturers')
                                            ->avatar()
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('faculty_id')
                                            ->label('Fakultas')
                                            ->relationship('faculty', 'name')
                                            ->required()
                                            ->reactive()
                                            ->preload(),
                                        Forms\Components\Select::make('study_program_id')
                                            ->label('Program Studi')
                                            ->relationship('studyProgram', 'name')
                                            ->preload(),
                                        Forms\Components\TextInput::make('nidn')
                                            ->label('NIDN')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('nip')
                                            ->label('NIP')
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->label('Telepon')
                                            ->tel()
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Kepegawaian')
                            ->schema([
                                Forms\Components\Section::make('Status & Jabatan')
                                    ->schema([
                                        Forms\Components\Select::make('employment_status')
                                            ->label('Status Kepegawaian')
                                            ->options([
                                                'PNS' => 'PNS',
                                                'PPPK' => 'PPPK',
                                                'Dosen Tetap' => 'Dosen Tetap',
                                                'Dosen Tidak Tetap' => 'Dosen Tidak Tetap',
                                                'Dosen LB' => 'Dosen LB',
                                            ])
                                            ->default('Dosen Tetap'),
                                        Forms\Components\Select::make('academic_rank')
                                            ->label('Jabatan Akademik')
                                            ->options([
                                                'Asisten Ahli' => 'Asisten Ahli',
                                                'Lektor' => 'Lektor',
                                                'Lektor Kepala' => 'Lektor Kepala',
                                                'Profesor' => 'Profesor',
                                            ]),
                                        Forms\Components\Select::make('functional_position')
                                            ->label('Jabatan Fungsional')
                                            ->options([
                                                'Tenaga Pengajar' => 'Tenaga Pengajar',
                                                'Asisten Ahli' => 'Asisten Ahli',
                                                'Lektor' => 'Lektor',
                                                'Lektor Kepala' => 'Lektor Kepala',
                                                'Guru Besar' => 'Guru Besar',
                                            ]),
                                        Forms\Components\Select::make('highest_education')
                                            ->label('Pendidikan Tertinggi')
                                            ->options([
                                                'S1' => 'S1',
                                                'S2' => 'S2',
                                                'S3' => 'S3',
                                            ])
                                            ->default('S2'),
                                        Forms\Components\TextInput::make('education_field')
                                            ->label('Bidang Pendidikan')
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Keahlian')
                            ->schema([
                                Forms\Components\Section::make('Bidang Keahlian & Penelitian')
                                    ->schema([
                                        Forms\Components\TagsInput::make('expertise_areas')
                                            ->label('Bidang Keahlian')
                                            ->placeholder('Tambah bidang keahlian')
                                            ->columnSpanFull(),
                                        Forms\Components\TagsInput::make('research_interests')
                                            ->label('Minat Penelitian')
                                            ->placeholder('Tambah minat penelitian')
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('biography')
                                            ->label('Biografi')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('SINTA Profile')
                            ->schema([
                                Forms\Components\Section::make('Data SINTA')
                                    ->schema([
                                        Forms\Components\TextInput::make('sinta_id')
                                            ->label('SINTA ID')
                                            ->maxLength(255)
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('sync_sinta')
                                                    ->icon('heroicon-o-arrow-path')
                                                    ->label('Sync')
                                                    ->action(function ($record) {
                                                        if ($record) {
                                                            $record->syncSintaProfile();
                                                        }
                                                    })
                                            ),
                                        Forms\Components\TextInput::make('sinta_score')
                                            ->label('SINTA Score')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('sinta_rank_national')
                                            ->label('Ranking Nasional')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('sinta_rank_affiliation')
                                            ->label('Ranking Afiliasi')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('sinta_publications')
                                            ->label('Jumlah Publikasi')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\DateTimePicker::make('last_profile_sync')
                                            ->label('Terakhir Sync')
                                            ->readOnly(),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Google Scholar')
                            ->schema([
                                Forms\Components\Section::make('Data Google Scholar')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_scholar_id')
                                            ->label('Google Scholar ID')
                                            ->maxLength(255)
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('sync_gs')
                                                    ->icon('heroicon-o-arrow-path')
                                                    ->label('Sync')
                                                    ->action(function ($record) {
                                                        if ($record) {
                                                            $record->syncGoogleScholarProfile();
                                                        }
                                                    })
                                            ),
                                        Forms\Components\TextInput::make('h_index')
                                            ->label('H-Index')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('i10_index')
                                            ->label('i10-Index')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('total_citations')
                                            ->label('Total Sitasi')
                                            ->numeric()
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('total_publications')
                                            ->label('Total Publikasi')
                                            ->numeric()
                                            ->readOnly(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('minio')
                    ->circular(),
                Tables\Columns\TextColumn::make('nidn')
                    ->label('NIDN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->label('Prodi')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('academic_rank')
                    ->label('Jabatan Akademik')
                    ->badge(),
                Tables\Columns\TextColumn::make('sinta_score')
                    ->label('SINTA Score')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('h_index')
                    ->label('H-Index')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('last_profile_sync')
                    ->label('Last Sync')
                    ->dateTime()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name')
                    ->preload(),
                Tables\Filters\SelectFilter::make('study_program_id')
                    ->label('Program Studi')
                    ->relationship('studyProgram', 'name')
                    ->preload(),
                Tables\Filters\SelectFilter::make('academic_rank')
                    ->label('Jabatan Akademik')
                    ->options([
                        'Asisten Ahli' => 'Asisten Ahli',
                        'Lektor' => 'Lektor',
                        'Lektor Kepala' => 'Lektor Kepala',
                        'Profesor' => 'Profesor',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sync_profile')
                    ->label('Sync Profile')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Lecturer $record) {
                        $record->syncSintaProfile();
                        $record->syncGoogleScholarProfile();
                    }),
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
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
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
