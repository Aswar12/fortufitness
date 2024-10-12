<x-filament::page>
    <div class="p-4 space-y-6">
        <div class="flex flex-col lg:flex-row lg:space-x-6">
            <!-- Informasi Pembayaran -->
            <div class="w-full lg:w-1/3 space-y-4">
                @foreach ([
                ['label' => 'ID', 'value' => $payment->id],
                ['label' => 'Jumlah', 'value' => 'Rp ' . number_format($payment->amount, 0, ',', '.')],
                ['label' => 'Metode Pembayaran', 'value' => $payment->payment_method],
                ['label' => 'Status', 'value' => $payment->status]
                ] as $info)
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $info['label'] }}</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($info['label'] === 'Status')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $payment->status === 'approved' ? 'Disetujui' : ($payment->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                        </span>
                        @else
                        {{ $info['value'] }}
                        @endif
                    </p>
                </div>
                @endforeach
            </div>

            <!-- Bukti Pembayaran -->
            <div class="w-full lg:w-2/3 mt-6 lg:mt-0">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bukti Pembayaran</h3>
                    @if($payment->proof_of_payment)
                    <div class="w-full h-64 sm:h-96 overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($payment->proof_of_payment) }}" alt="Bukti Pembayaran" class="w-full h-full object-contain">
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Tidak ada bukti pembayaran yang diunggah</p>
                    @endif
                </div>
            </div>
            <div class="flex space-x-4 mt-6">
                <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300"
                    wire:click.prevent="approvePayment({{ $payment->id }})">
                    Approve
                </button>
                <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300"
                    wire:click.prevent="rejectPayment({{ $payment->id }})">
                    Reject
                </button>
            </div>
        </div>
    </div>
</x-filament::page>

@push('scripts')
<script>
    Livewire.on('approvePayment', (paymentId) => {
        axios.post('/payments/' + paymentId + '/approve')
            .then(response => {
                // Update the payment status on the page
                document.querySelector(`[data-payment-id="${paymentId}"]`).innerHTML = 'Disetujui';
            })
            .catch(error => {
                console.error(error);
            });
    });

    Livewire.on('rejectPayment', (paymentId) => {
        axios.post('/payments/' + paymentId + '/reject')
            .then(response => {
                // Update the payment status on the page
                document.querySelector(`[data-payment-id="${paymentId}"]`).innerHTML = 'Ditolak';
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush