<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->required(),
                TextInput::make('phone_primary')
                    ->tel()
                    ->default(null),
                TextInput::make('phone_secondary')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('postal_code')
                    ->default(null),
                TextInput::make('city')
                    ->default(null),
                TextInput::make('org_number')
                    ->default(null),
                TextInput::make('hero_eyebrow')
                    ->default(null),
                TextInput::make('hero_title')
                    ->default(null),
                Textarea::make('hero_text')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('hero_primary_button_text')
                    ->default(null),
                TextInput::make('hero_secondary_button_text')
                    ->default(null),
                TextInput::make('seo_title')
                    ->default(null),
                Textarea::make('seo_description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
