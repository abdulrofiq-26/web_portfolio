<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - Portfolio</title>
    @php
        $user = \App\Models\User::first();
    @endphp
    @if($user && $user->profile_photo_path)
        <link rel="icon" type="image/jpeg" href="{{ asset('storage/' . $user->profile_photo_path) }}?v={{ time() }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#0b0f19', // Warna latar belakang gelap
                        cardBg: '#111827', // Warna kartu
                        accent: '#6366f1', // Akses biru keunguan
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-darkBg text-slate-800 dark:text-slate-300 font-sans antialiased min-h-screen flex items-center justify-center transition-colors duration-300 relative overflow-hidden"
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }">

    <!-- Theme Toggle -->
    <div class="absolute top-6 right-8 z-50">
        <button @click="toggleTheme()" class="p-2.5 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition rounded-full hover:bg-slate-200/50 dark:hover:bg-slate-800/50 focus:outline-none backdrop-blur-md bg-white/30 dark:bg-darkBg/30 border border-slate-200/50 dark:border-slate-700/50 shadow-sm">
            <!-- Sun icon -->
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <!-- Moon icon -->
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path></svg>
        </button>
    </div>
    
    <!-- Decorational background elements for glassmorphism -->
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-500/60 dark:bg-indigo-500/60 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[100px] pointer-events-none animate-blob"></div>
    <div class="absolute top-[20%] right-[-10%] w-[400px] h-[400px] bg-purple-500/60 dark:bg-purple-500/60 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[100px] pointer-events-none animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-[600px] h-[600px] bg-cyan-400/60 dark:bg-teal-500/60 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[120px] pointer-events-none animate-blob animation-delay-4000"></div>

    <div class="w-full max-w-md p-8 bg-white/30 dark:bg-cardBg/40 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-white/80 dark:border-white/10 backdrop-blur-2xl relative z-10 transition-colors duration-300">
        @yield('content')
    </div>

</body>
</html>