<?php

namespace App\Filament\Resources\DailyReports\Tables;

use App\Filament\Exports\DailyReportExporter;
use App\Models\DailyReport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DailyReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('site.employee'))
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('site.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('first_in')
                    ->label(__('site.first_in'))
                    ->time()
                    ->sortable(),
                TextColumn::make('last_out')
                    ->label(__('site.last_out'))
                    ->time()
                    ->sortable(),
              
                TextColumn::make('total_hours')
                    ->label(__('site.total_hours'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('day_status')
                    ->label(__('site.day_status'))
                   ,
                TextColumn::make('exception.name')
                    ->label(__('site.exception'))
                    ->getStateUsing(fn($record) => $record->exception?->nameLang())
                    ->sortable(),
                TextColumn::make('exception_type')
                    ->label(__('site.exception_type'))
                    ->searchable(),
                  IconColumn::make('is_late')
                    ->label(__('site.late'))
                    ->boolean(),
                TextColumn::make('late_formatted')
                    ->label(__('site.late_seconds'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_early_leave')
                    ->label(__('site.early_leave'))
                    ->boolean(),
                IconColumn::make('is_under_hours')
                    ->label(__('site.under_hours'))
                    ->boolean(),
                IconColumn::make('has_missing_in')
                    ->label(__('site.missing_in'))
                    ->boolean(),
                IconColumn::make('has_missing_out')
                    ->label(__('site.missing_out'))
                    ->boolean(),
                IconColumn::make('flagged_in')
                    ->label(__('site.flagged_in'))
                    ->boolean(),
                IconColumn::make('flagged_out')
                    ->label(__('site.flagged_out'))
                    ->boolean(),
                TextColumn::make('computed_at')
                    ->label(__('site.computed_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(DailyReportExporter::class)
                    ->authorize(fn () => auth()->user()->can('export_daily::reports::daily::report')),


            ]) 
            ->filters([
               
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                         ->exporter(DailyReportExporter::class)
                        ->authorize(fn () => auth()->user()->can('export_daily::reports::daily::report')),]),
            ]);
    }
}
