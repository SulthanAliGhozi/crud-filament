<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Filament\Infolists\Components;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\SiswaResource\Pages\EditSiswa;
use App\Filament\Resources\SiswaResource\Pages\ViewSiswa;
use App\Filament\Resources\SiswaResource\Pages\ListSiswas;
use App\Filament\Resources\SiswaResource\Pages\CreateSiswa;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $navigationGroup = 'Siswa';
    protected static ?string $slug = 'kelola-siswa';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->placeholder('Masukan Nama Siswa'),
                        Forms\Components\TextInput::make('nisn')
                            ->required()
                            ->numeric()
                            ->placeholder('Masukan Nisn Siswa'),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        Forms\Components\TextInput::make('kontak')
                            ->label('No Telp')
                            ->required()
                            ->numeric()
                            ->placeholder('Masukan No Telp. Siswa'),
                        Forms\Components\Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Khonghucu' => 'Khonghucu',
                            ])
                            ->placeholder('Pilih Agama Siswa')
                            ->required(),
                        Forms\Components\Select::make('kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->placeholder('Pilih Kelamin Siswa Siswa')
                            ->required(),
                        Forms\Components\Select::make('kelas')
                            ->options([
                                'X' => 'X',
                                'XI' => 'XI',
                                'XII' => 'XII',
                            ])
                            ->placeholder('Pilih Kelas Siswa')
                            ->required(),
                        Select::make('jurusan_id')
                            ->relationship('Jurusan', 'nama'),
                        Textarea::make('alamat')->required()
                            ->placeholder('Masukan Alamat Siswa'),
                        Forms\Components\FileUpload::make('profil')
                            ->directory('siswas')
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->previewable(true)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nisn')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agama')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('kelamin')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kontak')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelas')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama')
                ->searchable()
                ->sortable(),
                ImageColumn::make('profil')->circular(),
                Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(fn(string $state): string => ucwords("{$state}")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jurusan_id')
                    ->relationship('jurusan', 'nama'),
                SelectFilter::make('status')
                ->multiple()
                ->options( [
                    'accept' => 'Accept',
                    'off' => 'Off',
                    'move' => 'Move',
                    'grade' => 'Grade'
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Change Status')
                    ->icon('heroicon-m-check')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('Status')
                        ->label('Status')
                        ->options(['accept' => 'Accept', 'off' => 'Off', 'move' => 'Move', 'grade' => 'Grade'])
                        ->required(),
                    ])
                    ->action(function (Collection $records, array $data){
                        $records->each(function($record) use ($data){
                            Siswa::where('id', $record->id)->update(['Status' => $data['Status']]);
                        });
                    }),

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
            'index' => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'edit' => EditSiswa::route('/{record}/edit'),
            'view' => ViewSiswa::route('/{record}'),
        ];
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                Components\Split::make([
                                    Components\ImageEntry::make('profil')
                                        ->circular()
                                        ->hiddenLabel()
                                        ->grow(false),
                                    Components\Grid::make(2)
                                        ->schema([
                                            Components\Group::make([
                                                Components\TextEntry::make('nisn'),
                                                Components\TextEntry::make('nama'),
                                                Components\TextEntry::make('kelamin'),
                                                Components\TextEntry::make('tanggal_lahir'),

                                            ])
                                            ->inlineLabel()
                                            ->columns(1),

                                            Components\Group::make([
                                                Components\TextEntry::make('agama'),
                                                Components\TextEntry::make('kontak'),
                                                Components\TextEntry::make('status')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'accept' => 'success',
                                                    'off' => 'danger',
                                                    'grade' => 'success',
                                                    'move' => 'warning',
                                                    'wait' => 'gray'
                                                }),
                                                Components\ViewEntry::make('QRCode')
                                                ->view('filament.resources.siswa.qrcode'),
                                            ])
                                            ->inlineLabel()
                                            ->columns(1),
                                    ])

                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }
}
