<!-- resources/views/payments/process.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-8">
                <h3 class="text-lg font-semibold mb-6 border-b pb-2">Detail Pembayaran</h3>
                <div class="mb-4">
                    <p class="text-gray-700">Jumlah: <span class="font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></p>
                    <p class="text-gray-700">Tanggal Pembayaran: <span class="font-bold">{{ $payment->payment_date }}</span></p>
                    <p class="text-gray-700">Metode Pembayaran: <span class="font-bold">{{ $payment->payment_method }}</span></p>
                    <p class="text-gray-700">Status: <span class="font-bold">{{ ucfirst($payment->status) }}</span></p>
                </div>

                <h3 class="text-lg font-semibold mb-6 border-b pb-2">Rekening Bank</h3>
                @if($bankAccounts->count() > 0)
                <ul class="list-disc pl-5">
                    @foreach($bankAccounts as $bankAccount)
                    <li class="text-gray-700">
                        <strong>{{ $bankAccount->bank_name }}</strong> - {{ $bankAccount->account_name }} (No. Rek: {{ $bankAccount->account_number }})
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-700">Tidak ada rekening bank yang tersedia.</p>
                @endif

                <form action="{{ route('payments.uploadProof', $payment->id) }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    <div class="mb-6">
                        <label for="proof_of_payment" class="block text-sm font-medium text-gray-700">Upload Bukti Transfer</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            onchange="previewImage(event)">
                        <img id="preview" src="#" alt="Preview" style="display: none; max-width: 300px; margin-top: 10px;" class="mt-2 rounded-md shadow-md">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        }
    </script>
</x-app-layout>