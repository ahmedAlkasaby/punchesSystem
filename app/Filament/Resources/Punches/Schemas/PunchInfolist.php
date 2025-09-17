<?php

namespace App\Filament\Resources\Punches\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PunchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('type'),
                IconEntry::make('is_late')
                    ->boolean(),
                IconEntry::make('is_early_leave')
                    ->boolean(),
                TextEntry::make('late_seconds')
                    ->numeric(),
                TextEntry::make('early_leave_seconds')
                    ->numeric(),
                IconEntry::make('is_out_of_radius')
                    ->boolean(),
                IconEntry::make('approved')
                    ->boolean(),
                TextEntry::make('approved_by')
                    ->numeric(),
                TextEntry::make('approved_at')
                    ->dateTime(),
                TextEntry::make('location_id')
                    ->numeric(),
                TextEntry::make('latitude')
                    ->numeric(),
                TextEntry::make('longitude')
                    ->numeric(),
                TextEntry::make('address'),
                TextEntry::make('device_info'),
                TextEntry::make('distance_from_location')
                    ->numeric(),
                TextEntry::make('punched_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
