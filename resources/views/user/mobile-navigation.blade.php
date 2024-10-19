@section('mobile-navigation')
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-black bg-opacity-40 text-white p-4 flex justify-around" id="mobile-menu">
    <a class="block py-2 hover:text-yellow-400 text-center" href="#">
        <i class="fas fa-home text-xl"></i>
        <span class="block text-xs">Home</span>
    </a>
    <a class="block py-2 hover:text-yellow-400 text-center" href="#">
        <i class="fas fa-id-card text-xl"></i>
        <span class="block text-xs">Keanggotaan</span>
    </a>
    <a class="block py-2 hover:text-yellow-400 text-center" href="#">
        <i class="fas fa-receipt text-xl"></i>
        <span class="block text-xs">Transaksi</span>
    </a>
    <a class="block py-2 hover:text-yellow-400 text-center" href="#">
        <i class="fas fa-check text-xl"></i>
        <span class="block text-xs">Check-in</span>
    </a>
</nav>
@endsection