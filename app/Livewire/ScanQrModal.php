<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Checkin;

class ScanQrModal extends Component
{
    public $qrResult = '';
    public $scanner = null;

    public function mount()
    {
        $this->scanner = new \Zxing\Scanner();
    }

    public function checkIn()
    {
        Checkin::create([
            'member_id' => $this->qrResult,
            'checkin_time' => now(),
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Check-in berhasil untuk membership ID ' . $this->qrResult,
        ]);
    }

    public function render()
    {
        return view('livewire.scan-qr-modal');
    }

    public function updatedQrResult()
    {
        if ($this->qrResult) {
            $this->scanner->decode($this->qrResult);
        }
    }
}
