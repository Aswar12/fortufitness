<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getActions(): array
    {
        return [
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x')
                ->color('danger')
                ->action(fn(Payment $record) => route('payments.reject', $record)),
            Action::make('verify')
                ->label('Verify')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(fn(Payment $record) => route('payments.verify', $record)),
        ];
    }
}
