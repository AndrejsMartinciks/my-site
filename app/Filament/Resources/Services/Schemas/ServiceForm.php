<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Namn')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->helperText('Например: fonsterputsning, hemstadning, flyttstadning'),

            Textarea::make('description')
                ->label('Beskrivning')
                ->rows(4)
                ->columnSpanFull(),

            Select::make('pricing_mode')
                ->label('Prislogik')
                ->options([
                    'fixed' => 'Fixed / direkta prisintervall',
                    'frequency' => 'Frequency / pris per frekvens',
                ])
                ->required()
                ->default('fixed')
                ->helperText('Fönsterputsning использует fixed + direct price ranges.'),

            Toggle::make('is_active')
                ->label('Aktiv')
                ->default(true),

            TextInput::make('sort_order')
                ->label('Sortering')
                ->numeric()
                ->required()
                ->default(0),
        ]);
    }
}