<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->description('Basic customer details and contact information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('John Doe'),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->placeholder('09XXXXXXXXX')
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('customer@example.com'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Account Status')
                    ->description('Account verification and status settings.')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'blocked' => 'Blocked',
                            ])
                            ->default('active')
                            ->required()
                            ->helperText('Blocked customers will not be able to place orders.'),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->helperText('Leave empty if the email has not been verified.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Security')
                    ->description('Update customer login credentials if needed.')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->helperText('Leave blank to keep the current password.')
                            ->dehydrated(fn($state) => filled($state)),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
