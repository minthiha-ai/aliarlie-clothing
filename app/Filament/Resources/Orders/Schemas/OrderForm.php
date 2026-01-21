<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->description('Basic order details and status.')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('order_code')
                            ->label('Order Code')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('ORD-2026-0001')
                            ->default(fn (): string => sprintf(
                                'ALIAR-%03d-%03d',
                                random_int(0, 999),
                                random_int(0, 999)
                            ))
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated order reference for tracking.')
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Order Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'shipped' => 'Shipped',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(true)
                            ->helperText('Calculated from order items.')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Payment Method')
                    ->description('Select how the customer paid.')
                    ->schema([
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cod' => 'Cash on Delivery (COD)',
                                'online_payment' => 'Online Payment',
                            ])
                            ->default('cod')
                            ->required()
                            ->reactive()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Online Payment Details')
                    ->description('Required only for online payments.')
                    ->schema([
                        Select::make('payment_id')
                            ->label('Payment Account')
                            ->relationship('payment', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        FileUpload::make('payment_proof_photo')
                            ->label('Payment Proof')
                            ->image()
                            ->disk('public')
                            ->directory('payments/proofs')
                            ->imagePreviewHeight('150')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required()
                            ->helperText('Upload transfer screenshot (max 2MB).')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->visible(fn(Get $get): bool => $get('payment_method') === 'online_payment')
                    ->columnSpanFull(),
            ]);
    }
}
