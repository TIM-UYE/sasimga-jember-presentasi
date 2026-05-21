@extends('users.layout.main')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang, {{ Auth::user()->nama }}!</h1>
    <p class="text-gray-600">Ini adalah halaman dashboard pengguna.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-purple-100 rounded-lg p-6 text-center">
            <i class="fas fa-utensils text-3xl text-purple-600 mb-3"></i>
            <h3 class="font-semibold text-gray-700">Menu</h3>
            <p class="text-2xl font-bold text-purple-600">Lihat Menu</p>
        </div>
        <div class="bg-blue-100 rounded-lg p-6 text-center">
            <i class="fas fa-shopping-cart text-3xl text-blue-600 mb-3"></i>
            <h3 class="font-semibold text-gray-700">Pesanan</h3>
            <p class="text-2xl font-bold text-blue-600">Buat Pesanan</p>
        </div>
        <div class="bg-green-100 rounded-lg p-6 text-center">
            <i class="fas fa-history text-3xl text-green-600 mb-3"></i>
            <h3 class="font-semibold text-gray-700">Riwayat</h3>
            <p class="text-2xl font-bold text-green-600">Lihat Riwayat</p>
        </div>
    </div>
</div>
@endsection
