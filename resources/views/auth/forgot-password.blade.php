@extends('layouts.auth')

@section('content')
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Lupa Password?</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Masukkan email yang terdaftar, dan sistem akan mengirimkan tautan untuk mengatur ulang passwordmu.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/50 p-3 rounded-md">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 bg-transparent dark:bg-darkBg/50 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all text-slate-800 dark:text-white">
            @error('email') <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-accent dark:bg-accent text-white font-medium py-2.5 rounded-lg hover:bg-indigo-600 transition-colors shadow-lg shadow-accent/20 dark:shadow-accent/40">
            Kirim Link Reset
        </button>

        <div class="text-center mt-4 text-sm">
            <a href="{{ route('login') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">Kembali ke halaman Login</a>
        </div>
    </form>
@endsection