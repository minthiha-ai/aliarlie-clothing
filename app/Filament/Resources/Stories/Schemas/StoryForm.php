<?php

namespace App\Filament\Resources\Stories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Story')
                    ->description('Content shown on the Collections page. Image and content alternate left/right per story.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('stories')
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Lower numbers appear first.')
                            ->columnSpan(1),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
