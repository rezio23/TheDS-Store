<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

class UserForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password_hash')
                    ->password()
                    ->required(),
                TextInput::make('password')
                    ->password(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('gender')
                    ->default('Hidden'),
                TextInput::make('avatar'),
                Textarea::make('address')
                    ->columnSpanFull(),
            ]);
    }
}
