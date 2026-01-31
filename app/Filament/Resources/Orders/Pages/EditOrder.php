<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected $listeners = [
        'order-items-updated' => 'refreshTotalAmount',
    ];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->recalculateTotalAmount();
    }

    public function refreshTotalAmount(): void
    {
        $this->record->refresh();

        $this->form->fill([
            'total_amount' => $this->record->total_amount,
        ]);
    }
}
