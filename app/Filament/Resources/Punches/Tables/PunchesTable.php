<?php

namespace App\Filament\Resources\Punches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PunchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('site.employee'))
                    ->sortable()
                    ->searchable(),
                    TextColumn::make('type')
                    ->label(__('site.type'))
                    ->formatStateUsing(function ($state) {
                        return $state === 'in'
                            ? '⬇️ ' . __('site.in')   // دخول
                            : '⬆️ ' . __('site.out'); // خروج
                    })
                    ->badge()
                    ->colors([
                        'success' => 'in',   // أخضر لو in
                        'danger'  => 'out',  // أحمر لو out
                    ]),
                 TextColumn::make('location.name')
                    ->label(__('site.location'))
                    ->getStateUsing(fn($record) => $record->location?->nameLang())
                    ->toggleable(),


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
              
                TextColumn::make('early_leave_formatted')
                    ->label(__('site.early_leave_seconds'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_out_of_radius')
                    ->label(__('site.out_redis'))
                    ->boolean(),
                IconColumn::make('approved')
                    ->label(__('site.approved'))
                    ->boolean(),
              
                TextColumn::make('punched_at')
                    ->label(__('site.punched_at'))
                    ->dateTime() 
                    ->sortable(),
              
            ])
            ->filters([
                Filter::make('punched_at')
                ->form([
                    DatePicker::make('date')
                        ->label(__('site.date')),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['date'], fn ($q, $date) =>
                            $q->whereDate('punched_at', $date)
                        );
                }),
                 Filter::make('punched_at_range')
                ->form([
                    DatePicker::make('from')->label(__('site.from_date')),
                    DatePicker::make('until')->label(__('site.to_date')),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q, $date) =>
                            $q->whereDate('punched_at', '>=', $date)
                        )
                        ->when($data['until'], fn ($q, $date) =>
                            $q->whereDate('punched_at', '<=', $date)
                        );
                }),
                SelectFilter::make('employee')
                    ->label(__('site.employee'))
                    ->relationship(
                        name: 'employee',
                        titleAttribute: 'name', 
                    )
                    ->searchable()
                    ->preload()

            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
