<?php

namespace App\Filament\Resources\ContactInfos\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactInfoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Address 1')
                    ->description('First store or office address shown on the contact page.')
                    ->schema([
                        TextInput::make('address_1_title')
                            ->label('Title')
                            ->maxLength(255)
                            ->placeholder('AliarLIE Store')
                            ->columnSpan(1),
                        Textarea::make('address_1_text')
                            ->label('Address')
                            ->rows(2)
                            ->placeholder('PO Box 16122 Collins Street West Victoria 8007 Australia')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Address 2')
                    ->description('Second store or office address (optional).')
                    ->schema([
                        TextInput::make('address_2_title')
                            ->label('Title')
                            ->maxLength(255)
                            ->placeholder('Store 2')
                            ->columnSpan(1),
                        Textarea::make('address_2_text')
                            ->label('Address')
                            ->rows(2)
                            ->placeholder('8134 Budd Rd Terre Haute, In 3548')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Contact')
                    ->description('Email and phone shown on the contact page.')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('info@aliarlie.com')
                            ->columnSpan(1),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50)
                            ->placeholder('+354-354-4861')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Social links')
                    ->description('URLs for social icons. Leave blank to hide an icon.')
                    ->schema([
                        TextInput::make('social_facebook')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/...')
                            ->columnSpan(1),
                        TextInput::make('social_pinterest')
                            ->label('Pinterest URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://pinterest.com/...')
                            ->columnSpan(1),
                        TextInput::make('social_twitter')
                            ->label('Twitter / X URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/...')
                            ->columnSpan(1),
                        TextInput::make('social_instagram')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/...')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
