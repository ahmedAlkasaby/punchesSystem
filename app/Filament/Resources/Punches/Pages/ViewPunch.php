<?php

namespace App\Filament\Resources\Punches\Pages;

use App\Filament\Resources\Punches\PunchResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPunch extends ViewRecord
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
