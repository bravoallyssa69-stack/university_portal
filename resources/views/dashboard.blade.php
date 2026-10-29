<!DOCTYPE html>
<html lang="en" class="h-full" :class="darkMode ? 'bg-slate-950' : 'bg-slate-100'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - University of Binalonan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased transition-colors duration-200" 
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-100 text-slate-900'" 
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'light' ? false : true,
          sidebarOpen: false, 
          currentTab: 'home', 
          searchSchedule: '', 
          profileModal: false,
          userCourse: '{{ Auth::user()->course ?? 'BSIT' }}',
          userBlock: '{{ Auth::user()->block ?? 'Block C' }}',

          // 8 Na Natatanging Subjects at Instructors bawat Kurso
          schedules: {
              'BSIT': [
                  { code: 'SIA 101', title: 'Systems Integration and Architecture 1', time: 'TH (11:00 AM - 01:00 PM)', instructor: 'danao riezza', room: 'ONLINE' },
                  { code: 'GE ELEC 4', title: 'Technical Writing', time: 'T,TH (01:30 PM - 03:00 PM)', instructor: 'MALAGAYO NOEMIE', room: 'Annex D RM 6' },
                  { code: 'UED 101', title: 'User Experience Design', time: 'TH (08:00 AM - 11:00 AM)', instructor: 'ANGAN-ANGAN MAJIE BASOBAS', room: 'DIGITEL IT RM 3' },
                  { code: 'CC 106', title: 'App Dev. And Emerging Technologies', time: 'F (08:00 AM - 10:00 AM)', instructor: 'VALDEZ RICHELLE JANE PAZ', room: 'DIGITEL IT RM 2' },
                  { code: 'NET 102', title: 'Networking 2', time: 'M (09:00 AM - 11:00 AM)', instructor: 'ABELLERA APRIL ANNE MONTIPALCO', room: 'ONLINE' },
                  { code: 'PF 102', title: 'Event Driven Programming', time: 'F (03:00 PM - 05:00 PM)', instructor: 'ABELLERA APRIL ANNE MONTIPALCO', room: 'DIGITEL IT RM 2' },
                  { code: 'IT ELEC 2', title: 'IT Elective 2 (Social & Prof. Issues)', time: 'W (04:00 PM - 06:00 PM)', instructor: 'Collado Floresvein Elamparo', room: 'ONLINE' },
                  { code: 'CAP 101', title: 'Capstone Project and Research 1', time: 'S (01:00 PM - 04:00 PM)', instructor: 'Ramos Mark Anthony', room: 'DIGITEL IT RM 1' }
              ],
              'Criminology': [
                  { code: 'CRIM 101', title: 'Introduction to Criminology', time: 'M (08:00 AM - 11:00 AM)', instructor: 'Dr. Dela Cruz Juan', room: 'Crim Hall RM 1' },
                  { code: 'LEA 102', title: 'Law Enforcement Administration', time: 'M (01:00 PM - 04:00 PM)', instructor: 'Capt. Santos Roberto', room: 'Crim Hall RM 2' },
                  { code: 'CDI 103', title: 'Crime Detection and Investigation', time: 'T (08:00 AM - 11:00 AM)', instructor: 'Insp. Mendoza Maria', room: 'Crim Lab RM 1' },
                  { code: 'FOR 101', title: 'Personal Identification & Dactyloscopy', time: 'T (01:00 PM - 4:00 PM)', instructor: 'Dr. Reyes Gabriel', room: 'Forensic Lab' },
                  { code: 'CORR 1', title: 'Institutional Corrections', time: 'W (08:00 AM - 11:00 AM)', instructor: 'Prof. Soriano Fernando', room: 'Crim Hall RM 3' },
                  { code: 'SOC 102', title: 'Human Behavior and Victimology', time: 'Th (08:00 AM - 11:00 AM)', instructor: 'Prof. Aguilar Teresa', room: 'Crim Hall RM 1' },
                  { code: 'TAC 101', title: 'Marksmanship and Defensive Tactics', time: 'F (08:00 AM - 11:00 AM)', instructor: 'Sgt. Bautista Pedro', room: 'Firing Range / Gym' },
                  { code: 'CLJ 104', title: 'Criminal Law and Procedure', time: 'F (01:00 PM - 4:00 PM)', instructor: 'Atty. Navarro Carlos', room: 'Crim Hall RM 4' }
              ],
              'Education': [
                  { code: 'EDUC 101', title: 'The Child and Adolescent Learners', time: 'M (08:00 AM - 11:00 AM)', instructor: 'Dr. Alonzo Elena', room: 'Educ Bldg RM 101' },
                  { code: 'EDUC 102', title: 'The Teaching Profession', time: 'M (01:00 PM - 4:00 PM)', instructor: 'Prof. Garcia Beatris', room: 'Educ Bldg RM 102' },
                  { code: 'EDUC 103', title: 'The Teacher and the Community', time: 'T (08:00 AM - 11:00 AM)', instructor: 'Prof. Pascual Ramon', room: 'Educ Bldg RM 103' },
                  { code: 'EDUC 104', title: 'Foundation of Special & Inclusive Educ', time: 'T (01:00 PM - 4:00 PM)', instructor: 'Dr. Torres Sofia', room: 'Educ Bldg RM 104' },
                  { code: 'ASSESS 1', title: 'Assessment in Learning 1', time: 'W (08:00 AM - 11:00 AM)', instructor: 'Prof. Domingo Arthur', room: 'Educ Bldg RM 101' },
                  { code: 'CURR 101', title: 'The Teacher and the School Curriculum', time: 'Th (08:00 AM - 11:00 AM)', instructor: 'Dr. Villanueva Liza', room: 'Educ Bldg RM 102' },
                  { code: 'TECH 101', title: 'Technology for Teaching and Learning 1', time: 'F (08:00 AM - 11:00 AM)', instructor: 'Prof. Castro Noel', room: 'Computer Lab 2' },
                  { code: 'ETHICS 1', title: 'Professional Ethics for Teachers', time: 'F (01:00 PM - 4:00 PM)', instructor: 'Prof. Morales Grace', room: 'Educ Bldg RM 105' }
              ],
              'Business Administration': [
                  { code: 'BUS 101', title: 'Principles of Management', time: 'M (08:00 AM - 11:00 AM)', instructor: 'Prof. Tan Kenneth', room: 'BA Hall RM 201' },
                  { code: 'MKTG 101', title: 'Principles of Marketing', time: 'M (01:00 PM - 4:00 PM)', instructor: 'Prof. Lim Patricia', room: 'BA Hall RM 202' },
                  { code: 'ACTG 101', title: 'Financial Accounting & Reporting', time: 'T (08:00 AM - 11:00 AM)', instructor: 'CPA Sy Benjamin', room: 'BA Hall RM 203' },
                  { code: 'ECON 101', title: 'Basic Microeconomics', time: 'T (01:00 PM - 4:00 PM)', instructor: 'Dr. Hernandez Olivia', room: 'BA Hall RM 204' },
                  { code: 'FIN 101', title: 'Financial Management', time: 'W (08:00 AM - 11:00 AM)', instructor: 'Prof. Ocampo Ricardo', room: 'BA Hall RM 201' },
                  { code: 'LAW 101', title: 'Business Law and Obligations', time: 'Th (08:00 AM - 11:00 AM)', instructor: 'Atty. Aquino Daniel', room: 'BA Hall RM 202' },
                  { code: 'HRM 101', title: 'Human Resource Management', time: 'F (08:00 AM - 11:00 AM)', instructor: 'Prof. Roxas Karen', room: 'BA Hall RM 203' },
                  { code: 'ENTREP 1', title: 'Entrepreneurial Mindset and Startups', time: 'F (01:00 PM - 4:00 PM)', instructor: 'Prof. Go Victor', room: 'BA Hall RM 205' }
              ]
          },

          get currentSchedules() {
              return this.schedules[this.userCourse] || this.schedules['BSIT'];
          }
      }">

    <div class="min-h-screen flex flex-col transition-colors duration-200" :class="darkMode ? 'bg-slate-950' : 'bg-slate-100'">

        <!-- TOP HEADER -->
        <header class="border-b sticky top-0 z-40 backdrop-blur-md transition-colors duration-200" :class="darkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white/90 border-slate-200 shadow-sm'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                
                <!-- Left: Burger Icon (≡) + University Name -->
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" :class="darkMode ? 'bg-slate-800/80 border-slate-700/60 text-emerald-400' : 'bg-slate-100 border-slate-300 text-emerald-600'" class="p-2 rounded-xl border hover:opacity-80 transition flex items-center justify-center">
                        <span class="text-xl font-extrabold leading-none">≡</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-graduation-cap text-white text-xs"></i>
                        </div>
                        <div>
                            <h1 class="text-xs sm:text-sm font-extrabold tracking-wide bg-gradient-to-r from-emerald-400 to-amber-300 bg-clip-text text-transparent">
                                University of Binalonan 
                            </h1>
                           
                        </div>
                    </div>
                </div>

                <!-- Right: Notification Bell (In-update katulad ng sa Admin) & Profile Dropdown -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- NOTIFICATION BELL BUTTON (TOGGLE HOME / NOTIFICATIONS) -->
                    <button @click="currentTab = (currentTab === 'notifications' ? 'home' : 'notifications')" 
                            :class="currentTab === 'notifications' ? 'bg-emerald-600 text-white border-emerald-500 shadow-lg shadow-emerald-600/20' : (darkMode ? 'bg-slate-800/40 text-slate-400 hover:text-white border-slate-800' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border-slate-300')" 
                            class="relative p-2.5 border rounded-xl transition-all active:scale-95 flex items-center justify-center" 
                            title="Toggle Notification History / Home">
                        <i class="fa-solid fa-bell text-xs"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    </button>

                    <!-- PROFILE DROPDOWN -->
                    <div class="relative" x-data="{ profileDropdown: false }">
                        <button @click="profileDropdown = !profileDropdown" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-slate-200' : 'bg-slate-100 border-slate-300 text-slate-800'" class="flex items-center gap-2 border px-3 py-1.5 rounded-2xl transition">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center text-xs border border-emerald-500/30">
                                {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold hidden sm:inline">My Profile</span>
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                        </button>

                        <div x-show="profileDropdown" @click.away="profileDropdown = false" :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-100' : 'bg-white border-slate-200 text-slate-800 shadow-xl'" class="absolute right-0 mt-2 w-56 border rounded-2xl p-2 space-y-1 z-50 text-xs" x-cloak>
                            <div class="px-3 py-2 border-b mb-1" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                                <p class="font-bold truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-emerald-400">ID: {{ Auth::user()->student_id ?? 'N/A' }}</p>
                            </div>
                            <button @click="profileModal = true; profileDropdown = false;" :class="darkMode ? 'hover:bg-slate-800 text-slate-300' : 'hover:bg-slate-100 text-slate-700'" class="w-full text-left px-3 py-2 rounded-xl flex items-center gap-2 transition">
                                <i class="fa-solid fa-user-gear text-emerald-400"></i> Profile Information
                            </button>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-rose-500/10 hover:text-rose-400 rounded-xl flex items-center gap-2 text-rose-500 transition font-bold">
                                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- SLIDE-OUT BURGER SIDEBAR MENU -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex" x-cloak>
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>

            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-100' : 'bg-white border-slate-200 text-slate-800'" class="relative w-72 border-r h-full p-6 flex flex-col justify-between shadow-2xl z-10 space-y-6 transition-colors duration-200">
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30">
                            <i class="fa-solid fa-graduation-cap text-sm"></i>
                        </div>
                        <button @click="sidebarOpen = false" class="text-slate-400 hover:text-rose-500 text-xl font-bold">&times;</button>
                    </div>

                    <nav class="space-y-2 text-xs font-semibold">
                        <button @click="currentTab = 'home'; sidebarOpen = false;" :class="currentTab === 'home' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-house text-sm"></i> Dashboard Home
                        </button>
                        <button @click="currentTab = 'schedule'; sidebarOpen = false;" :class="currentTab === 'schedule' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-calendar-days text-sm"></i> Class Schedule
                        </button>
                        <button @click="currentTab = 'grades'; sidebarOpen = false;" :class="currentTab === 'grades' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-graduation-cap text-sm"></i> Grades & Evaluation
                        </button>
                        <button @click="currentTab = 'clearance'; sidebarOpen = false;" :class="currentTab === 'clearance' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-file-shield text-sm"></i> Clearance & Assessment
                        </button>
                        <button @click="currentTab = 'notifications'; sidebarOpen = false;" :class="currentTab === 'notifications' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-bell text-sm"></i> Notification History
                        </button>
                    </nav>
                </div>

                <div :class="darkMode ? 'bg-slate-950 border-slate-800 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'" class="p-4 rounded-2xl border space-y-1">
                    <p class="text-[10px] text-amber-400 font-bold uppercase">University Portal</p>
                    <p class="text-xs font-bold">University of Binalonan</p>
                    <p class="text-[10px] text-slate-500">AY 2026-2027 • Student Portal</p>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- TAB 1: DASHBOARD HOME -->
            <div x-show="currentTab === 'home'" class="space-y-6">
                <div :class="darkMode ? 'from-emerald-950/60 via-slate-900 to-slate-900 border-emerald-500/20 text-white' : 'from-emerald-100 via-white to-white border-emerald-300 text-slate-800 shadow-sm'" class="bg-gradient-to-r border rounded-3xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden shadow-xl">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-[10px] font-extrabold uppercase tracking-wider">Official Student Portal</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold">Welcome, {{ Auth::user()->name }}!</h2>
                    </div>
                    <button @click="currentTab = 'schedule'" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-2xl transition shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days"></i> View Schedule
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-2xl p-5 space-y-1">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Enrolled Program & Block</p>
                        <h3 class="text-sm font-extrabold"><span x-text="userCourse"></span> • <span x-text="userBlock"></span></h3>
                        <p class="text-[10px] text-emerald-400 font-medium">University of Binalonan</p>
                    </div>
                    <div :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-2xl p-5 space-y-1">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Enrollment Status</p>
                        <h3 class="text-sm font-extrabold text-emerald-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Enrolled (<span x-text="userBlock"></span>)
                        </h3>
                        <p class="text-[10px] text-slate-400 font-medium">Regular Student</p>
                    </div>
                    <div :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-2xl p-5 space-y-1">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Last Sign-in</p>
                        <h3 class="text-xs font-extrabold">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Just now' }}</h3>
                        <p class="text-[10px] text-amber-400 font-medium">Gmail SMTP Protected</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: SCHEDULE TABLE -->
            <div x-show="currentTab === 'schedule'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-emerald-400"></i> Official Class Schedule
                                </h2>
                                <span class="px-2.5 py-0.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full">Status: Enrolled</span>
                            </div>
                            <p class="text-xs text-slate-400"><span x-text="userCourse"></span> — <span x-text="userBlock"></span></p>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="text" x-model="searchSchedule" placeholder="Search subject, instructor or room..." :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-100 border-slate-300 text-slate-800'" class="w-full sm:w-64 border rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500">
                            <button onclick="window.print()" :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-white' : 'bg-slate-200 hover:bg-slate-300 text-slate-800'" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-print"></i> Print
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead :class="darkMode ? 'bg-slate-950 text-slate-400 border-slate-800' : 'bg-slate-100 text-slate-600 border-slate-200'" class="uppercase font-bold border-b">
                                <tr>
                                    <th class="p-3">Subject Code</th>
                                    <th class="p-3">Descriptive Title</th>
                                    <th class="p-3">Schedule</th>
                                    <th class="p-3">Block</th>
                                    <th class="p-3">Instructor</th>
                                    <th class="p-3">Room</th>
                                    <th class="p-3">Program / Section</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y font-medium" :class="darkMode ? 'divide-slate-800/60' : 'divide-slate-200'">
                                <template x-for="sub in currentSchedules">
                                    <tr :class="darkMode ? 'hover:bg-slate-800/30' : 'hover:bg-slate-50'" class="transition">
                                        <td class="p-3 font-bold text-amber-400" x-text="sub.code"></td>
                                        <td class="p-3 font-semibold" x-text="sub.title"></td>
                                        <td class="p-3 text-slate-400" x-text="sub.time"></td>
                                        <td class="p-3 text-slate-400" x-text="userBlock"></td>
                                        <td class="p-3" x-text="sub.instructor"></td>
                                        <td class="p-3 text-emerald-400" x-text="sub.room"></td>
                                        <td class="p-3 text-slate-400" x-text="userCourse + ' / ' + userBlock"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: GRADES & EVALUATION -->
            <div x-show="currentTab === 'grades'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold flex items-center gap-2">
                                    <i class="fa-solid fa-graduation-cap text-teal-400"></i> Semestral Grades & Evaluation
                                </h2>
                                <span class="px-2.5 py-0.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full">Status: Enrolled</span>
                            </div>
                            <p class="text-xs text-slate-400">Academic Progress Evaluation Sheet (<span x-text="userCourse"></span>)</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-[11px]">
                            <thead :class="darkMode ? 'bg-slate-950 text-slate-400 border-slate-800' : 'bg-slate-100 text-slate-600 border-slate-200'" class="uppercase font-bold border-b">
                                <tr>
                                    <th class="p-2.5 border-r border-slate-800/50">Subject</th>
                                    <th class="p-2.5 border-r border-slate-800/50">Descriptive Title</th>
                                    <th class="p-2.5 text-center border-r border-slate-800/50">Unit</th>
                                    <th class="p-2.5 text-center border-r border-slate-800/50" colspan="3">Prelim</th>
                                    <th class="p-2.5 text-center border-r border-slate-800/50" colspan="3">Midterm</th>
                                    <th class="p-2.5 text-center border-r border-slate-800/50" colspan="3">Finals</th>
                                    <th class="p-2.5 text-center">Final Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y font-medium" :class="darkMode ? 'divide-slate-800/60' : 'divide-slate-200'">
                                <template x-for="sub in currentSchedules">
                                    <tr :class="darkMode ? 'hover:bg-slate-800/30' : 'hover:bg-slate-50'" class="transition">
                                        <td class="p-2.5 font-bold text-amber-400 border-r" x-text="sub.code"></td>
                                        <td class="p-2.5 border-r" x-text="sub.title"></td>
                                        <td class="p-2.5 text-center border-r">3</td>
                                        <td class="p-2.5 text-center text-slate-400 border-r" colspan="3">--</td>
                                        <td class="p-2.5 text-center text-slate-400 border-r" colspan="3">--</td>
                                        <td class="p-2.5 text-center text-slate-400 border-r" colspan="3">--</td>
                                        <td class="p-2.5 text-center text-slate-400">-</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-4 border-t space-y-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div class="text-center p-3 border rounded-2xl" :class="darkMode ? 'bg-slate-950/80 border-slate-800/80' : 'bg-slate-100 border-slate-200'">
                            <span class="text-xs text-slate-400 font-medium">General Weighted Average (GWA): </span>
                            <span class="text-amber-400 font-extrabold text-sm ml-1">INC</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CLEARANCE & ASSESSMENT -->
            <div x-show="currentTab === 'clearance'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <h2 class="text-base font-extrabold flex items-center gap-2">
                            <i class="fa-solid fa-file-shield text-emerald-400"></i> Student Clearance & Fee Assessment Ledger
                        </h2>
                        <p class="text-xs text-slate-400">Academic Year 2026-2027 Clearance Status</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl space-y-2">
                            <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 font-bold rounded-lg text-[10px]">Cleared</span>
                            <h4 class="font-bold text-sm">University Library Clearance</h4>
                            <p class="text-[11px] text-slate-400">No overdue books or pending fines recorded.</p>
                        </div>
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl space-y-2">
                            <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 font-bold rounded-lg text-[10px]">Cleared</span>
                            <h4 class="font-bold text-sm">Department Laboratory Clearance</h4>
                            <p class="text-[11px] text-slate-400">All workstation accountabilities verified.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: NOTIFICATION HISTORY -->
            <div x-show="currentTab === 'notifications'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <h2 class="text-base font-extrabold flex items-center gap-2">
                            <i class="fa-solid fa-bell text-amber-400"></i> Email & System Notifications History
                        </h2>
                        <p class="text-xs text-slate-400">Recent logs dispatched securely via Gmail SMTP.</p>
                    </div>

                    <div class="space-y-2.5 text-xs max-h-[500px] overflow-y-auto pr-2">
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-3 border rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-xs">Successful Portal Login</p>
                                    <p class="text-[10px] text-slate-400">Security alert notification sent to Gmail inbox.</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-emerald-400 font-bold">Just now</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- MY PROFILE MODAL POPUP -->
        <div x-show="profileModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak style="display: none;">
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-5">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-emerald-400"></i> Profile Information
                    </h3>
                    <button @click="profileModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-100 border-slate-200'" class="space-y-3 p-4 rounded-2xl border text-xs">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Full Name</span>
                        <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Student ID Number</span>
                        <p class="text-amber-400 font-bold text-sm">{{ Auth::user()->student_id ?? '2024-06378' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Enrolled Course / Program</span>
                        <p class="text-teal-400 font-bold text-sm">{{ Auth::user()->course ?? 'BSIT' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Block / Section</span>
                        <p class="text-emerald-400 font-bold text-sm">{{ Auth::user()->block ?? 'Block C' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Registered Gmail</span>
                        <p class="font-bold text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="profileModal = false" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">
                        Close Profile
                    </button>
                </div>
            </div>
        </div>

    </div>

</body>
</html>