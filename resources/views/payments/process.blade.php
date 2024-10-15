<!-- resources/views/payments/process.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pembayaran</h3>
                <p>Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p>Tanggal Pembayaran: {{ $payment->payment_date }}</p>
                <p>Metode Pembayaran: {{ $payment->payment_method }}</p>
                <p>Status: {{ ucfirst($payment->status) }}</p>

                <form action="{{ route('payments.uploadProof', $payment->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="proof_of_payment" class="block text-sm font-medium text-gray-700">Upload Bukti Transfer</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            onchange="previewImage(event)">
                        <img id="preview" src="#" alt="Preview" style="display: none; max-width: 300px; margin-top: 10px;">
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                        <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>