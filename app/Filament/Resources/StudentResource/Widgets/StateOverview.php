<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StateOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Student', Student::all()->count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Card::make('Student Accept', Student::where('status', 'accept')->count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Card::make('Student Off', Student::where('status', 'of')->count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Card::make('User', User::all()->count()),
            Card::make('Teacher', Teacher::all()->count()),
            Card::make('Subject', Subject::all()->count()),
        ];
    }
}
