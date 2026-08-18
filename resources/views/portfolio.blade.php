<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $user->name }} — {{ $user->headline ?? 'Developer' }}. Lihat portofolio, proyek, dan keahlian saya.">
    <title>{{ $user->name }} — {{ $user->headline ?? 'Portofolio' }}</title>
    
    @if($user->profile_photo_path)
        <link rel="icon" type="image/jpeg" href="{{ asset('storage/' . $user->profile_photo_path) }}?v={{ time() }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    @endif
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Devicon -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        darkBg: '#0f172a', // slate-900
                        darkCard: '#1e293b', // slate-800
                        accent: '#4f46e5', // indigo-600
                        accentDark: '#4338ca', // indigo-700
                    },
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('portfolio-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0b0f19; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 10px; }

        /* Blob animation */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 8s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Fade-in on scroll */
        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }

        /* Typing cursor */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .cursor-blink::after {
            content: '|';
            animation: blink 1s infinite;
            color: #6366f1;
            font-weight: 300;
        }

        /* Glow effect for profile */
        .profile-glow {
            box-shadow: 0 0 60px rgba(99, 102, 241, 0.4), 0 0 120px rgba(99, 102, 241, 0.15);
        }

        /* Card hover lift */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #a78bfa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navbar blur */
        .nav-blur {
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-darkBg text-slate-800 dark:text-slate-300 font-sans antialiased overflow-x-hidden transition-colors duration-500"
      x-data="{
          darkMode: localStorage.getItem('portfolio-theme') !== 'light',
          mobileMenu: false,
          activeSection: 'home',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('portfolio-theme', this.darkMode ? 'dark' : 'light');
              document.documentElement.classList.toggle('dark', this.darkMode);
          }
      }">

    <!-- ========================================= -->
    <!-- BACKGROUND DECORATIONS                    -->
    <!-- ========================================= -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-[-15%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/30 dark:bg-indigo-600/20 rounded-full filter blur-[120px] animate-blob"></div>
        <div class="absolute top-[30%] right-[-15%] w-[450px] h-[450px] bg-purple-600/25 dark:bg-purple-500/15 rounded-full filter blur-[120px] animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[25%] w-[550px] h-[550px] bg-cyan-500/20 dark:bg-teal-500/10 rounded-full filter blur-[130px] animate-blob animation-delay-4000"></div>
    </div>

    <!-- ========================================= -->
    <!-- NAVBAR                                    -->
    <!-- ========================================= -->
    <nav class="fixed top-0 left-0 right-0 z-50 nav-blur bg-white/70 dark:bg-darkBg/70 border-b border-slate-200/50 dark:border-white/5 transition-colors duration-300"
         id="navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <a href="#home" class="text-xl lg:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ explode(' ', $user->username)[0] }}<span class="text-accent">.</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $visibility = is_string($user->section_visibility) ? json_decode($user->section_visibility, true) : (is_array($user->section_visibility) ? $user->section_visibility : []);
                        $sections = [
                            'home' => 'Home', 
                            'about' => 'About', 
                            'skills' => 'Skills', 
                            'projects' => 'Projects', 
                            'certificates' => 'Certificates', 
                            'experiences' => 'Experiences', 
                            'contact' => 'Contact'
                        ];
                    @endphp
                    @foreach($sections as $id => $label)
                        @if($id === 'home' || ($visibility[$id] ?? true))
                            <a href="#{{ $id }}"
                               class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                      text-slate-600 dark:text-slate-400
                                      hover:text-accent dark:hover:text-accent
                                      hover:bg-accent/5 dark:hover:bg-accent/10"
                               :class="activeSection === '{{ $id }}' ? '!text-accent bg-accent/10 dark:bg-accent/10' : ''">
                                {{ $label }}
                            </a>
                        @endif
                    @endforeach
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()"
                            class="relative w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-accent dark:hover:text-accent transition-all duration-200 hover:scale-105">
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                        </svg>
                    </button>

                    <!-- Avatar -->
                    <div class="hidden md:block w-10 h-10 rounded-xl overflow-hidden border-2 border-accent/30">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-accent/20 flex items-center justify-center text-accent font-bold text-sm">
                                {{ substr($user->name, 0, 1) }}   
                            </div>
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center">
                        <svg x-show="!mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenu" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white/90 dark:bg-darkBg/90 nav-blur border-b border-slate-200/50 dark:border-white/5 px-6 pb-4">
            @foreach($sections as $id => $label)
                @if($id === 'home' || ($visibility[$id] ?? true))
                    <a href="#{{ $id }}" @click="mobileMenu = false"
                       class="block py-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-accent transition-colors border-b border-slate-100 dark:border-white/5 last:border-0">
                        {{ $label }}
                    </a>
                @endif
            @endforeach
        </div>
    </nav>

    <!-- ========================================= -->
    <!-- HERO SECTION                              -->
    <!-- ========================================= -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 lg:pt-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Left Content -->
                <div class="order-2 lg:order-1 fade-up">
                    <!-- Name -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-tight mb-4">
                        {{ $user->name }}
                    </h1>

                    <!-- Animated Badge (Headlines) -->
                    @php
                        $headlines = is_array($user->headlines) && count($user->headlines) > 0 ? $user->headlines : (empty($user->headline) ? ['Developer'] : [$user->headline]);
                    @endphp
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-accent/10 dark:bg-accent/10 border border-accent/20 mb-6" x-data="{
                        headlines: @js($headlines),
                        currentIndex: 0,
                        currentText: '',
                        isDeleting: false,
                        type() {
                            if (!this.headlines || this.headlines.length === 0) return;
                            const fullText = this.headlines[this.currentIndex];
                            
                            if (this.isDeleting) {
                                this.currentText = fullText.substring(0, this.currentText.length - 1);
                            } else {
                                this.currentText = fullText.substring(0, this.currentText.length + 1);
                            }
                            
                            let typeSpeed = this.isDeleting ? 50 : 100;
                            
                            if (!this.isDeleting && this.currentText === fullText) {
                                typeSpeed = 2000;
                                this.isDeleting = true;
                            } else if (this.isDeleting && this.currentText === '') {
                                this.isDeleting = false;
                                this.currentIndex = (this.currentIndex + 1) % this.headlines.length;
                                typeSpeed = 500;
                            }
                            
                            setTimeout(() => this.type(), typeSpeed);
                        }
                    }" x-init="type()">
                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                        <span class="text-accent text-sm sm:text-base font-bold cursor-blink min-w-[100px]" x-text="currentText || '\u00A0'"></span>
                    </div>

                    <!-- Bio Short -->
                    <div class="text-base lg:text-lg text-slate-600 dark:text-slate-400 leading-relaxed mb-8 max-w-lg prose prose-slate dark:prose-invert">
                        {!! $user->home_bio ?? $user->bio ?? 'I build modern, responsive and scalable web applications and bring ideas to life on the web.' !!}
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4 mb-8">
                        <a href="#projects"
                           class="group inline-flex items-center gap-2 px-7 py-3.5 bg-accent hover:bg-indigo-600 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-accent/30 hover:shadow-accent/50 hover:scale-[1.02]">
                            View My Work
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="#contact"
                           class="inline-flex items-center gap-2 px-7 py-3.5 bg-white/80 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-white font-semibold rounded-xl transition-all duration-300 hover:border-accent/50 hover:bg-accent/5 backdrop-blur-sm hover:scale-[1.02]">
                            Contact Me
                        </a>
                    </div>

                    <!-- Social Icons -->
                    <div class="flex items-center gap-4">
                        @php
                            $socialIcons = [
                                'github' => 'M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z',
                                'linkedin' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
                                'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
                                'twitter' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z',
                                'facebook' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'
                            ];
                        @endphp
                        @foreach($user->socialMedias as $social)
                            @if(isset($socialIcons[$social->platform]))
                            <a href="{{ $social->url }}" target="_blank" rel="noopener"
                               class="w-11 h-11 rounded-xl bg-white/80 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-accent dark:hover:text-accent hover:border-accent/30 transition-all duration-200 hover:scale-110 backdrop-blur-sm"
                               title="{{ ucfirst($social->platform) }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="{{ $socialIcons[$social->platform] }}"/>
                                </svg>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Right - Profile Photo -->
                <div class="order-1 lg:order-2 flex justify-center lg:justify-end fade-up">
                    <div class="relative">
                        <!-- Glow Circle Behind -->
                        <div class="absolute inset-0 bg-gradient-to-br from-accent/30 via-purple-500/20 to-cyan-500/20 rounded-full blur-3xl scale-110 animate-pulse"></div>

                        <!-- Profile Photo -->
                        <div class="relative w-64 h-64 sm:w-80 sm:h-80 lg:w-[360px] lg:h-[360px] rounded-full overflow-hidden profile-glow border-4 border-accent/20">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-accent/30 to-purple-600/30 flex items-center justify-center">
                                    <span class="text-7xl font-black text-white/50">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Experience Badge -->
                        @php
                            $totalYears = $user->hero_experience_years ?: 1;
                        @endphp
                        <div class="absolute bottom-4 left-0 sm:left-[-10px] bg-white/90 dark:bg-darkCard/90 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-2xl px-5 py-3 shadow-xl animate-float">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center">
                                    <span class="text-accent font-black text-lg">{{ $totalYears }}+</span>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Years</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Experience</p>
                                </div>
                            </div>
                        </div>

                        <!-- Code Badge -->
                        <div class="absolute top-6 right-0 sm:right-[-10px] bg-accent/90 backdrop-blur-xl rounded-2xl px-4 py-3 shadow-xl animate-float animation-delay-2000">
                            @php
                                $heroIconClass = config("skills.{$user->hero_icon}.icon") ?? $user->hero_icon;
                            @endphp
                            @if($heroIconClass)
                                <i class="{{ $heroIconClass }} text-white text-4xl"></i>
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- ABOUT ME SECTION                          -->
    <!-- ========================================= -->
    @if($visibility['about'] ?? true)
    <section id="about" class="py-20 lg:py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Label -->
            <div class="fade-up mb-4">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">About Me</span>
            </div>
            <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-12">About Me</h2>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                <!-- Bio Text -->
                <div class="lg:col-span-3 fade-up">
                    <div class="bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-8 shadow-xl">
                        <div class="text-base lg:text-lg text-slate-600 dark:text-slate-400 leading-relaxed mb-6 prose prose-slate dark:prose-invert max-w-none">
                            {!! $user->bio ?? 'I\'m a passionate developer specializing in building beautiful, functional and user-friendly web applications that solve real-world problems.' !!}
                        </div>
                        @if($user->cv_path)
                            <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white font-medium rounded-xl hover:border-accent/50 transition-all duration-200 hover:scale-[1.02]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download CV
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="lg:col-span-2 space-y-4 fade-up">
                    @foreach([
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Name', 'value' => $user->name],
                        ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => $user->email],
                        ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Location', 'value' => $user->location ?: 'Not specified'],
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Availability', 'value' => $user->availability ?: 'Ask me'],
                    ] as $info)
                        <div class="flex items-center gap-4 bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-xl p-4 shadow-sm card-hover">
                            <div class="w-11 h-11 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $info['label'] }}</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $info['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- SKILLS / TECHNOLOGIES SECTION             -->
    <!-- ========================================= -->
    @if($visibility['skills'] ?? true)
    <section id="skills" class="py-20 lg:py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Label -->
            <div class="fade-up mb-4">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">My Skills</span>
            </div>
            <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-12">Technologies I Work With</h2>

            @if($user->skills->count() > 0)
                <div x-data="{ expanded: false }">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                        @foreach($user->skills as $index => $skill)
                            <div class="fade-up card-hover bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-xl p-4 text-center shadow-sm group cursor-default"
                                 x-show="expanded || {{ $index }} < 10"
                                 style="transition-delay: {{ $index * 80 }}ms">
                            <!-- Skill Icon -->
                            <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-gradient-to-br from-accent/20 to-purple-500/20 border border-accent/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                @php
                                    $iconClass = config("skills.{$skill->name}.icon");
                                @endphp
                                @if($iconClass)
                                    <i class="{{ $iconClass }} colored text-2xl"></i>
                                @else
                                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">{{ $skill->name }}</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $skill->category ?? 'Technology' }}</p>
                        </div>
                    @endforeach
                    </div>
                    @if($user->skills->count() > 10)
                        <div class="mt-10 flex justify-center">
                            <button @click="expanded = !expanded" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium transition-colors backdrop-blur-sm flex items-center gap-2">
                                <span x-text="expanded ? 'Show Less' : 'Show More'"></span>
                                <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <div class="fade-up bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No skills added yet. Manage in Studio.</p>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- FEATURED PROJECTS SECTION                 -->
    <!-- ========================================= -->
    @php
        $projectsData = $user->projects->map(function($p) {
            $techArr = is_string($p->tech_stack) ? array_map('trim', explode(',', $p->tech_stack)) : (is_array($p->tech_stack) ? $p->tech_stack : []);
            $techObjects = [];
            foreach($techArr as $t) {
                $techObjects[] = [
                    'name' => $t,
                    'icon' => config("skills.{$t}.icon")
                ];
            }

            return [
                'title' => $p->title,
                'description' => $p->description,
                'image_url' => $p->image_path ? asset('storage/' . $p->image_path) : null,
                'project_url' => $p->project_url,
                'github_url' => $p->github_url,
                'tech_stack' => $techObjects
            ];
        })->values();
    @endphp
    @if($visibility['projects'] ?? true)
    <section id="projects" class="py-20 lg:py-32 relative z-10" x-data="{
        selectedProject: null,
        expanded: false,
        projects: @js($projectsData)
    }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
                <div>
                    <div class="fade-up mb-4">
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">My Projects</span>
                    </div>
                    <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white">Featured Projects</h2>
                </div>
                @if($user->projects->count() > 6)
                    <button @click="expanded = !expanded" class="fade-up inline-flex items-center gap-2 px-5 py-2.5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-accent hover:border-accent/30 transition-all">
                        <span x-text="expanded ? 'Show Less' : 'View All Projects'"></span>
                        <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                @endif
            </div>

            @if($user->projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->projects as $index => $project)
                        <div class="fade-up card-hover bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-sm group cursor-pointer"
                             x-show="expanded || {{ $index }} < 6"
                             style="transition-delay: {{ $index * 100 }}ms"
                             @click="selectedProject = projects[{{ $index }}]">
                            <!-- Project Header with gradient -->
                            <div class="h-40 bg-gradient-to-br from-accent/30 via-purple-500/20 to-pink-500/10 dark:from-accent/20 dark:via-purple-500/10 dark:to-pink-500/5 flex items-center justify-center relative overflow-hidden">
                                @if($project->image_path)
                                    <img src="{{ asset('storage/' . $project->image_path) }}" alt="{{ $project->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <!-- Decorative elements -->
                                    <div class="absolute top-4 right-4 w-20 h-20 rounded-2xl border border-white/10 rotate-12 group-hover:rotate-[24deg] transition-transform duration-500"></div>
                                    <div class="absolute bottom-4 left-4 w-12 h-12 rounded-xl border border-white/10 -rotate-12 group-hover:rotate-[-24deg] transition-transform duration-500"></div>
                                    <svg class="w-12 h-12 text-accent/50 group-hover:scale-110 transition-transform duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Project Info -->
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-accent transition-colors">{{ $project->title }}</h3>
                                    @if($project->project_url)
                                        <a href="{{ $project->project_url }}" target="_blank" rel="noopener"
                                           class="shrink-0 w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 line-clamp-2 leading-relaxed">{{ strip_tags($project->description) }}</p>

                                <!-- Tech Stack Tags -->
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $techStacks = is_string($project->tech_stack) ? array_map('trim', explode(',', $project->tech_stack)) : (is_array($project->tech_stack) ? $project->tech_stack : []);
                                    @endphp
                                    @foreach($techStacks as $tech)
                                        @php
                                            $iconClass = config("skills.{$tech}.icon");
                                        @endphp
                                        @if($iconClass)
                                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-lg" title="{{ $tech }}">
                                                <i class="{{ $iconClass }} colored"></i>
                                            </span>
                                        @else
                                            <span class="text-xs px-3 py-1 rounded-lg bg-accent/10 text-accent font-medium border border-accent/10">{{ $tech }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Project Modal -->
                <template x-teleport="body">
                    <div x-show="selectedProject !== null" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="selectedProject = null"></div>

                        <div class="relative w-full max-w-4xl bg-white dark:bg-darkCard rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                            
                            <button @click="selectedProject = null" class="absolute top-4 right-4 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center z-10 transition-colors backdrop-blur-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="w-full h-64 sm:h-80 lg:h-96 bg-gradient-to-br from-accent/20 to-purple-500/20 relative flex-shrink-0">
                                <template x-if="selectedProject.image_url">
                                    <img :src="selectedProject.image_url" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedProject.image_url">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-accent/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    </div>
                                </template>
                            </div>

                            <div class="p-6 sm:p-8 overflow-y-auto">
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white" x-text="selectedProject.title"></h2>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <template x-if="selectedProject.github_url">
                                            <a :href="selectedProject.github_url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-xl transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                <span class="hidden sm:inline">GitHub</span>
                                            </a>
                                        </template>
                                        <template x-if="selectedProject.project_url">
                                            <a :href="selectedProject.project_url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-accent hover:bg-indigo-600 text-white text-sm font-medium rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                <span class="hidden sm:inline">Live Demo</span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                                
                                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Tech Stack</h3>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <template x-for="tech in selectedProject.tech_stack">
                                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-2">
                                            <template x-if="tech.icon">
                                                <i :class="tech.icon + ' colored text-lg'"></i>
                                            </template>
                                            <span x-text="tech.name"></span>
                                        </span>
                                    </template>
                                </div>

                                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Project Description</h3>
                                <div class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap" x-html="selectedProject.description"></div>
                            </div>
                        </div>
                    </div>
                </template>
            @else
                <div class="fade-up bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No projects added yet. Manage in Studio.</p>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- CERTIFICATES SECTION                      -->
    <!-- ========================================= -->
    @php
        $certificatesData = $user->certificates->map(function($c) {
            return [
                'title' => $c->title,
                'issuer' => $c->issuer,
                'date' => \Carbon\Carbon::parse($c->date)->format('M Y'),
                'description' => $c->description,
                'image_url' => $c->image_path ? asset('storage/' . $c->image_path) : null,
            ];
        })->values();
    @endphp
    @if($visibility['certificates'] ?? true)
    <section id="certificates" class="py-20 lg:py-32 relative z-10" x-data="{
        selectedCert: null,
        expanded: false,
        certificates: @js($certificatesData)
    }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
                <div>
                    <div class="fade-up mb-4">
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">Achievements</span>
                    </div>
                    <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white">Certificates & Awards</h2>
                </div>
                @if($user->certificates->count() > 8)
                    <button @click="expanded = !expanded" class="fade-up inline-flex items-center gap-2 px-5 py-2.5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-accent hover:border-accent/30 transition-all">
                        <span x-text="expanded ? 'Show Less' : 'View All'"></span>
                        <svg class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                @endif
            </div>

            @if($user->certificates->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($user->certificates as $index => $cert)
                        <div class="fade-up card-hover bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-sm group cursor-pointer"
                             x-show="expanded || {{ $index }} < 8"
                             style="transition-delay: {{ $index * 100 }}ms"
                             @click="selectedCert = certificates[{{ $index }}]">
                            <!-- Certificate Header Image -->
                            <div class="h-28 sm:h-36 bg-gradient-to-br from-accent/20 to-purple-500/20 flex items-center justify-center relative overflow-hidden">
                                @if($cert->image_path)
                                    <img src="{{ asset('storage/' . $cert->image_path) }}" alt="{{ $cert->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <svg class="w-10 h-10 text-accent/50 group-hover:scale-110 transition-transform duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                @endif
                            </div>
                            <!-- Certificate Info -->
                            <div class="p-4 sm:p-5">
                                <h3 class="font-bold text-slate-900 dark:text-white leading-tight mb-1 line-clamp-2" title="{{ $cert->title }}">{{ $cert->title }}</h3>
                                <p class="text-xs text-accent font-medium line-clamp-1 mb-1">{{ $cert->issuer }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($cert->date)->format('M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Certificate Modal -->
                <template x-teleport="body">
                    <div x-show="selectedCert !== null" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="selectedCert = null"></div>

                        <div class="relative w-full max-w-3xl bg-white dark:bg-darkCard rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                            
                            <button @click="selectedCert = null" class="absolute top-4 right-4 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center z-10 transition-colors backdrop-blur-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="w-full h-48 sm:h-72 bg-gradient-to-br from-accent/20 to-purple-500/20 relative flex-shrink-0">
                                <template x-if="selectedCert.image_url">
                                    <img :src="selectedCert.image_url" class="w-full h-full object-contain bg-slate-900/5 backdrop-blur-sm p-2 sm:p-4">
                                </template>
                                <template x-if="!selectedCert.image_url">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-accent/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                    </div>
                                </template>
                            </div>

                            <div class="p-5 sm:p-8 overflow-y-auto">
                                <div class="mb-5">
                                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-2" x-text="selectedCert.title"></h2>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-3 py-1 bg-accent/10 text-accent rounded-lg text-sm font-medium" x-text="selectedCert.issuer"></span>
                                        <span class="text-sm text-slate-500 dark:text-slate-400" x-text="selectedCert.date"></span>
                                    </div>
                                </div>
                                
                                <template x-if="selectedCert.description">
                                    <div>
                                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Description</h3>
                                        <div class="text-slate-600 dark:text-slate-400 prose prose-sm prose-slate dark:prose-invert max-w-none" x-html="selectedCert.description"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            @else
                <div class="fade-up bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No certificates added yet. Manage in Studio.</p>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- EXPERIENCES SECTION                       -->
    <!-- ========================================= -->
    @if($visibility['experiences'] ?? true)
    <section id="experiences" class="py-20 lg:py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Label -->
            <div class="fade-up mb-4">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">My Journey</span>
            </div>
            <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-12">Work Experience</h2>

            @if($user->experiences->count() > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-6 lg:left-8 top-0 bottom-0 w-px bg-gradient-to-b from-accent via-purple-500 to-transparent"></div>

                    <div class="space-y-8">
                        @foreach($user->experiences->sortByDesc('start_date') as $index => $exp)
                            <div class="fade-up relative pl-16 lg:pl-20" style="transition-delay: {{ $index * 120 }}ms">
                                <!-- Timeline Dot -->
                                <div class="absolute left-[14px] lg:left-[22px] top-2 w-5 h-5 rounded-full bg-accent/20 border-[3px] border-accent flex items-center justify-center z-10">
                                    <div class="w-1.5 h-1.5 rounded-full bg-accent"></div>
                                </div>

                                <!-- Experience Card -->
                                <div class="card-hover bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-sm group">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-accent transition-colors">{{ $exp->role }}</h3>
                                            <p class="text-accent font-semibold text-sm">{{ $exp->company_name }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium {{ $exp->end_date ? 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400' : 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} — {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($exp->description)
                                        <div class="text-sm text-slate-600 dark:text-slate-400 prose prose-sm prose-slate dark:prose-invert max-w-none">
                                            {!! $exp->description !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="fade-up bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No work experience added yet. Manage in Studio.</p>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- CONTACT ME SECTION                        -->
    <!-- ========================================= -->
    @if($visibility['contact'] ?? true)
    <section id="contact" class="py-20 lg:py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Label -->
            <div class="fade-up mb-4">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-accent">Contact Me</span>
            </div>
            <h2 class="fade-up text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-4">Get In Touch</h2>
            <p class="fade-up text-slate-500 dark:text-slate-400 mb-12 max-w-lg">Have a project in mind or want to work together? Feel free to send me a message.</p>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Contact Info -->
                <div class="lg:col-span-2 space-y-4 fade-up">
                    @foreach([
                        ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => $user->email, 'type' => 'Email'],
                        ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => $user->phone ?: '+62 xxx xxxx xxxx', 'type' => 'Phone'],
                        ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => $user->location ?: 'Not specified', 'type' => 'Location'],
                    ] as $contact)
                        <div class="flex items-center gap-4 bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-xl p-5 card-hover">
                            <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $contact['icon'] }}"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mb-0.5">{{ $contact['type'] }}</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $contact['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-3 fade-up">
                    <div class="bg-white/50 dark:bg-darkCard/50 backdrop-blur-2xl border border-slate-200 dark:border-white/10 rounded-2xl p-8 shadow-xl">
                        <form class="space-y-5" x-data="{ 
                            sent: false, 
                            sending: false,
                            error: null,
                            formData: { name: '', email: '', subject: '', message: '' },
                            async submitForm() {
                                this.sending = true;
                                this.error = null;
                                try {
                                    let res = await fetch('{{ route("portfolio.contact", ["username" => $user->username]) }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify(this.formData)
                                    });
                                    let data = await res.json();
                                    if(data.success) {
                                        this.sent = true;
                                        this.formData = { name: '', email: '', subject: '', message: '' };
                                        setTimeout(() => this.sent = false, 5000);
                                    } else {
                                        this.error = data.error || 'Failed to send message.';
                                    }
                                } catch(e) {
                                    this.error = 'Network error occurred.';
                                }
                                this.sending = false;
                            }
                        }" @submit.prevent="submitForm">
                            <template x-if="error">
                                <div class="bg-red-100 text-red-600 p-3 rounded-xl text-sm mb-4" x-text="error"></div>
                            </template>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <input type="text" x-model="formData.name" placeholder="Your Name" required
                                           class="w-full bg-white/70 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <input type="email" x-model="formData.email" placeholder="Your Email" required
                                           class="w-full bg-white/70 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm">
                                </div>
                            </div>
                            <div>
                                <input type="text" x-model="formData.subject" placeholder="Subject" required
                                       class="w-full bg-white/70 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <textarea rows="5" x-model="formData.message" placeholder="Your Message" required
                                          class="w-full bg-white/70 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all resize-none text-sm"></textarea>
                            </div>
                            <button type="submit" :disabled="sending"
                                    class="w-full py-4 bg-gradient-to-r from-accent to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-accent/30 hover:shadow-accent/50 flex items-center justify-center gap-2 hover:scale-[1.01] disabled:opacity-70 disabled:hover:scale-100"
                                    :class="sent ? 'from-green-500 to-emerald-600' : ''">
                                <template x-if="!sent && !sending">
                                    <span class="flex items-center gap-2">
                                        Send Message
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </span>
                                </template>
                                <template x-if="sending">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </span>
                                </template>
                                <template x-if="sent">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Message Sent!
                                    </span>
                                </template>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ========================================= -->
    <!-- FOOTER                                    -->
    <!-- ========================================= -->
    <footer class="relative z-10 border-t border-slate-200 dark:border-white/5 bg-white/30 dark:bg-darkBg/50 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500 dark:text-slate-500">
                    &copy; {{ date('Y') }} {{ $user->name }}. All rights reserved.
                </p>
                <a href="#home" class="w-10 h-10 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all duration-200 hover:scale-110"
                   title="Back to top">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- ========================================= -->
    <!-- SCRIPTS                                   -->
    <!-- ========================================= -->
    <script>
        // Intersection Observer for fade-up animation
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

            // Active section tracking
            const sections = document.querySelectorAll('section[id]');
            const navObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        // Update Alpine's activeSection via Alpine.js 3 data stack
                        const bodyData = Alpine.$data(document.body);
                        if (bodyData) bodyData.activeSection = id;
                    }
                });
            }, { threshold: 0.2 });

            sections.forEach(section => navObserver.observe(section));

            // Navbar background on scroll
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('shadow-lg', 'dark:shadow-black/20');
                } else {
                    navbar.classList.remove('shadow-lg', 'dark:shadow-black/20');
                }
            });
        });
    </script>
</body>
</html>
