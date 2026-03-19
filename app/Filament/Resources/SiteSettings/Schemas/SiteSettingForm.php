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

                TextInput::make('hero_point_1')
                    ->default(null),

                TextInput::make('hero_point_2')
                    ->default(null),

                TextInput::make('hero_point_3')
                    ->default(null),

                TextInput::make('hero_badge_1')
                    ->default(null),

                TextInput::make('hero_badge_2')
                    ->default(null),

                TextInput::make('hero_badge_3')
                    ->default(null),

                TextInput::make('trust_eyebrow')
                    ->default(null),

                TextInput::make('trust_title')
                    ->default(null),

                TextInput::make('trust_item_1_title')
                    ->default(null),

                Textarea::make('trust_item_1_text')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('trust_item_2_title')
                    ->default(null),

                Textarea::make('trust_item_2_text')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('trust_item_3_title')
                    ->default(null),

                Textarea::make('trust_item_3_text')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('trust_item_4_title')
                    ->default(null),

                Textarea::make('trust_item_4_text')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('about_eyebrow')
                    ->default(null),

                TextInput::make('about_title')
                    ->default(null),

                Textarea::make('about_text_1')
                    ->default(null)
                    ->columnSpanFull(),

                Textarea::make('about_text_2')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('about_check_title')
                    ->default(null),

                TextInput::make('about_check_1')
                    ->default(null),

                TextInput::make('about_check_2')
                    ->default(null),

                TextInput::make('about_check_3')
                    ->default(null),

                TextInput::make('about_check_4')
                    ->default(null),

                TextInput::make('rut_eyebrow')
                    ->default(null),

                TextInput::make('rut_title')
                    ->default(null),

                Textarea::make('rut_text_1')
                    ->default(null)
                    ->columnSpanFull(),

                Textarea::make('rut_text_2')
                    ->default(null)
                    ->columnSpanFull(),

                Textarea::make('footer_text')
                    ->default(null)
                    ->columnSpanFull(),

                TextInput::make('seo_title')
                    ->default(null),

                Textarea::make('seo_description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}