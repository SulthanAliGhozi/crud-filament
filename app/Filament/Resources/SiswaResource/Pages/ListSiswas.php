<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return "Siswa";
    }

    public function getTabs(): array
    {
        // Tab::make()
        //  ->icon('heroicon-m-user-group')
        //  ->iconPosition(IconPosition::After);
        return [
            'all' => Tab::make(),
            'accept' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('Status', 'accept')),
            'off' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('Status', 'off')),
        ];
    }
}
