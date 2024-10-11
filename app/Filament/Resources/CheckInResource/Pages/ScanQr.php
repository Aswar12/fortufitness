<?php

namespace App\Filament\Resources\CheckInResource\Pages;

use App\Filament\Resources\CheckInResource;
use App\Models\CheckIn;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;

class ScanQr extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CheckInResource::class;
    protected static string $view = 'filament.resources.check-in-resource.pages.scan-qr';

    public $qrCode = '';
    public $user_id = '';
    public $check_in_time = '';
    public $check_in_method = 'QR Code';



    public function updatedQrCode()
    {
        $this->save();
    }

    public function save()
    {
        $membership = \App\Models\Membership::where('id', $this->qrCode)->first();
        if (!$membership) {
            $this->showNotification('error', 'Membership tidak ditemukan');
            sleep(2);
            return redirect()->to(CheckInResource::getUrl('index'));
        }

        $user_id = $membership->user_id;

        $existingCheckIn = CheckIn::where('user_id', $user_id)
            ->whereNull('check_out_time')
            ->first();

        if ($existingCheckIn) {
            $this->showNotification('warning', 'Pengguna ini sudah melakukan check-in dan belum check-out.');
            sleep(4);
            return redirect()->to(CheckInResource::getUrl('index'));
        }

        CheckIn::create([
            'user_id' => $user_id,
            'check_in_time' => now(),
            'check_in_method' => $this->check_in_method,
            'qrCode' => $this->qrCode,
        ]);

        $this->showNotification('success', 'Check-in berhasil');
        sleep(2);
        return redirect()->to(CheckInResource::getUrl('index'));
    }

    protected function showNotification($type, $message)
    {
        Notification::make()
            ->title($message)
            ->$type()
            ->send();
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(CheckInResource::getUrl('index'))
                ->color('secondary'),
        ];
    }
}
