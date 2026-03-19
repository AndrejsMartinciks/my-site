<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class WindowCleaningBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('WindowCleaningBookingTabs')
                ->persistTab()
                ->id('window-cleaning-booking-tabs')
                ->tabs([
                    Tab::make('Kund')
                        ->schema([
                            Section::make('Kunduppgifter')
                                ->schema([
                                    TextInput::make('customer_name')->label('Namn')->disabled()->dehydrated(false),
                                    TextInput::make('email')->label('E-post')->disabled()->dehydrated(false),
                                    TextInput::make('phone')->label('Telefon')->disabled()->dehydrated(false),
                                    TextInput::make('personnummer_last4')->label('Personnummer sista 4')->disabled()->dehydrated(false),
                                    TextInput::make('address')->label('Adress')->disabled()->dehydrated(false),
                                    TextInput::make('postcode')->label('Postnummer')->disabled()->dehydrated(false),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Bokning')
                        ->schema([
                            Section::make('Bokningsdetaljer')
                                ->schema([
                                    TextInput::make('booking_date')->label('Datum')->disabled()->dehydrated(false),
                                    TextInput::make('time_from')->label('Tid från')->disabled()->dehydrated(false),
                                    TextInput::make('time_to')->label('Tid till')->disabled()->dehydrated(false),
                                    TextInput::make('booking_slot_id')->label('Booking slot ID')->disabled()->dehydrated(false),
                                    TextInput::make('window_count')->label('Antal fönster')->disabled()->dehydrated(false),
                                    TextInput::make('cleaning_scope')->label('Typ av putsning')->disabled()->dehydrated(false),
                                    TextInput::make('quoted_price')->label('Offertpris')->suffix(' kr')->disabled()->dehydrated(false),
                                    TextInput::make('sqm')->label('Lagrad mängd (sqm-fältet)')->disabled()->dehydrated(false),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Beräkning')
                        ->schema([
                            Section::make('Valda tillägg')
                                ->schema([
                                    Textarea::make('addons')
                                        ->label('Tillägg JSON')
                                        ->rows(10)
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Calculator summary')
                                ->schema([
                                    Textarea::make('calculator_summary')
                                        ->label('Calculator summary')
                                        ->rows(14)
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tab::make('Status')
                        ->schema([
                            Section::make('Bokningsstatus')
                                ->schema([
                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'new' => 'Ny',
                                            'contacted' => 'Kontaktad',
                                            'confirmed' => 'Bekräftad',
                                            'in_progress' => 'Pågående',
                                            'done' => 'Slutförd',
                                            'cancelled' => 'Avbokad',
                                        ])
                                        ->required()
                                        ->native(false),

                                    Textarea::make('manager_note')
                                        ->label('Intern anteckning')
                                        ->rows(8)
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}