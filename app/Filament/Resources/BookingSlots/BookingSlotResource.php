<?php

namespace App\Filament\Resources\BookingSlots;

use App\Filament\Resources\BookingSlots\Pages\CreateBookingSlot;
use App\Filament\Resources\BookingSlots\Pages\EditBookingSlot;
use App\Filament\Resources\BookingSlots\Pages\ListBookingSlots;
use App\Models\BookingSlot;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class BookingSlotResource extends Resource
{
    protected static ?string $model = BookingSlot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'booking_date';

    protected static ?string $navigationLabel = 'Booking Slots';

    protected static ?string $pluralModelLabel = 'Booking Slots';

    protected static ?string $modelLabel = 'Booking Slot';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('service_id')
                    ->label('Tjänst')
                    ->options(fn () => Service::query()->orderBy('sort_order')->pluck('name', 'id')->all())
                    ->default(fn () => Service::query()->where('slug', 'engangsstadning')->value('id'))
                    ->searchable()
                    ->required(),

                DatePicker::make('booking_date')
                    ->label('Datum')
                    ->required(),

                TimePicker::make('time_from')
                    ->label('Från')
                    ->seconds(false)
                    ->required(),

                TimePicker::make('time_to')
                    ->label('Till')
                    ->seconds(false)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),

                Toggle::make('is_booked')
                    ->label('Bokad')
                    ->default(false),

                TextInput::make('sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Textarea::make('internal_note')
                    ->label('Intern anteckning')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('booking_date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Tjänst')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Datum')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_from')
                    ->label('Från')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_to')
                    ->label('Till')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_booked')
                    ->label('Bokad')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable(),

                Tables\Columns\TextColumn::make('internal_note')
                    ->label('Anteckning')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Tjänst')
                    ->options(fn () => Service::query()->orderBy('sort_order')->pluck('name', 'id')->all()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktiv'),

                Tables\Filters\TernaryFilter::make('is_booked')
                    ->label('Bokad'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingSlots::route('/'),
            'create' => CreateBookingSlot::route('/create'),
            'edit' => EditBookingSlot::route('/{record}/edit'),
        ];
    }
}