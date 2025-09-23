<?php

namespace App\Filament\Resources\DailyReports\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DailyReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee_id')
                    ->numeric(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('first_in')
                    ->time(),
                TextEntry::make('last_out')
                    ->time(),
                TextEntry::make('total_seconds')
                    ->numeric(),
                TextEntry::make('total_hours')
                    ->numeric(),
                TextEntry::make('day_status'),
                TextEntry::make('exception_id')
                    ->numeric(),
                TextEntry::make('exception_type'),
                IconEntry::make('is_late')
                    ->boolean(),
                TextEntry::make('late_seconds')
                    ->numeric(),
                IconEntry::make('is_early_leave')
                    ->boolean(),
                IconEntry::make('is_under_hours')
                    ->boolean(),
                IconEntry::make('has_missing_in')
                    ->boolean(),
                IconEntry::make('has_missing_out')
                    ->boolean(),
                IconEntry::make('flagged_in')
                    ->boolean(),
                IconEntry::make('flagged_out')
                    ->boolean(),
                TextEntry::make('computed_at')
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
