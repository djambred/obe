<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LecturerResource\Pages;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                                            ->visibility('public')
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
                                        Forms\Components\TextInput::make('expertise_areas')
                                            ->label('Bidang Keahlian')
                                            ->hint('Pisahkan dengan koma')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('research_interests')
                                            ->label('Minat Penelitian')
                                            ->hint('Pisahkan dengan koma')
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
                                                    ->color('warning')
                                                    ->action(function (Lecturer $record) {
                                                        try {
                                                            if ($record && !empty($record->sinta_id)) {
                                                                if ($record->syncSintaProfile()) {
                                                                    Notification::make()
                                                                        ->title('✅ SINTA Synced')
                                                                        ->body("Score: {$record->sinta_score}")
                                                                        ->success()
                                                                        ->send();
                                                                } else {
                                                                    Notification::make()
                                                                        ->title('⚠️ Sync Gagal')
                                                                        ->body('Gagal fetch data dari SINTA')
                                                                        ->warning()
                                                                        ->send();
                                                                }
                                                            }
                                                        } catch (\Exception $e) {
                                                            Notification::make()
                                                                ->title('❌ Error')
                                                                ->body($e->getMessage())
                                                                ->danger()
                                                                ->send();
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

                        Forms\Components\Tabs\Tab::make('Google Scholar Profile')
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
                                                    ->color('warning')
                                                    ->action(function (Lecturer $record) {
                                                        try {
                                                            if ($record && !empty($record->google_scholar_id)) {
                                                                if ($record->syncGoogleScholarProfile()) {
                                                                    Notification::make()
                                                                        ->title('✅ Google Scholar Synced')
                                                                        ->body("H-Index: {$record->h_index}")
                                                                        ->success()
                                                                        ->send();
                                                                } else {
                                                                    Notification::make()
                                                                        ->title('⚠️ Sync Gagal')
                                                                        ->body('Gagal fetch data dari Google Scholar')
                                                                        ->warning()
                                                                        ->send();
                                                                }
                                                            }
                                                        } catch (\Exception $e) {
                                                            Notification::make()
                                                                ->title('❌ Error')
                                                                ->body($e->getMessage())
                                                                ->danger()
                                                                ->send();
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

                        Forms\Components\Tabs\Tab::make('Jabatan Akademik')
                            ->schema([
                                Forms\Components\Section::make('Posisi Akademik')
                                    ->description('Kelola posisi akademik di institusi')
                                    ->schema([
                                        Forms\Components\Repeater::make('academic_positions')
                                            ->label('Posisi Akademik')
                                            ->schema([
                                                Forms\Components\Select::make('position')
                                                    ->label('Posisi')
                                                    ->options([
                                                        'Rektor' => 'Rektor',
                                                        'Wakil Rektor' => 'Wakil Rektor',
                                                        'Dekan' => 'Dekan',
                                                        'Wakil Dekan' => 'Wakil Dekan',
                                                        'Ketua Program Studi' => 'Ketua Program Studi',
                                                        'Sekretaris Program Studi' => 'Sekretaris Program Studi',
                                                        'Koordinator Mata Kuliah' => 'Koordinator Mata Kuliah',
                                                        'Kepala Laboratorium' => 'Kepala Laboratorium',
                                                        'Ketua Tim Kurikulum' => 'Ketua Tim Kurikulum',
                                                        'Lainnya' => 'Lainnya',
                                                    ])
                                                    ->searchable()
                                                    ->required()
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('position_other')
                                                    ->label('Posisi Lainnya (jika pilih Lainnya)')
                                                    ->placeholder('Masukkan posisi akademik lainnya')
                                                    ->columnSpan(1)
                                                    ->visible(fn (Forms\Get $get) => $get('position') === 'Lainnya'),
                                                Forms\Components\Select::make('unit')
                                                    ->label('Unit / Organisasi')
                                                    ->relationship('faculty', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->columnSpan(2),
                                                Forms\Components\DatePicker::make('start_date')
                                                    ->label('Tanggal Mulai')
                                                    ->columnSpan(1),
                                                Forms\Components\DatePicker::make('end_date')
                                                    ->label('Tanggal Berakhir')
                                                    ->columnSpan(1),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Deskripsi / Tugas Pokok')
                                                    ->placeholder('Tulis deskripsi tugas dan tanggung jawab...')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['position'] ? ($state['position'] === 'Lainnya' ? ($state['position_other'] ?? 'Posisi Lainnya') : $state['position']) : null)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Non Akademik')
                            ->schema([
                                Forms\Components\Section::make('Posisi Administrasi & SDM')
                                    ->description('Kelola posisi administrasi, SDM, dan bidang lainnya')
                                    ->schema([
                                        Forms\Components\Repeater::make('administrative_positions')
                                            ->label('Posisi Non Akademik')
                                            ->schema([
                                                Forms\Components\Select::make('category')
                                                    ->label('Kategori')
                                                    ->options([
                                                        'SDM / Human Resources' => 'SDM / Human Resources',
                                                        'Keuangan' => 'Keuangan',
                                                        'Administrasi' => 'Administrasi',
                                                        'IT / Sistem Informasi' => 'IT / Sistem Informasi',
                                                        'Perpustakaan' => 'Perpustakaan',
                                                        'Laboratorium' => 'Laboratorium',
                                                        'Rumah Sakit / Klinik' => 'Rumah Sakit / Klinik',
                                                        'Sarana & Prasarana' => 'Sarana & Prasarana',
                                                        'Lembaga / Unit Khusus' => 'Lembaga / Unit Khusus',
                                                        'Lainnya' => 'Lainnya',
                                                    ])
                                                    ->searchable()
                                                    ->required()
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('position')
                                                    ->label('Jabatan / Posisi')
                                                    ->placeholder('Contoh: Kepala SDM, Bendahara, Admin Database, dll')
                                                    ->required()
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('unit')
                                                    ->label('Unit / Departemen')
                                                    ->placeholder('Contoh: Bagian SDM, Direktorat Keuangan, dll')
                                                    ->columnSpan(2),
                                                Forms\Components\DatePicker::make('start_date')
                                                    ->label('Tanggal Mulai')
                                                    ->columnSpan(1),
                                                Forms\Components\DatePicker::make('end_date')
                                                    ->label('Tanggal Berakhir')
                                                    ->columnSpan(1),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Deskripsi / Tugas Pokok')
                                                    ->placeholder('Tulis deskripsi tugas dan tanggung jawab...')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => ($state['position'] ?? '') . ($state['unit'] ? ' - ' . $state['unit'] : ''))
                                            ->columnSpanFull(),
                                    ]),
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
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->label('Prodi')
                    ->wrap()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('academic_rank')
                    ->label('Jabatan Akademik')
                    ->wrap()
                    ->badge(),
                Tables\Columns\TextColumn::make('sinta_score')
                    ->label('SINTA Score')
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('h_index')
                    ->label('H-Index')
                    ->wrap()
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
                    ->color('warning')
                    ->action(function (Lecturer $record) {
                        try {
                            $sintaSuccess = false;
                            $gsSuccess = false;
                            $messages = [];

                            if (!empty($record->sinta_id)) {
                                if ($record->syncSintaProfile()) {
                                    $sintaSuccess = true;
                                    $messages[] = "SINTA: Score {$record->sinta_score}";
                                } else {
                                    $messages[] = "SINTA: Gagal disinkronisasi";
                                }
                            }

                            if (!empty($record->google_scholar_id)) {
                                if ($record->syncGoogleScholarProfile()) {
                                    $gsSuccess = true;
                                    $messages[] = "Google Scholar: H-Index {$record->h_index}";
                                } else {
                                    $messages[] = "Google Scholar: Gagal disinkronisasi";
                                }
                            }

                            if ($sintaSuccess || $gsSuccess) {
                                Notification::make()
                                    ->title('✅ Sinkronisasi Berhasil')
                                    ->body(implode(', ', $messages))
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('⚠️ Sinkronisasi Gagal')
                                    ->body('Pastikan SINTA ID atau Google Scholar ID sudah diisi.')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('❌ Error')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Sinkronisasi Profile')
                    ->modalSubheading('Sinkronisasi data SINTA dan Google Scholar (proses 2-5 detik)')
                    ->modalButton('Ya, Sinkronisasi'),
                Tables\Actions\Action::make('view_profile')
                    ->label('View Profile')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Lecturer $record) => route('lecturer.show', $record))
                    ->openUrlInNewTab(),
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
