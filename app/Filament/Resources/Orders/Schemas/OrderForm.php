<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Township;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

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
                            ->prefix('MMK ')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Order items subtotal + delivery fees (recalculated on save).')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Delivery')
                    ->description('Delivery address and fees. Total amount = items subtotal + delivery fees.')
                    ->schema([
                        Select::make('state_region_id')
                            ->label('State / Region')
                            ->relationship('stateRegion', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (callable $set): mixed => $set('township_id', null))
                            ->columnSpan(1),

                        Select::make('township_id')
                            ->label('Township')
                            ->options(function (Get $get): array {
                                $stateRegionId = $get('state_region_id');
                                if (! $stateRegionId) {
                                    return [];
                                }

                                return Township::query()
                                    ->where('state_region_id', $stateRegionId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpan(1),

                        TextInput::make('delivery_fees')
                            ->label('Delivery Fees (MMK)')
                            ->numeric()
                            ->prefix('MMK ')
                            ->minValue(0)
                            ->default(0)
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
                        View::make('filament.orders.payment-proof-preview')
                            ->visible(fn (?Model $record, Get $get): bool => $record && filled($record->payment_proof_photo ?? null) && $get('payment_method') === 'online_payment')
                            ->columnSpanFull(),

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
                            ->imagePreviewHeight('200')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required()
                            ->helperText('Upload transfer screenshot (max 2MB). Click image above to view full size.')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('payment_method') === 'online_payment')
                    ->columnSpanFull(),
            ]);
    }
}
