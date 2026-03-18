<?php

namespace App\Filament\Resources\Services\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PriceRangesRelationManager extends RelationManager
{
    protected static string $relationship = 'priceRanges';

    protected static ?string $title = 'Price ranges';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->pricing_mode === 'fixed';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return $ownerRecord->slug === 'fonsterputsning'
            ? 'Window price ranges'
            : 'Price ranges';
    }

    public function form(Schema $schema): Schema
    {
        $ownerRecord = $this->getOwnerRecord();
        $isWindowCleaning = $ownerRecord?->slug === 'fonsterputsning';

        $minLabel = $isWindowCleaning ? 'Min windows' : 'Min sqm';
        $maxLabel = $isWindowCleaning ? 'Max windows' : 'Max sqm';
        $minHelper = $isWindowCleaning
            ? 'Минимальное количество окон для этого диапазона.'
            : 'Минимальная площадь для этого диапазона.';
        $maxHelper = $isWindowCleaning
            ? 'Максимальное количество окон для этого диапазона.'
            : 'Максимальная площадь для этого диапазона.';
        $priceHelper = $isWindowCleaning
            ? 'Цена для диапазона окон.'
            : 'Цена для этого диапазона площади.';

        return $schema->components([
            TextInput::make('min_sqm')
                ->label($minLabel)
                ->numeric()
                ->required()
                ->helperText($minHelper),

            TextInput::make('max_sqm')
                ->label($maxLabel)
                ->numeric()
                ->required()
                ->helperText($maxHelper),

            TextInput::make('price')
                ->label('Pris')
                ->numeric()
                ->required()
                ->helperText($priceHelper),

            TextInput::make('sort_order')
                ->label('Sortering')
                ->numeric()
                ->required()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        $ownerRecord = $this->getOwnerRecord();
        $isWindowCleaning = $ownerRecord?->slug === 'fonsterputsning';

        $minLabel = $isWindowCleaning ? 'Min windows' : 'Min sqm';
        $maxLabel = $isWindowCleaning ? 'Max windows' : 'Max sqm';

        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('min_sqm')
                    ->label($minLabel)
                    ->sortable(),

                TextColumn::make('max_sqm')
                    ->label($maxLabel)
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Pris')
                    ->suffix(' kr')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Sortering')
                    ->sortable(),
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