@extends('layouts.auth')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Selamat Datang Kembali</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Masuk ke dashboard portofoliomu</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/50 p-3 rounded-md">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 bg-transparent dark:bg-darkBg/50 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all text-slate-800 dark:text-white">
            @error('email') <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" required class="w-full px-4 py-2 bg-transparent dark:bg-darkBg/50 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all text-slate-800 dark:text-white pr-10">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center text-slate-600 dark:text-slate-400 cursor-pointer">
                <input type="checkbox" name="remember" class="mr-2 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-darkBg text-accent focus:ring-accent"> Ingat Saya
            </label>
            <a href="{{ route('password.request') }}" class="text-accent hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline transition-colors">Lupa Password?</a>
        </div>

        <button type="submit" class="w-full bg-slate-900 dark:bg-accent text-white font-medium py-2.5 rounded-lg hover:bg-slate-800 dark:hover:bg-indigo-600 transition-colors shadow-lg shadow-accent/20 dark:shadow-accent/40">
            Masuk
        </button>

        <div class="text-center mt-4 text-sm">
            <span class="text-slate-500 dark:text-slate-400">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-accent hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline font-medium transition-colors">Daftar sekarang</a>
        </div>
    </form>
@endsection