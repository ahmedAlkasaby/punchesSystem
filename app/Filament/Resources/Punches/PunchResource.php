<?php

namespace App\Filament\Resources\Punches;

use App\Filament\Resources\Punches\Pages\CreatePunch;
use App\Filament\Resources\Punches\Pages\EditPunch;
use App\Filament\Resources\Punches\Pages\ListPunches;
use App\Filament\Resources\Punches\Pages\ViewPunch;
use App\Filament\Resources\Punches\Schemas\PunchForm;
use App\Filament\Resources\Punches\Schemas\PunchInfolist;
use App\Filament\Resources\Punches\Tables\PunchesTable;
use App\Models\Punch;
use BackedEnum;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PunchResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Punch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
    

    public static function getNavigationLabel(): string
    {
        return __('site.punches');
    }
    
    public static function getModelLabel(): string
    {
        return __('site.punch');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('site.punches');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return PunchInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PunchesTable::configure($table);
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
            'index' => ListPunches::route('/'),
            'view' => ViewPunch::route('/{record}'),
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
