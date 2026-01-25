<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Collection Information')
                    ->description('Create and manage product collections.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Collection Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Summer Collection')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                if (filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('summer-collection')
                            ->unique(ignoreRecord: true)
                            ->helperText('Used in URLs. Lowercase letters, numbers, and hyphens only.')
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive collections will not appear in the store.')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->helperText('Brief description of the collection.')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Collection Image')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->disk('public')
                            ->directory('collections')
                            ->maxSize(2048)
                            ->helperText('Upload a JPG or PNG (max 2MB). Recommended: 16:9 aspect ratio.')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first. Use to control display order.')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
