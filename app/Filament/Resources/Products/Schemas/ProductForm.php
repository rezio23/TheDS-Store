<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('tags'),
                TextInput::make('rating'),
                TextInput::make('badge'),
                FileUpload::make('image')
                    ->image(),
                Textarea::make('gallery')
                    ->columnSpanFull(),
                TextInput::make('category'),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
