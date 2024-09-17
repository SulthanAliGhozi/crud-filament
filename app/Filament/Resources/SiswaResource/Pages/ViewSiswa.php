<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Models\Siswa;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SiswaResource;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected static string $view = 'filament.resources.siswa.view';

    public function getData()
    {
        $result = Siswa::find($this->record->id);
    }
}