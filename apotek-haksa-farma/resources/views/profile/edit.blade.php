@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <!-- Icon User Edit -->
        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        Edit Profil & Password
    </h2>
</div>

@if(session('success'))
    <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
        <button onclick="dismissAlert('success-alert')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <span>&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border rounded-xl shadow-sm p-6 max-w-2xl">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Pengguna (Tidak Bisa Diubah)</label>
            <input type="text" value="{{ auth()->user()->nama }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed" disabled>
        </div>

        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-semibold mb-2">Username Baru (Untuk Login)</label>
            <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-sm text-gray-500 mt-1">Anda juga dapat membiarkan ini jika tidak ingin mengubah username.</p>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold text-gray-800 mb-4">Ganti Password</h3>
        
        <div class="mb-4">
            <label for="password_baru" class="block text-gray-700 font-semibold mb-2">Password Baru</label>
            <input type="password" name="password_baru" id="password_baru" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Kosongkan jika tidak ingin ganti password">
            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
        </div>

        <div class="mb-6">
            <label for="password_baru_confirmation" class="block text-gray-700 font-semibold mb-2">Ulangi Password Baru</label>
            <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="flex items-center justify-end">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
