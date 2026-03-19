<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Företagsuppgifter')
                ->schema([
                    TextInput::make('company_name')
                        ->label('Företagsnamn')
                        ->placeholder('Clean Source AB')
                        ->required(),

                    TextInput::make('phone_primary')
                        ->label('Primärt telefonnummer')
                        ->tel()
                        ->placeholder('070 741 37 72'),

                    TextInput::make('phone_secondary')
                        ->label('Sekundärt telefonnummer')
                        ->tel()
                        ->placeholder('08 838 538'),

                    TextInput::make('email')
                        ->label('E-post')
                        ->email()
                        ->placeholder('info@cleansource.se'),

                    TextInput::make('address')
                        ->label('Adress')
                        ->placeholder('Ångermannagatan 121'),

                    TextInput::make('postal_code')
                        ->label('Postnummer')
                        ->placeholder('162 64'),

                    TextInput::make('city')
                        ->label('Stad')
                        ->placeholder('Vällingby'),

                    TextInput::make('org_number')
                        ->label('Organisationsnummer')
                        ->placeholder('556988-2722'),

                    TextInput::make('bankgiro')
                        ->label('Bankgiro')
                        ->placeholder('540-1054'),

                    TextInput::make('swish')
                        ->label('Swish')
                        ->placeholder('1234591558'),
                ])
                ->columns(2),

            Section::make('Hero')
                ->schema([
                    TextInput::make('hero_eyebrow')
                        ->label('Eyebrow')
                        ->placeholder('Städning för hem & företag i Stockholm'),

                    TextInput::make('hero_title')
                        ->label('Huvudrubrik')
                        ->placeholder('Trygg och noggrann städservice med tydlig bokning.'),

                    Textarea::make('hero_text')
                        ->label('Ingress')
                        ->rows(4)
                        ->placeholder('Clean Source AB hjälper privatpersoner och företag i Stockholm...')
                        ->columnSpanFull(),

                    TextInput::make('hero_primary_button_text')
                        ->label('Primär knapptext')
                        ->placeholder('Räkna ut pris'),

                    TextInput::make('hero_secondary_button_text')
                        ->label('Sekundär knapptext')
                        ->placeholder('Begär offert'),
                ])
                ->columns(2),

            Section::make('Hero punkter')
                ->schema([
                    TextInput::make('hero_point_1')->label('Punkt 1')->placeholder('50% RUT-avdrag direkt på fakturan'),
                    TextInput::make('hero_point_2')->label('Punkt 2')->placeholder('Ansvarsförsäkring och kvalitetssäkring'),
                    TextInput::make('hero_point_3')->label('Punkt 3')->placeholder('Flexibla tider i hela Stockholm'),
                    TextInput::make('hero_badge_1')->label('Badge 1')->placeholder('För hem & företag'),
                    TextInput::make('hero_badge_2')->label('Badge 2')->placeholder('Tydliga priser'),
                    TextInput::make('hero_badge_3')->label('Badge 3')->placeholder('Snabb återkoppling'),
                ])
                ->columns(2),

            Section::make('Trust block')
                ->schema([
                    TextInput::make('trust_eyebrow')->label('Eyebrow')->placeholder('Därför väljer kunder oss'),
                    TextInput::make('trust_title')->label('Rubrik')->placeholder('En modern och trygg städpartner'),

                    TextInput::make('trust_item_1_title')->label('Kort 1 rubrik')->placeholder('Tryggt'),
                    Textarea::make('trust_item_1_text')->label('Kort 1 text')->rows(3)->placeholder('Vi arbetar med tydliga rutiner...'),

                    TextInput::make('trust_item_2_title')->label('Kort 2 rubrik')->placeholder('Kvalitet'),
                    Textarea::make('trust_item_2_text')->label('Kort 2 text')->rows(3)->placeholder('Vårt team arbetar noggrant...'),

                    TextInput::make('trust_item_3_title')->label('Kort 3 rubrik')->placeholder('Flexibelt'),
                    Textarea::make('trust_item_3_text')->label('Kort 3 text')->rows(3)->placeholder('Boka enstaka uppdrag eller återkommande...'),

                    TextInput::make('trust_item_4_title')->label('Kort 4 rubrik')->placeholder('RUT-avdrag'),
                    Textarea::make('trust_item_4_text')->label('Kort 4 text')->rows(3)->placeholder('Vi hjälper dig med korrekt underlag...'),
                ])
                ->columns(2),

            Section::make('About block')
                ->schema([
                    TextInput::make('about_eyebrow')->label('Eyebrow')->placeholder('Om Clean Source AB'),
                    TextInput::make('about_title')->label('Rubrik')->placeholder('Städning med fokus på kvalitet, trygghet och enkel kommunikation'),
                    Textarea::make('about_text_1')->label('Text 1')->rows(4)->columnSpanFull(),
                    Textarea::make('about_text_2')->label('Text 2')->rows(4)->columnSpanFull(),
                    TextInput::make('about_check_title')->label('Check-rubrik')->placeholder('Så arbetar vi'),
                    TextInput::make('about_check_1')->label('Check 1')->placeholder('Tydlig offert och snabb återkoppling'),
                    TextInput::make('about_check_2')->label('Check 2')->placeholder('Anpassade upplägg för privat & företag'),
                    TextInput::make('about_check_3')->label('Check 3')->placeholder('Noggrann planering inför varje uppdrag'),
                    TextInput::make('about_check_4')->label('Check 4')->placeholder('Enkel kontakt före, under och efter bokning'),
                ])
                ->columns(2),

            Section::make('RUT block')
                ->schema([
                    TextInput::make('rut_eyebrow')->label('Eyebrow')->placeholder('RUT-avdrag för privatpersoner'),
                    TextInput::make('rut_title')->label('Rubrik')->placeholder('Du betalar bara halva arbetskostnaden'),
                    Textarea::make('rut_text_1')->label('Text 1')->rows(4)->columnSpanFull(),
                    Textarea::make('rut_text_2')->label('Text 2')->rows(4)->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Footer & SEO')
                ->schema([
                    Textarea::make('footer_text')
                        ->label('Footer text')
                        ->rows(3)
                        ->placeholder('Professionell städservice i Stockholm för privatpersoner och företag.')
                        ->columnSpanFull(),

                    TextInput::make('seo_title')
                        ->label('SEO title')
                        ->placeholder('Clean Source AB | Städning i Stockholm'),

                    Textarea::make('seo_description')
                        ->label('SEO description')
                        ->rows(3)
                        ->placeholder('Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm.')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}