<?php

namespace App\Filament\Resources\Punches\Pages;

use App\Filament\Resources\Punches\PunchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPunches extends ListRecords
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
