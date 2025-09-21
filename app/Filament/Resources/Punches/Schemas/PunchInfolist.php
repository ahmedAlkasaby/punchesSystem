<?php

namespace App\Filament\Resources\Punches\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;


class PunchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label(__('site.employee')),

                TextEntry::make('type')
                    ->label(__('site.type'))
                    ->badge()
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ])
                    ->formatStateUsing(fn($state) => $state === 'in' ? __('site.in') : __('site.out')),

                IconEntry::make('is_late')
                    ->label(__('site.late'))
                    ->boolean(),
                TextEntry::make('late_formatted')
                    ->label(__('site.late_seconds'))
                    ->numeric(),
                IconEntry::make('is_early_leave')
                    ->label(__('site.early_leave'))
                    ->boolean(),
                TextEntry::make('early_leave_formatted')
                    ->label(__('site.early_leave_seconds'))
                    ->numeric(),
                IconEntry::make('is_out_of_radius')
                    ->label(__('site.out_redis'))
                    ->boolean(),
                IconEntry::make('approved')
                    ->boolean(),

                TextEntry::make('location.name')
                    ->label(__('site.location'))
                    ->getStateUsing(fn($record) => $record->location?->nameLang()),

                TextEntry::make('address')
                    ->label(__('site.address'))
                    ->url(fn($record) => "https://www.google.com/maps?q={$record->latitude},{$record->longitude}")
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-map-pin')
                    ->iconPosition(IconPosition::Before) 
                    ->formatStateUsing(fn($state) => $state ?: __('site.no_address')),
                TextEntry::make('device_info')
                    ->label(__('site.device_info')),
                TextEntry::make('distance_formatted')
                    ->label(__('site.distance_from_location'))
                    ->numeric(),
                TextEntry::make('punched_at')
                    ->label(__('site.punched_at'))
                    ->dateTime(),

            ]);
    }
}
