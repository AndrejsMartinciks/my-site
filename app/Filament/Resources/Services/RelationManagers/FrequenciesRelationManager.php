<?php

namespace App\Filament\Resources\Services\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FrequenciesRelationManager extends RelationManager
{
    protected static string $relationship = 'frequencies';

    protected static ?string $title = 'Frequencies';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->pricing_mode === 'frequency';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Frequency plans';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Frequency name')
                ->required()
                ->maxLength(255)
                ->helperText('Например: Varje vecka, Varannan vecka, Var fjärde vecka'),

            Toggle::make('is_active')
                ->label('Aktiv')
                ->default(true),

            TextInput::make('sort_order')
                ->label('Sortering')
                ->numeric()
                ->required()
                ->default(0),

            Repeater::make('priceRanges')
                ->label('Price ranges for this frequency')
                ->relationship()
                ->defaultItems(0)
                ->reorderable(false)
                ->schema([
                    TextInput::make('min_sqm')
                        ->label('Min sqm')
                        ->numeric()
                        ->required(),

                    TextInput::make('max_sqm')
                        ->label('Max sqm')
                        ->numeric()
                        ->required(),

                    TextInput::make('price')
                        ->label('Pris')
                        ->numeric()
                        ->required(),

                    TextInput::make('sort_order')
                        ->label('Sortering')
                        ->numeric()
                        ->required()
                        ->default(0),
                ])
                ->columnSpanFull()
                ->helperText('Эти диапазоны используются только для frequency-услуг.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Frequency')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Sortering')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Uppdaterad')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}