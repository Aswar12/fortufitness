<div>
    <h2 class="text-lg font-bold mb-4">Scan QR Code</h2>

    <div class="mb-4">
        <video id="scanner-video" width="100%" height="300"></video>
        <input type="text" wire:model="qrResult" id="qrResult" class="block w-full pl-10 text-sm text-gray-700">
        <label for="qrResult" class="absolute left-3 top-2 text-gray-500 text-sm">Scan QR Code</label>
    </div>

    @if ($errors->has('qrResult'))
    <div class="text-red-500 mt-2">{{ $errors->first('qrResult') }}</div>
    @endif

    <button wire:click="checkIn" class="btn btn-success">Check-in</button>

    @push('scripts')
    <script src="https://unpkg.com/@zxing/library@0.18.4/umd/index.js"></script>
    <script>
        const scanner = new ZXing.Scanner();
        scanner.decodeFromVideoDevice().then(result => {
            document.getElementById('qrResult').value = result.text;
        }).catch(err => {
            console.error(err);
        });
    </script>
    @endpush
</div>