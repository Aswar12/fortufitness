<x-filament::page>
    <div>
        <h2>Scan QR Code</h2>
        <div id="reader" width="600px"></div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Get the membership ID from the scanned QR code
            const membershipId = decodedText;
            const csrfToken = '{{ csrf_token() }}';
            // Make an API request to create a new check-in record
            fetch('/check-ins', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        membership_id: membershipId,
                    }),
                })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error(error));
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
    @endpush
</x-filament::page>