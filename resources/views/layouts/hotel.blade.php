<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Quản Lý Khách Sạn') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Sidebar -->
        <div class="flex">
            <div class="w-64 bg-white shadow-md min-h-screen">
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-800">Quản Lý Khách Sạn</h2>
                </div>
                <nav class="mt-4">
                    <ul>
                        <li class="mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Tổng quan
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('rooms.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Quản lý phòng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('room-types.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Loại phòng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Đặt phòng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Khách hàng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('services.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Dịch vụ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('rooms.available') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                Kiểm tra phòng trống
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
