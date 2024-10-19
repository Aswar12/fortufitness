@php
use App\Filament\Resources\CheckInResource;
@endphp
<x-filament::page>
    <div wire:ignore>
        <h2>Scan QR Code</h2>
        <div id="reader" width="600px"></div>
    </div>

    <input type="hidden" wire:model="qrCode" id="qrCodeInput">

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('qrCodeInput').value = decodedText;
            @this.set('qrCode', decodedText);
        }

        function onScanFailure(error) {
            console.warn(`Code scan error = ${error}`);
        }

        window.addEventListener('load', function() {
            const html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            };

            html5QrCode.start({
                    facingMode: "environment"
                }, config, onScanSuccess, onScanFailure)
                .catch((err) => {
                    console.error(`Unable to start scanning: ${err}`);
                });
        });
    </script>
    @endpush
</x-filament::page>