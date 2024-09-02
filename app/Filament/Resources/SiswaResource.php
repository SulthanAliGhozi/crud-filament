<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SiswaResource\RelationManagers;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $navigationGroup = 'Siswa';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required(),
                        Forms\Components\TextInput::make('nisn')->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->required(),
                        Forms\Components\TextInput::make('kontak')->label('No Telp')->required(),
                        Forms\Components\Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Khonghucu' => 'Khonghucu',
                            ])
                            ->required(),
                        Forms\Components\Select::make('kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\Select::make('kelas')
                            ->options([
                            'X' => 'X',
                            'XI' => 'XI',
                            'XII' => 'XII',
                            ])
                            ->required(),
                        Forms\Components\Select::make('jurusan_id')
                            ->relationship('jurusan', 'nama')
                            ->required(),
                        Forms\Components\TextArea::make('alamat')->required(),
                        Forms\Components\FileUpload::make('profil')
                            ->directory('siswas')
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
                Tables\Columns\TextColumn::make('nisn')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('agama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelamin')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alamat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kontak')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelas')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama')->searchable()->sortable(),
                ImageColumn::make('profil')
                ->extraImgAttributes(['loading' => 'lazy']),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jurusan_id')
                    ->relationship('jurusan', 'nama'),
            ])
            ->actions([
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}