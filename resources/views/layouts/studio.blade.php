<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Studio - Ruang Kerja</title>
    @if(auth()->check() && auth()->user()->profile_photo_path)
        <link rel="icon" type="image/jpeg" href="{{ asset('storage/' . auth()->user()->profile_photo_path) }}?v={{ time() }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Devicon -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css" />
    
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#0b0f19', // Warna latar belakang gelap khas referensimu
                        cardBg: '#111827', // Warna kartu
                        accent: '#6366f1', // Akses biru keunguan
                    }
                }
            }
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (initialContent = '') => ({
                content: initialContent,
                init() {
                    const quill = new Quill(this.$refs.quill, {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet'}],
                                ['clean']
                            ]
                        }
                    });
                    
                    quill.root.innerHTML = this.content || '';
                    
                    quill.on('text-change', () => {
                        this.content = quill.root.innerHTML;
                        this.$el.dispatchEvent(new CustomEvent('quill-update', { detail: this.content }));
                    });

                    this.$watch('content', val => {
                        if (val !== quill.root.innerHTML) {
                            quill.root.innerHTML = val || '';
                        }
                    });
                }
            }));
        });
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
<body class="bg-slate-50 dark:bg-darkBg text-slate-800 dark:text-slate-300 font-sans antialiased min-h-screen transition-colors duration-300 relative overflow-x-hidden"
      x-data="{ 
          darkMode: localStorage.getItem('theme') !== 'light',
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

    <!-- Decorational background elements for glassmorphism -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-500/60 dark:bg-indigo-500/50 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[100px] pointer-events-none animate-blob z-0"></div>
    <div class="fixed top-[20%] right-[-10%] w-[400px] h-[400px] bg-purple-500/60 dark:bg-purple-500/50 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[100px] pointer-events-none animate-blob animation-delay-2000 z-0"></div>
    <div class="fixed bottom-[-20%] left-[20%] w-[600px] h-[600px] bg-cyan-400/60 dark:bg-teal-500/50 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[120px] pointer-events-none animate-blob animation-delay-4000 z-0"></div>
    
    <!-- Top Navigation Simple -->
    <nav class="border-b border-white/60 dark:border-white/10 bg-white/30 dark:bg-darkBg/50 backdrop-blur-xl sticky top-0 z-50 transition-colors duration-300 shadow-sm dark:shadow-none">
        <div class="w-full px-8 py-4 flex justify-between items-center relative z-10">
            <div class="text-xl font-bold text-slate-900 dark:text-white">
                {{ ucfirst(auth()->user()->username) }}.<span class="text-accent">Studio</span>
            </div>
            <div class="flex items-center gap-4">
                <button @click="toggleTheme()" class="p-2 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition rounded-full hover:bg-slate-200/50 dark:hover:bg-slate-800/50 focus:outline-none">
                    <!-- Sun icon -->
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <!-- Moon icon -->
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path></svg>
                </button>
                <a href="{{ url('/' . auth()->user()->username) }}" target="_blank" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">Lihat Portofolio</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-white/50 hover:bg-slate-200/50 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 backdrop-blur-sm text-slate-900 dark:text-white px-4 py-2 rounded-lg transition border border-slate-200/50 dark:border-slate-700/50 shadow-sm">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         @toast.window="message = $event.detail.message; type = $event.detail.type || 'success'; show = true; setTimeout(() => show = false, 3000)"
         class="fixed top-24 right-8 z-[100] transition-all duration-300 transform"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-[-10px] scale-95"
         x-cloak>
        <div class="flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl backdrop-blur-md border border-white/20 dark:border-white/10"
             :class="{
                 'bg-green-500/90 text-white shadow-green-500/20': type === 'success',
                 'bg-red-500/90 text-white shadow-red-500/20': type === 'error',
                 'bg-blue-500/90 text-white shadow-blue-500/20': type === 'info'
             }">
             <svg x-show="type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
             <svg x-show="type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             <span class="text-sm font-semibold tracking-wide" x-text="message"></span>
        </div>
    </div>

    <!-- Content Area -->
    <main class="w-full px-8 py-10 relative z-10">
        @yield('content')
    </main>

</body>
</html>