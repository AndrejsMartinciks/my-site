<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Filament\Resources\Bookings\Pages\ViewBooking;
use App\Filament\Resources\Bookings\Tables\BookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'customer_name';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $pluralModelLabel = 'Bookings';

    protected static ?string $modelLabel = 'Booking';

    protected static ?int $navigationSort = 70;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('status', 'new')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Nya bokningar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Infolists\Components\TextEntry::make('id')
                    ->label('DB ID'),

                Infolists\Components\TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'done' => 'success',
                        default => 'gray',
                    }),

                Infolists\Components\TextEntry::make('service.name')
                    ->label('Tjänst')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('bookingSlot.id')
                    ->label('Slot ID')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('booking_date')
                    ->label('Datum')
                    ->date('Y-m-d'),

                Infolists\Components\TextEntry::make('time_from')
                    ->label('Från')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—'),

                Infolists\Components\TextEntry::make('time_to')
                    ->label('Till')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—'),

                Infolists\Components\TextEntry::make('customer_name')
                    ->label('För & Efternamn'),

                Infolists\Components\TextEntry::make('personnummer')
                    ->label('Personnummer')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('personnummer_last4')
                    ->label('Personnummer last4')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('address')
                    ->label('Adress')
                    ->columnSpanFull(),

                Infolists\Components\TextEntry::make('phone')
                    ->label('Telefon'),

                Infolists\Components\TextEntry::make('email')
                    ->label('E-post'),

                Infolists\Components\TextEntry::make('sqm')
                    ->label('Kvadratmeter')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('quoted_price')
                    ->label('Quoted price')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('calculator_summary')
                    ->label('Prisberäkning från kalkylatorn')
                    ->columnSpanFull()
                    ->html()
                    ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?: '—'))),

                Infolists\Components\TextEntry::make('manager_note')
                    ->label('Manager note')
                    ->columnSpanFull()
                    ->html()
                    ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?: '—'))),

                Infolists\Components\TextEntry::make('created_at')
                    ->label('Skapad')
                    ->dateTime('Y-m-d H:i:s'),

                Infolists\Components\TextEntry::make('contacted_at')
                    ->label('Contacted at')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('done_at')
                    ->label('Done at')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('—'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'view' => ViewBooking::route('/{record}'),
        ];
    }
}