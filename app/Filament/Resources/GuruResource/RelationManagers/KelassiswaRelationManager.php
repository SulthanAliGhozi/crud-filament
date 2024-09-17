<?php

namespace App\Filament\Resources\GuruResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Periode;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\FormsComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Factories\Relationship;

use function Laravel\Prompts\select;

class KelassiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'kelassiswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kelas_id')
                    ->options(Kelas::all()->pluck('kelas', 'id'))
                    ->relationship('kelas', 'kelas')
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('kelas')
                            ->label('Add Periode')
                            ->required(),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Add Kelas')
                            ->modalButton('Add Kelas')
                            ->modalWidth('2xl');
                    }),
                select::make('periode_id')
                    ->options(Periode::all()->pluck('periode', 'id'))
                    ->searchable()
                    ->relationship('periode', 'periode')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('Periode')
                            ->label('Add Periode')
                            ->required(),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Add Periode')
                            ->modalButton('Add Periode')
                            ->modalWidth('2xl');
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Nama')
            ->columns([
                Tables\Columns\TextColumn::make('kelas.kelas'),
                Tables\Columns\TextColumn::make('periode.periode'),
                ToggleColumn::make('is_open')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
