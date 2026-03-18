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
                    Tab::make('Customer')
                        ->schema([
                            Section::make('Customer details')
                                ->schema([
                                    TextInput::make('customer_name')
                                        ->label('Customer name')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('email')
                                        ->label('Email')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('phone')
                                        ->label('Phone')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('personnummer_last4')
                                        ->label('Personnummer last 4')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('address')
                                        ->label('Address')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('postcode')
                                        ->label('Postcode')
                                        ->disabled()
                                        ->dehydrated(false),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Booking')
                        ->schema([
                            Section::make('Booking details')
                                ->schema([
                                    TextInput::make('booking_date')
                                        ->label('Booking date')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('time_from')
                                        ->label('Time from')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('time_to')
                                        ->label('Time to')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('window_count')
                                        ->label('Window count')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('cleaning_scope')
                                        ->label('Cleaning scope')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('sqm')
                                        ->label('Stored sqm field')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('quoted_price')
                                        ->label('Quoted price')
                                        ->suffix(' kr')
                                        ->disabled()
                                        ->dehydrated(false),
                                ])
                                ->columns(2),
                        ]),

                    Tab::make('Calculation')
                        ->schema([
                            Section::make('Selected addons')
                                ->schema([
                                    Textarea::make('addons')
                                        ->label('Addons JSON')
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
                            Section::make('Booking status')
                                ->schema([
                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'new' => 'New',
                                            'contacted' => 'Contacted',
                                            'confirmed' => 'Confirmed',
                                            'done' => 'Done',
                                            'cancelled' => 'Cancelled',
                                        ])
                                        ->required()
                                        ->native(false),

                                    Textarea::make('manager_note')
                                        ->label('Manager note')
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