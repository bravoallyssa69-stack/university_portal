<!DOCTYPE html>
<html lang="en" class="h-full" :class="darkMode ? 'dark bg-slate-950' : 'bg-slate-50'" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : true, toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U-Bi Portal - Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">

    <!-- Floating Theme Switcher Button -->
    <div class="absolute top-6 right-6 z-20">
        <button @click="toggleTheme()" class="p-2.5 rounded-xl border transition-all shadow-sm flex items-center justify-center hover:scale-105" :class="darkMode ? 'bg-slate-800 text-amber-400 border-slate-700' : 'bg-white text-slate-700 border-slate-300 shadow-sm'" title="Toggle Light/Dark Mode">
            <i class="fa-solid text-sm" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-slate-600'"></i>
        </button>
    </div>

    <div class="w-full max-w-md border rounded-3xl p-8 shadow-2xl space-y-6 transition-colors"
         :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900 shadow-xl'">
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-black" :class="darkMode ? 'text-white' : 'text-slate-900'">Reset Password</h1>
            <p class="text-xs" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">Enter your Gmail and create a new password for your account.</p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-bold uppercase mb-1" :class="darkMode ? 'text-slate-400' : 'text-slate-700'">Email Address</label>
                <input type="email" name="email" value="{{ request('email') }}" required
                       class="w-full border rounded-xl px-4 py-2.5 text-xs outline-none transition"
                       :class="darkMode ? 'bg-slate-950 border-slate-800 text-white focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 focus:border-emerald-500'">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase mb-1" :class="darkMode ? 'text-slate-400' : 'text-slate-700'">New Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-xl px-4 py-2.5 text-xs outline-none transition"
                       :class="darkMode ? 'bg-slate-950 border-slate-800 text-white focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 focus:border-emerald-500'">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase mb-1" :class="darkMode ? 'text-slate-400' : 'text-slate-700'">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border rounded-xl px-4 py-2.5 text-xs outline-none transition"
                       :class="darkMode ? 'bg-slate-950 border-slate-800 text-white focus:border-emerald-500' : 'bg-slate-50 border-slate-300 text-slate-900 focus:border-emerald-500'">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-xs transition shadow-lg shadow-emerald-600/30">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>