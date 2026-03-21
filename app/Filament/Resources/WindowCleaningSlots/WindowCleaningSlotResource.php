<?php

namespace App\Filament\Resources\WindowCleaningSlots;

use App\Filament\Resources\WindowCleaningSlots\Pages\CreateWindowCleaningSlot;
use App\Filament\Resources\WindowCleaningSlots\Pages\EditWindowCleaningSlot;
use App\Filament\Resources\WindowCleaningSlots\Pages\ListWindowCleaningSlots;
use App\Models\BookingSlot;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WindowCleaningSlotResource extends Resource
{
    protected static ?string $model = BookingSlot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;
    protected static string|\UnitEnum|null $navigationGroup = 'Booking Management';

    protected static ?string $navigationLabel = 'Fönsterputsning Slots';
    protected static ?string $pluralModelLabel = 'Fönsterputsning Slots';
    protected static ?string $modelLabel = 'Fönsterputsning Slot';
    protected static ?int $navigationSort = 62;
    protected static ?string $recordTitleAttribute = 'booking_date';

    protected static function getServiceId(): ?int
    {
        return Service::query()->where('slug', 'fonsterputsning')->value('id');
    }

    public static function getEloquentQuery(): Builder
    {
        $serviceId = static::getServiceId() ?? 0;

        return parent::getEloquentQuery()->where('service_id', $serviceId);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Hidden::make('service_id')
                    ->default(fn () => static::getServiceId())
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
            'index' => ListWindowCleaningSlots::route('/'),
            'create' => CreateWindowCleaningSlot::route('/create'),
            'edit' => EditWindowCleaningSlot::route('/{record}/edit'),
        ];
    }
}