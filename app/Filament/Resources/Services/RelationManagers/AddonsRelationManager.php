<?php

namespace App\Filament\Resources\Services\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AddonsRelationManager extends RelationManager
{
    protected static string $relationship = 'addons';

    protected static ?string $title = 'Addons';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return $ownerRecord->slug === 'fonsterputsning'
            ? 'Window addons'
            : 'Addons';
    }

    public function form(Schema $schema): Schema
    {
        $ownerRecord = $this->getOwnerRecord();
        $isWindowCleaning = $ownerRecord?->slug === 'fonsterputsning';

        return $schema->components([
            TextInput::make('name')
                ->label($isWindowCleaning ? 'Addon name' : 'Namn')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->label($isWindowCleaning ? 'Addon price' : 'Pris')
                ->numeric()
                ->required()
                ->default(0),

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

    public function table(Table $table): Table
    {
        $ownerRecord = $this->getOwnerRecord();
        $isWindowCleaning = $ownerRecord?->slug === 'fonsterputsning';

        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label($isWindowCleaning ? 'Addon' : 'Namn')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Pris')
                    ->suffix(' kr')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

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