<?php

namespace App\Filament\Widgets;

use App\Models\Jurusan;
use App\Models\Siswa;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use League\CommonMark\Extension\DescriptionList\Node\DescriptionList;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', Siswa::count())
            ->description('Total Siswa Tahun Ajaran 2022')
            ->descriptionIcon('heroicon-o-user', IconPosition::Before)
            ->color('success')
            ->Chart([1,2,3,10,20,4,6,2,8]),
            Stat::make('Total Total Jurusan Tahun Ini', Jurusan::count())
            ->description('Total Jurusan Tahun Ajaran 2022')
            ->descriptionIcon('heroicon-o-academic-cap', IconPosition::Before)
            ->color('success')
            ->Chart([0,20,0,50,0,8,0,1]),
        ];
    }
}
