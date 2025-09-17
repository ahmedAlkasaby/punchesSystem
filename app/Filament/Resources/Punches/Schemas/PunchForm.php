<?php

namespace App\Filament\Resources\Punches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PunchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['in' => 'In', 'out' => 'Out'])
                    ->required(),
                Toggle::make('is_late'),
                Toggle::make('is_early_leave'),
                TextInput::make('late_seconds')
                    ->numeric(),
                TextInput::make('early_leave_seconds')
                    ->numeric(),
                Toggle::make('is_out_of_radius')
                    ->required(),
                Toggle::make('approved')
                    ->required(),
                TextInput::make('approved_by')
                    ->numeric(),
                DateTimePicker::make('approved_at'),
                TextInput::make('location_id')
                    ->numeric(),
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('device_info'),
                TextInput::make('distance_from_location')
                    ->numeric(),
                Textarea::make('note')
                    ->columnSpanFull(),
                DateTimePicker::make('punched_at')
                    ->required(),
            ]);
    }
}
