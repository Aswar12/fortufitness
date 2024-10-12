<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Filament\Notifications\Notification;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('verify')
                ->action(function () {
                    $this->record->status = 'verified';
                    $this->record->save();

                    $membership = $this->record->membership;
                    if ($membership->status === 'pending') {
                        $membership->status = 'active';
                        $membership->save();
                    }

                    Notification::make()
                        ->success()
                        ->title('Payment verified')
                        ->body('The payment has been verified and the membership has been activated.')
                        ->send();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->record->status === 'pending')
                ->color('success'),
            Actions\Action::make('reject')
                ->action(function () {
                    $this->record->status = 'rejected';
                    $this->record->save();

                    Notification::make()
                        ->warning()
                        ->title('Payment rejected')
                        ->body('The payment has been rejected.')
                        ->send();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->record->status === 'pending')
                ->color('danger'),
        ];
    }
}
