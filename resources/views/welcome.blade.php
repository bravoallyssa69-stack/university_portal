<!DOCTYPE html>
<html lang="en" class="scroll-smooth h-full" :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : true, toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); }, mobileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University of Binalonan - U-Bi Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav-dark { background: rgba(15, 23, 42, 0.92); backdrop-filter: blur(16px); }
        .glass-nav-light { background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); }
        .hero-glow { background: radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.18) 0%, rgba(245, 158, 11, 0.08) 50%, transparent 100%); }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">

    <!-- Top Navigation Header -->
    <nav class="sticky top-0 z-50 border-b transition-colors duration-300"
         :class="darkMode ? 'glass-nav-dark border-slate-800/80' : 'glass-nav-light border-slate-200/80'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            
            <!-- ENHANCED BRAND LOGO BADGE (Eksakto sa picture) -->
            <a href="/" class="flex items-center gap-3.5 group">
                <div class="relative p-[3px] rounded-2xl bg-gradient-to-tr from-emerald-400 via-teal-300 to-amber-300 shadow-xl shadow-emerald-500/20 group-hover:scale-105 group-hover:shadow-emerald-500/40 transition-all duration-300">
                    <div class="w-12 h-12 bg-slate-950 rounded-[13px] flex flex-col items-center justify-center p-1 border border-slate-800">
                        <i class="fa-solid fa-graduation-cap text-emerald-400 text-sm mb-0.5 transform group-hover:-rotate-6 transition-transform"></i>
                        <span class="text-[12px] font-black tracking-tight leading-none text-amber-400 font-sans">U-Bi</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-extrabold tracking-wide block leading-none" :class="darkMode ? 'text-white' : 'text-slate-900'">UNIVERSITY OF BINALONAN</span>
                    
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-8 text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                <a href="#home" class="hover:text-emerald-500 transition-colors">Home</a>
                <a href="#about" class="hover:text-emerald-500 transition-colors">About</a>
                <a href="#courses" class="hover:text-emerald-500 transition-colors">Courses</a>
                <a href="#contact" class="hover:text-emerald-500 transition-colors">Contact Us</a>
            </div>

            <!-- Action Buttons -->
            <div class="hidden md:flex items-center gap-3">
                <button @click="toggleTheme()" class="p-2.5 rounded-xl border transition-all duration-300 flex items-center justify-center shadow-sm hover:scale-105" :class="darkMode ? 'bg-slate-800 text-amber-400 border-slate-700 hover:bg-slate-700' : 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200'" title="Toggle Light/Dark Mode">
                    <i class="fa-solid text-sm" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-lg shadow-emerald-600/25 transition-all active:scale-95 flex items-center gap-2">
                        <span>Go to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold px-4 py-3 rounded-xl transition" :class="darkMode ? 'text-slate-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100'">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-lg shadow-emerald-600/25 transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="fa-solid fa-user-plus text-[10px]"></i>
                        <span>Register Now</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggles -->
            <div class="flex items-center gap-2 md:hidden">
                <button @click="toggleTheme()" class="p-2.5 rounded-xl border transition-all duration-300 flex items-center justify-center shadow-sm" :class="darkMode ? 'bg-slate-800 text-amber-400 border-slate-700' : 'bg-slate-100 text-slate-700 border-slate-300'">
                    <i class="fa-solid text-sm" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl border transition-colors" :class="darkMode ? 'text-slate-300 hover:text-white bg-slate-900 border-slate-800' : 'text-slate-700 hover:text-slate-900 bg-slate-100 border-slate-200'">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-collapse class="md:hidden border-b px-6 py-6 space-y-3 text-xs font-semibold transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-300' : 'bg-white border-slate-200 text-slate-700'">
            <a href="#home" @click="mobileMenuOpen = false" class="block py-2 hover:text-emerald-500">Home</a>
            <a href="#about" @click="mobileMenuOpen = false" class="block py-2 hover:text-emerald-500">About</a>
            <a href="#courses" @click="mobileMenuOpen = false" class="block py-2 hover:text-emerald-500">Courses</a>
            <a href="#contact" @click="mobileMenuOpen = false" class="block py-2 hover:text-emerald-500">Contact Us</a>
            <div class="pt-4 border-t flex flex-col gap-2.5" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full bg-emerald-600 text-center text-white py-3 rounded-xl font-bold shadow-md">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-3 rounded-xl font-bold border transition" :class="darkMode ? 'bg-slate-800 text-white border-slate-700' : 'bg-slate-100 text-slate-800 border-slate-300'">Sign In</a>
                    <a href="{{ route('register') }}" class="w-full bg-emerald-600 text-center text-white py-3 rounded-xl font-bold shadow-md">Register Now</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative py-24 lg:py-36 px-4 sm:px-6 lg:px-8 overflow-hidden hero-glow transition-colors duration-300" :class="darkMode ? 'bg-gradient-to-br from-emerald-950 via-teal-950 to-slate-950 text-white' : 'bg-gradient-to-br from-emerald-50 via-teal-50 to-slate-100 text-slate-900'">
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-amber-500/10 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 border text-xs font-bold px-4 py-2 rounded-full mb-6 uppercase tracking-wider shadow-inner" :class="darkMode ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-emerald-100 text-emerald-700 border-emerald-300'">
                <i class="fa-solid fa-graduation-cap text-amber-500"></i> Quality Higher Education in Binalonan, Pangasinan
            </span>
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] mb-6">
                Welcome to <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-teal-400 to-amber-500">University of Binalonan (U-Bi)</span>
            </h1>
            <p class="text-sm sm:text-base max-w-2xl mx-auto mb-10 leading-relaxed font-normal transition-colors" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                Empowering minds and shaping futures in Binalonan, Pangasinan. Access your academic records, course enrollments, and real-time security alerts.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm px-9 py-4 rounded-2xl shadow-xl shadow-emerald-600/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>Access Student Dashboard</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm px-9 py-4 rounded-2xl shadow-xl shadow-emerald-600/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>Register Now</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto border font-bold text-sm px-9 py-4 rounded-2xl transition-all shadow-lg backdrop-blur-md" :class="darkMode ? 'bg-slate-900/90 hover:bg-slate-900 border-slate-800 text-white' : 'bg-white hover:bg-slate-50 border-slate-300 text-slate-800'">
                        Student Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="border-y py-10 px-4 sm:px-6 lg:px-8 relative z-20 shadow-2xl transition-colors duration-300" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200'">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-3 border-r last:border-none" :class="darkMode ? 'border-slate-800/60' : 'border-slate-200'">
                <h3 class="text-3xl sm:text-4xl font-black text-emerald-500">100%</h3>
                <p class="text-xs font-bold mt-1 uppercase tracking-widest" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Midwifery Passers</p>
            </div>
            <div class="p-3 border-r last:border-none" :class="darkMode ? 'border-slate-800/60' : 'border-slate-200'">
                <h3 class="text-3xl sm:text-4xl font-black text-amber-500">75%+</h3>
                <p class="text-xs font-bold mt-1 uppercase tracking-widest" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Criminologist Board Rate</p>
            </div>
            <div class="p-3 border-r last:border-none" :class="darkMode ? 'border-slate-800/60' : 'border-slate-200'">
                <h3 class="text-3xl sm:text-4xl font-black text-emerald-500">10+</h3>
                <p class="text-xs font-bold mt-1 uppercase tracking-widest" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Degree Programs</p>
            </div>
            <div class="p-3">
                <h3 class="text-3xl sm:text-4xl font-black text-emerald-500">100%</h3>
                <p class="text-xs font-bold mt-1 uppercase tracking-widest" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Secure Portal Logging</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 text-emerald-500 text-xs font-bold uppercase tracking-widest bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                    <i class="fa-solid fa-landmark"></i> About U-Bi (Binalonan Campus)
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight" :class="darkMode ? 'text-white' : 'text-slate-900'">
                    The Center of Quality Higher Education in Binalonan
                </h2>
                <p class="text-sm leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    Located along <strong>McArthur Highway, Brgy. Canarvacanan, Binalonan, Pangasinan</strong>, <strong>University of Binalonan (U-Bi)</strong> is dedicated to nurturing competent leaders and expert professionals in Information Technology, Teacher Education, Criminology, Business Administration, and Health Sciences.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-5 rounded-2xl border transition-colors" :class="darkMode ? 'bg-slate-900/80 border-slate-800/80' : 'bg-white border-slate-200 shadow-sm'">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold mb-3 text-lg">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4 class="text-xs font-bold uppercase tracking-wider" :class="darkMode ? 'text-white' : 'text-slate-900'">Protected Portal</h4>
                        <p class="text-xs mt-1" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Integrated with Google reCAPTCHA v2 and security alert emails.</p>
                    </div>
                    <div class="p-5 rounded-2xl border transition-colors" :class="darkMode ? 'bg-slate-900/80 border-slate-800/80' : 'bg-white border-slate-200 shadow-sm'">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold mb-3 text-lg">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h4 class="text-xs font-bold uppercase tracking-wider" :class="darkMode ? 'text-white' : 'text-slate-900'">Real-Time Activity</h4>
                        <p class="text-xs mt-1" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Automatically logs <code>last_login_at</code> timestamps into database records.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 p-8 sm:p-10 rounded-3xl border shadow-2xl relative transition-colors" :class="darkMode ? 'bg-gradient-to-tr from-emerald-950/40 via-slate-900 to-slate-900 border-slate-800' : 'bg-gradient-to-tr from-emerald-50 via-white to-white border-slate-200 shadow-xl'">
                <h3 class="text-lg font-extrabold mb-6 flex items-center gap-2" :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <i class="fa-solid fa-eye text-amber-500"></i> Institutional Vision & Mission
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 rounded-2xl border transition-colors" :class="darkMode ? 'bg-slate-950/70 border-slate-800' : 'bg-slate-50 border-slate-200'">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">V</div>
                        <div>
                            <h4 class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">Vision Statement</h4>
                            <p class="text-[11px] mt-0.5" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">"The best holistic higher education institution known to cultivate excellent and virtuous individuals as catalysts of progress."</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl border transition-colors" :class="darkMode ? 'bg-slate-950/70 border-slate-800' : 'bg-slate-50 border-slate-200'">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">M</div>
                        <div>
                            <h4 class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">Mission Statement</h4>
                            <p class="text-[11px] mt-0.5" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">To render sound decisions, generate new scholarly works, and practice professions efficiently for sustainable community development.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full border-t transition-colors" :class="darkMode ? 'border-slate-800/80' : 'border-slate-200'">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest bg-emerald-500/15 px-3 py-1 rounded-full border border-emerald-500/25">Courses Offered</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mt-3" :class="darkMode ? 'text-white' : 'text-slate-900'">Academic Programs at U-Bi</h2>
            <p class="text-sm mt-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Explore the official degree programs offered at Binalonan Campus.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-laptop-code"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Information Technology</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Software Architecture, Web Systems, Cloud Infrastructure, and Network Servicing.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-teal-500/10 text-teal-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-chalkboard-user"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Education</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Bachelor in Elementary Education and Secondary Education majors.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-briefcase"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Business Administration</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Financial Management, Marketing, and Enterprise Management.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-red-500/10 text-red-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-shield-cat"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Criminology</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Law enforcement, criminalistics, and forensic science studies.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-rose-500/10 text-rose-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-user-nurse"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Midwifery</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Healthcare clinical training with a consistent 100% Board Exam passing record.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="p-8 rounded-3xl border hover:border-emerald-500/50 transition-all duration-300 group hover:-translate-y-1 shadow-xl" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-14 h-14 bg-indigo-500/10 text-indigo-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform shadow-inner"><i class="fa-solid fa-utensils"></i></div>
                <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">Hospitality Management</h3>
                <p class="text-xs leading-relaxed mb-6" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Hotel and Restaurant Management, culinary arts, and tourism services.</p>
                <a href="{{ route('register') }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"><span>Apply For This Course</span> <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full border-t transition-colors" :class="darkMode ? 'border-slate-800/80' : 'border-slate-200'">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest bg-emerald-500/15 px-3 py-1 rounded-full border border-emerald-500/25">Get In Touch</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mt-3" :class="darkMode ? 'text-white' : 'text-slate-900'">Contact University of Binalonan</h2>
            <p class="text-sm mt-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Have questions or need assistance regarding admissions or portal accounts? Contact us below.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-6 rounded-3xl border transition-colors" :class="darkMode ? 'bg-slate-900/80 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h4 class="font-bold text-sm mb-1" :class="darkMode ? 'text-white' : 'text-slate-900'">Email Support</h4>
                <p class="text-xs text-slate-400">support@U-Bi.edu.ph</p>
            </div>
            <div class="p-6 rounded-3xl border transition-colors" :class="darkMode ? 'bg-slate-900/80 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center font-bold mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h4 class="font-bold text-sm mb-1" :class="darkMode ? 'text-white' : 'text-slate-900'">Hotline</h4>
                <p class="text-xs text-slate-400">09624470505</p>
            </div>
            <div class="p-6 rounded-3xl border transition-colors" :class="darkMode ? 'bg-slate-950/80 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-500 flex items-center justify-center font-bold mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h4 class="font-bold text-sm mb-1" :class="darkMode ? 'text-white' : 'text-slate-900'">Campus Address</h4>
                <p class="text-xs text-slate-400">McArthur Highway, Brgy. Canarvacanan, Binalonan, Pangasinan</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 px-4 sm:px-6 lg:px-8 border-t mt-auto transition-colors" :class="darkMode ? 'bg-slate-900 text-slate-400 border-slate-800' : 'bg-white text-slate-600 border-slate-200 shadow-inner'">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 pb-12 border-b text-xs" :class="darkMode ? 'border-slate-800/80' : 'border-slate-200'">
            <div>
                <!-- ENHANCED FOOTER BRAND LOGO -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-[2px] rounded-xl bg-gradient-to-tr from-emerald-400 via-teal-300 to-amber-300">
                        <div class="w-9 h-9 bg-slate-950 rounded-[10px] flex items-center justify-center font-black text-amber-400 text-xs">U-Bi</div>
                    </div>
                    <span class="text-sm font-extrabold tracking-wide" :class="darkMode ? 'text-white' : 'text-slate-900'">UNIVERSITY OF BINALONAN</span>
                </div>
                <p class="leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Transcending towards holistic development, leadership, and progress.</p>
            </div>
            <div>
                <h4 class="font-bold text-sm mb-4 uppercase tracking-wider" :class="darkMode ? 'text-white' : 'text-slate-900'">Quick Links</h4>
                <ul class="space-y-2.5">
                    <li><a href="#home" class="hover:text-emerald-500 transition">Home</a></li>
                    <li><a href="#about" class="hover:text-emerald-500 transition">About U-Bi</a></li>
                    <li><a href="#courses" class="hover:text-emerald-500 transition">Courses Offered</a></li>
                    <li><a href="#contact" class="hover:text-emerald-500 transition">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm mb-4 uppercase tracking-wider" :class="darkMode ? 'text-white' : 'text-slate-900'">Contact Information</h4>
                <p class="mb-2"><i class="fa-solid fa-envelope mr-2 text-emerald-500"></i> support@U-Bi.edu.ph</p>
                <p class="mb-2"><i class="fa-solid fa-phone mr-2 text-emerald-500"></i> 09624470505</p>
                <p><i class="fa-solid fa-location-dot mr-2 text-emerald-500"></i> McArthur Highway, Brgy. Canarvacanan, Binalonan, Pangasinan</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto text-center pt-8 text-[11px] font-medium text-slate-500">
            &copy; {{ date('Y') }} University of Binalonan (U-Bi.edu.ph). All rights reserved.
        </div>
    </footer>

</body>
</html>