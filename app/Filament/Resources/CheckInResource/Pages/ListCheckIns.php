<?php

namespace App\Filament\Resources\CheckInResource\Pages;

use App\Filament\Resources\CheckInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CheckInResource\Pages\ScanQrModal;
use App\Models\CheckIn;
use Illuminate\Contracts\View\View;

class ListCheckIns extends ListRecords
{
    protected static string $resource = CheckInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('scanQr')
                ->label('Scan QR')
                ->url(fn(): string => static::getResource()::getUrl('scan'))
                ->icon('heroicon-o-qr-code'),
        ];
    }
}
