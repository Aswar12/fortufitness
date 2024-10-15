<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Notifications\Notification;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    public function getActions(): array
    {

        return [

            Actions\Action::make('verify')
                ->action(fn() => $this->verifyPayment())
                ->requiresConfirmation()
                ->visible(fn() => $this->record->status === 'pending')
                ->color('success'),
            Actions\Action::make('reject')
                ->action(fn() => $this->rejectPayment())
                ->requiresConfirmation()
                ->visible(fn() => $this->record->status === 'pending')
                ->color('danger'),
        ];
    }
    private function verifyPayment()
    {
        $this->record->status = 'completed';
        $this->record->save();

        $membership = $this->record->membership;
        if ($membership->status === 'pending') {
            $membership->status = 'active';
            $membership->save();
        }

        Notification::make()
            ->success()
            ->title('Pembayaran Terverifikasi')
            ->body('Pembayaran telah diverifikasi dan keanggotaan telah diaktifkan.')
            ->send();
    }

    private function rejectPayment()
    {
        $this->record->status = 'failed';
        $this->record->save();

        Notification::make()
            ->warning()
            ->title('Pembayaran Ditolak')
            ->body('Pembayaran telah ditolak.')
            ->send();
    }
}
