<x-filament-panels::page>
    <!-- scan-barcode.blade.php -->

    <div class="scan-barcode-component">
        <h2>Scan Barcode</h2>
        <input type="text" id="barcode-input" placeholder="Masukkan kode barcode" />
        <button id="scan-button" class="btn btn-primary">Scan</button>

        <div id="scan-result">
            <p id="barcode-data"></p>
            <p id="barcode-error"></p>
        </div>

        @push('scripts')
        <script>
            // Inisialisasi variabel
            const barcodeInput = document.getElementById('barcode-input');
            const scanButton = document.getElementById('scan-button');
            const scanResult = document.getElementById('scan-result');
            const barcodeData = document.getElementById('barcode-data');
            const barcodeError = document.getElementById('barcode-error');

            // Fungsi untuk menghandle scan button click event
            scanButton.addEventListener('click', () => {
                // Ambil nilai input barcode
                const barcodeValue = barcodeInput.value.trim();

                // Cek apakah nilai input barcode kosong
                if (barcodeValue === '') {
                    barcodeError.textContent = 'Masukkan kode barcode terlebih dahulu';
                    return;
                }

                // Kirim request ke server untuk memproses barcode
                fetch('/api/scan-barcode', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            barcode: barcodeValue
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tampilkan hasil scan barcode
                        barcodeData.textContent = `Kode barcode: ${data.barcode}`;
                        barcodeError.textContent = '';
                    })
                    .catch(error => {
                        // Tampilkan error message jika terjadi kesalahan
                        barcodeError.textContent = 'Terjadi kesalahan saat memproses barcode';
                    });
            });
        </script>
        @endpush
    </div>
</x-filament-panels::page>