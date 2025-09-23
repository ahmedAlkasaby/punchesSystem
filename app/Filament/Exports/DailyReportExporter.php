<?php

namespace App\Filament\Exports;

use App\Models\DailyReport;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DailyReportExporter extends Exporter
{
    protected static ?string $model = DailyReport::class;

    public static function getColumns(): array
{
    // helper صغير لتحويل ثواني -> "H:MM"
    $secondsToHourMin = fn($s) => ($s === null || $s === '') ? '' : sprintf('%d:%02d', (int)floor($s / 3600), (int)floor(($s % 3600) / 60));

    return [
        ExportColumn::make('id')->label('الرقم المميز'),

        // relation: employee name (fall back to raw state)
        ExportColumn::make('employee.name')
            ->label('الموظف')
            ->formatStateUsing(fn($state, $record) => $record?->employee?->name ?? $state ?? ''),

        ExportColumn::make('date')
            ->label('التاريخ')
            ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('Y-m-d') : ''),

        ExportColumn::make('first_in')
            ->label('أول حضور')
            ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : ''),

        ExportColumn::make('last_out')
            ->label('آخر انصراف')
            ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('H:i') : ''),

        ExportColumn::make('total_seconds')
            ->label('إجمالي الثواني'),

        ExportColumn::make('total_hours')
            ->label('إجمالي الساعات')
            ->formatStateUsing(fn($state) => $state !== null && $state !== '' ? number_format((float)$state, 2) : ''),

        ExportColumn::make('day_status')
            ->label('حالة اليوم')
            ->formatStateUsing(fn($s) => match($s) {
                'present' => 'حاضر',
                'absent' => 'غائب',
                default => (string) $s,
            }),

        ExportColumn::make('exception.name')
            ->label('الاستثناء')
            ->formatStateUsing(fn($state, $record) => $record?->exception?->name ?? $state ?? ''),

        ExportColumn::make('exception_type')
            ->label('نوع الاستثناء'),

        ExportColumn::make('is_late')
            ->label('تأخير')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('late_seconds')
            ->label('مدة التأخير (س:د)')
            ->formatStateUsing(fn($s) => $secondsToHourMin($s)),

        ExportColumn::make('is_early_leave')
            ->label('انصراف مبكر')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('is_under_hours')
            ->label('أقل من الساعات المطلوبة')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('has_missing_in')
            ->label('غياب بصمة دخول')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('has_missing_out')
            ->label('غياب بصمة خروج')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('flagged_in')
            ->label('ملاحظة على الدخول')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('flagged_out')
            ->label('ملاحظة على الانصراف')
            ->formatStateUsing(fn($s) => $s ? 'نعم' : 'لا'),

        ExportColumn::make('notes')
            ->label('ملاحظات')
            ->formatStateUsing(fn($s) => is_array($s) ? implode('; ', $s) : ($s ?? '')),

        ExportColumn::make('computed_at')
            ->label('تاريخ الحساب')
            ->formatStateUsing(fn($s) => $s ? Carbon::parse($s)->format('Y-m-d H:i') : ''),
    ];
}
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('site.export_completed') . Number::format($export->successful_rows) . ' ' . trans_choice('site.row', $export->successful_rows) . __('site.exported');

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . trans_choice('site.row', $failedRowsCount) . __('site.export_failed');
        }

        return $body;
    }
}
