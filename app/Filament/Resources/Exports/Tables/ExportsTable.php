<?php

namespace App\Filament\Resources\Exports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ExportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
              ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->label('المستخدم'),
                TextColumn::make('exporter')->label('نوع التصدير'),
                TextColumn::make('file_name')
                ->label('الملف')
    ->url(fn($record) => route('exports.download', $record))
                // ->openUrlInNewTab()

                ->formatStateUsing(fn($state) => $state ? '📂 تحميل' : '-'),


                TextColumn::make('total_rows')->label('عدد الصفوف'),
                TextColumn::make('successful_rows')->label('الناجحة'),
                TextColumn::make('processed_rows')->label('المعالجة'),
                TextColumn::make('completed_at')->label('اكتمل عند')->dateTime(),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
