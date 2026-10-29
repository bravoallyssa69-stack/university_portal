<!DOCTYPE html>
<html lang="en" class="h-full" :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : true, toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University of Binalonan - Register Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    {!! NoCaptcha::renderJs() !!}
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    
    <!-- Decorative Background Ambient Glows -->
    <div class="fixed top-1/4 -left-20 w-80 h-80 bg-emerald-600/20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="fixed bottom-1/4 -right-20 w-80 h-80 bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Floating Theme Switcher Button -->
    <div class="absolute top-6 right-6 z-20">
        <button @click="toggleTheme()" class="p-2.5 rounded-xl border transition-all shadow-sm flex items-center justify-center hover:scale-105" :class="darkMode ? 'bg-slate-800 text-amber-400 border-slate-700' : 'bg-white text-slate-700 border-slate-300 shadow-sm'" title="Toggle Light/Dark Mode">
            <i class="fa-solid text-sm" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
        </button>
    </div>

    <div class="max-w-md w-full backdrop-blur-xl rounded-3xl shadow-2xl p-8 border relative z-10 my-6 transition-colors"
         :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200 shadow-xl'">
        
        <!-- Header & U-Bi Branding -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-400 to-amber-400 p-[2px] shadow-lg shadow-emerald-950/50 mb-3 group transition-transform hover:scale-105">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                    <span class="font-black text-amber-400 text-lg tracking-tight">U-Bi</span>
                </div>
            </div>
            <h1 class="text-2xl font-black tracking-tight" :class="darkMode ? 'text-white' : 'text-slate-900'">University of Binalonan</h1>
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mt-1">Student Registration Portal</p>
        </div>

        <!-- Validation Errors Display -->
        @if ($errors->any())
            <div class="mb-5 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-xs"></i>
                    <p class="text-xs font-bold text-rose-300">Registration Errors</p>
                </div>
                <ul class="text-xs text-rose-300/90 space-y-1 pl-5 list-disc font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Student ID Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Student ID</label>
                <div class="relative">
                    <input type="text" name="student_id" value="{{ old('student_id') }}" placeholder="2024-00123" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-id-card text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
            </div>

            <!-- Full Name Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Full Name</label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-user text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
            </div>

            <!-- Select Course / Program Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Select Course / Program</label>
                <div class="relative">
                    <select name="course" required 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium appearance-none cursor-pointer"
                            :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 focus:border-emerald-500'">
                        <option value="BSIT" {{ old('course') == 'BSIT' ? 'selected' : '' }}>Bachelor of Science in Information Technology (BSIT)</option>
                        <option value="Education" {{ old('course') == 'Education' ? 'selected' : '' }}>Bachelor of Education</option>
                        <option value="Business Administration" {{ old('course') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                        <option value="Criminology" {{ old('course') == 'Criminology' ? 'selected' : '' }}>Criminology</option>
                        <option value="Midwifery" {{ old('course') == 'Midwifery' ? 'selected' : '' }}>Midwifery</option>
                        <option value="Hospitality Management" {{ old('course') == 'Hospitality Management' ? 'selected' : '' }}>Hospitality Management</option>
                    </select>
                    <i class="fa-solid fa-graduation-cap text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                    <i class="fa-solid fa-chevron-down text-slate-500 absolute right-3.5 top-3.5 text-xs pointer-events-none"></i>
                </div>
            </div>

            <!-- Select Block / Section Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Select Block / Section</label>
                <div class="relative">
                    <select name="block" required 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium appearance-none cursor-pointer"
                            :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 focus:border-emerald-500'">
                        <option value="Block A" {{ old('block') == 'Block A' ? 'selected' : '' }}>Block A</option>
                        <option value="Block B" {{ old('block') == 'Block B' ? 'selected' : '' }}>Block B</option>
                        <option value="Block C" {{ old('block', 'Block C') == 'Block C' ? 'selected' : '' }}>Block C</option>
                        <option value="Block D" {{ old('block') == 'Block D' ? 'selected' : '' }}>Block D</option>
                    </select>
                    <i class="fa-solid fa-layer-group text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                    <i class="fa-solid fa-chevron-down text-slate-500 absolute right-3.5 top-3.5 text-xs pointer-events-none"></i>
                </div>
            </div>

            <!-- Valid Gmail Address Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Valid Gmail Address</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="student@gmail.com" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-envelope text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
                <p class="text-[10px] mt-1 flex items-center gap-1" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <i class="fa-solid fa-circle-info text-emerald-500"></i>
                    Must be an active @gmail.com account.
                </p>
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Password</label>
                <div class="relative">
                    <input type="password" name="password" placeholder="••••••••" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-lock text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" placeholder="••••••••" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-shield-halved text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
            </div>

            <!-- reCAPTCHA Widget -->
            <div class="flex justify-center py-2">
                {!! NoCaptcha::display() !!}
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-lg shadow-emerald-600/25 text-sm active:scale-[0.98] flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Register Account</span>
            </button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-xs mt-6 font-medium" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-emerald-500 hover:text-emerald-400 font-bold hover:underline transition-colors">Sign in</a>
        </p>
        <div class="text-center mt-3 text-xs">
            <a href="/" class="text-emerald-500 hover:underline font-semibold"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Home</a>
        </div>
    </div>

</body>
</html>