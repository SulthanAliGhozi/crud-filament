<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required(),
                Forms\Components\TextInput::make('nisn')->required(),
                Forms\Components\TextInput::make('kelas')->required(),
                Forms\Components\TextArea::make('alamat')->required(),
                Forms\Components\Select::make('agama_id')
                ->relationship('agama', 'nama')
                ->required(),
                Forms\Components\Select::make('jurusan_id')
                ->relationship('jurusan', 'nama')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nisn')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelas')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alamat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('agama.nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama')->searchable()->sortable(),
                ])
            ->filters([
                Tables\Filters\SelectFilter::make('agama_id')
                ->relationship('agama', 'nama'),
                Tables\Filters\SelectFilter::make('jurusan_id')
                ->relationship('jurusan', 'nama')
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