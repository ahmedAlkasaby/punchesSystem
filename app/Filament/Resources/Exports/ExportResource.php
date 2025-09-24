<?php

namespace App\Filament\Resources\Exports;

use App\Filament\Resources\Exports\Pages\CreateExport;
use App\Filament\Resources\Exports\Pages\EditExport;
use App\Filament\Resources\Exports\Pages\ListExports;
use App\Filament\Resources\Exports\Schemas\ExportForm;
use App\Filament\Resources\Exports\Tables\ExportsTable;
use App\Models\Export;
use BackedEnum;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExportResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Export::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


      public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
        ];
    }
    

    public static function getNavigationLabel(): string
    {
        return __('site.exports');
    }
    
    public static function getModelLabel(): string
    {
        return __('site.export');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('site.exports');
    }


    public static function form(Schema $schema): Schema
    {
        return ExportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExports::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
