<?php

namespace App\Filament\Resources\AdjancencyResource\Pages;

use App\Filament\Resources\AdjancencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdjancencies extends ListRecords
{
    protected static string $resource = AdjancencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
