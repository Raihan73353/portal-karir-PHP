<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Karir')</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand & Menu Utama -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('lowongan.index') }}" class="text-xl font-bold text-blue-600">
                        PortalKarir
                    </a>
                    <a href="{{ route('lowongan.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Katalog Lowongan
                    </a>

                    @auth
                        @if(auth()->user()->peran === 'admin')
                            <a href="{{ route('admin.kategori.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Kategori
                            </a>
                            <a href="{{ route('admin.instansi.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Instansi
                            </a>
                            <a href="{{ route('admin.lowongan.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Kelola Lowongan
                            </a>
                            <a href="{{ route('admin.lamaran.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Data Lamaran
                            </a>
                        @elseif(auth()->user()->peran === 'pelamar')
                            <a href="{{ route('pelamar.riwayat') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Riwayat Lamaran
                            </a>
                            <a href="{{ route('pelamar.profil.edit') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Profil & CV
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Menu Akun -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Daftar
                        </a>
                    @else
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ auth()->user()->peran === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(auth()->user()->peran) }}
                        </span>
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline">
                            {{ auth()->user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Notifikasi Flash Message -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Konten Utama -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Portal Karir. Seluruh hak cipta dilindungi.
        </div>
    </footer>

</body>
</html>