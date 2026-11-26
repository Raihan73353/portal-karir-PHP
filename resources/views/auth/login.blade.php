@extends('layouts.app')

@section('title', 'Masuk - Portal Karir')

@section('content')
<div class="max-w-md mx-auto bg-white border border-gray-200 rounded-xl p-6 sm:p-8 shadow-sm mt-4">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Masuk ke Akun</h1>
        <p class="text-sm text-gray-600 mt-1">Akses portal lowongan magang dan karier Anda.</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="nama@email.com"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input type="password" id="password" name="password" required
                   placeholder="Masukkan kata sandi"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 mr-2">
                Ingat saya
            </label>
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Masuk
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">
            Daftar sekarang
        </a>
    </p>
</div>
@endsection