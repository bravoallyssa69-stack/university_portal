<!DOCTYPE html>
<html lang="en" class="h-full" :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : true, toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University of Binalonan - Forgot Password</title>
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
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    
    <!-- Ambient Background Glows -->
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
            <p class="text-xs font-medium mt-1" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Enter your registered @gmail.com address to receive a password reset link.</p>
        </div>

        <!-- Status Message -->
        @if (session('status'))
            <div class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-2.5">
                <i class="fa-solid fa-paper-plane text-emerald-400 text-xs flex-shrink-0"></i>
                <p class="text-xs text-emerald-300 font-semibold">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Validation Errors Display -->
        @if ($errors->any())
            <div class="mb-5 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-400 text-xs flex-shrink-0"></i>
                <p class="text-xs text-rose-300 font-semibold">{{ $errors->first() }}</p>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider mb-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">Email Address</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="student@gmail.com" required 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border outline-none transition text-sm font-medium"
                           :class="darkMode ? 'bg-slate-950/80 border-slate-800 text-white placeholder:text-slate-500 focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 placeholder:text-slate-400 focus:border-emerald-500'">
                    <i class="fa-solid fa-envelope text-slate-500 absolute left-3.5 top-3.5 text-xs"></i>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-lg shadow-emerald-600/25 text-sm active:scale-[0.98] flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span> Reset Link</span>
            </button>
        </form>

        <!-- Back to Login Link -->
        <p class="text-center text-xs mt-6 font-medium" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
            Remembered your password? 
            <a href="{{ route('login') }}" class="text-emerald-500 hover:text-emerald-400 font-bold hover:underline transition-colors flex items-center justify-center gap-1 mt-1 inline-flex">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Login</span>
            </a>
        </p>
    </div>

</body>
</html>