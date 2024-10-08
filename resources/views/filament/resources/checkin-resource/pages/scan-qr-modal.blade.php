<div>
    <div id="reader" style="width: 100%"></div>
    <input type="hidden" id="result" wire:model="qrResult">
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('result').value = decodedText;
        document.getElementById('result').dispatchEvent(new Event('input'));
        html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        console.warn(`QR code scanning failed: ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush