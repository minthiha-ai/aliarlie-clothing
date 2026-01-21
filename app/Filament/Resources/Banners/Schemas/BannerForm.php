<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Banner Information')
                    ->description('Manage banner content and visibility.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255)
                            ->placeholder('Summer Sale Banner')
                            ->columnSpan(1),
                        Select::make('page')
                            ->label('Page')
                            ->options([
                                'default' => 'Default',
                                'home' => 'Home',
                                'shop' => 'Shop',
                                'collection' => 'Collection',
                                'account' => 'Account',
                            ])
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive banners will not be shown on the website.')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),

                        FileUpload::make('image')
                            ->label('Banner Image')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->maxSize(2048)
                            ->required()

                            ->helperText('Recommended size: 1600×600px. JPG or PNG, max 2MB.')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Optional short description for this banner')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
