<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WindowCleaningBookingResource\Pages\EditWindowCleaningBooking;
use App\Filament\Resources\WindowCleaningBookingResource\Pages\ListWindowCleaningBookings;
use App\Filament\Resources\WindowCleaningBookingResource\Schemas\WindowCleaningBookingForm;
use App\Filament\Resources\WindowCleaningBookingResource\Tables\WindowCleaningBookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WindowCleaningBookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|UnitEnum|null $navigationGroup = 'Bookings';
    protected static ?string $navigationLabel = 'Fönsterputs bokningar';
    protected static ?string $modelLabel = 'Fönsterputs bokning';
    protected static ?string $pluralModelLabel = 'Fönsterputs bokningar';
    protected static ?string $recordTitleAttribute = 'customer_name';
    protected static ?int $navigationSort = 20;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'window-cleaning-bookings';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('booking_type', 'window_cleaning');
    }

    public static function form(Schema $schema): Schema
    {
        return WindowCleaningBookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WindowCleaningBookingsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWindowCleaningBookings::route('/'),
            'edit' => EditWindowCleaningBooking::route('/{record}/edit'),
        ];
    }
}