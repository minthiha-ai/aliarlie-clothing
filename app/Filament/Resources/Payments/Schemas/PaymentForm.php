<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Method Information')
                    ->description('Configure available payment methods for customers.')
                    ->schema([
                        Select::make('payment_type')
                            ->label('Payment Type')
                            ->options([
                                'cod' => 'Cash on Delivery (COD)',
                                'online_payment' => 'Online Payment',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->helperText('Inactive payment methods will not be shown to customers.')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('name')
                            ->label('Account / Payment Name')
                            ->placeholder('KBZ Pay - Aliarlie Clothing')
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('number')
                            ->label('Account / Phone Number')
                            ->placeholder('09XXXXXXXXX')
                            ->maxLength(255)
                            ->columnSpan(1),

                        FileUpload::make('payment_logo')
                            ->label('Payment Logo')
                            ->image()
                            ->disk('public')
                            ->directory('payments')
                            ->imagePreviewHeight('120')
                            ->imageEditor()
                            ->maxSize(2048)

                            ->helperText('Recommended size: square logo, max 2MB (JPG or PNG).')
                            ->columnSpanFull(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
