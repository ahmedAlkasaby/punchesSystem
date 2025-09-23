<?php

namespace App\Filament\Resources\DailyReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DailyReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('first_in'),
                TimePicker::make('last_out'),
                TextInput::make('total_seconds')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_hours')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('day_status')
                    ->options(['present' => 'Present', 'absent' => 'Absent'])
                    ->default('absent')
                    ->required(),
                TextInput::make('exception_id')
                    ->numeric(),
                TextInput::make('exception_type'),
                Toggle::make('is_late'),
                TextInput::make('late_seconds')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_early_leave'),
                Toggle::make('is_under_hours'),
                Toggle::make('has_missing_in')
                    ->required(),
                Toggle::make('has_missing_out')
                    ->required(),
                Toggle::make('flagged_in'),
                Toggle::make('flagged_out'),
                TextInput::make('notes'),
                DateTimePicker::make('computed_at'),
            ]);
    }
}
