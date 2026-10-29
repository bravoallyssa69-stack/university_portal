<!DOCTYPE html>
<html lang="en" class="h-full" :class="darkMode ? 'bg-slate-950' : 'bg-slate-100'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - University of Binalonan (U-Bi)</title>
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
          searchStudent: '', 
          selectedBlockFilter: 'All',
          selectedCourseFilter: 'All',
          profileModal: false, 
          addStudentModal: false,
          editStudentModal: false,
          addSubjectModal: false,
          editSubjectModal: false,
          
          // Per-Course Grade Lock Controls (Tugma sa 5 kurso)
          gradesLocked: {
              'BSIT': false,
              'Criminology': false,
              'Education': false,
              'Business Administration': false,
              'Midwifery': false,
              'Hospitality Management': false
          },

          alertMessage: '',
          showAlert: false,
          
          triggerAlert(msg) {
              this.alertMessage = msg;
              this.showAlert = true;
              setTimeout(() => { this.showAlert = false; }, 3500);
          },

          students: {{ Js::from($students ?? []) }},

          // Comprehensive Master Subjects for All 5 Courses (8 Subjects Each)
          subjects: [
              // BSIT Subjects (1-8)
              { id: 1, course: 'BSIT', block: 'Block C', code: 'SIA 101', title: 'Systems Integration and Architecture 1', instructor: 'danao riezza', room: 'ONLINE' },
              { id: 2, course: 'BSIT', block: 'Block C', code: 'GE ELEC 4', title: 'Technical Writing', instructor: 'MALAGAYO NOEMIE', room: 'Annex D RM 6' },
              { id: 3, course: 'BSIT', block: 'Block C', code: 'UED 101', title: 'User Experience Design', instructor: 'ANGAN-ANGAN MAJIE BASOBAS', room: 'DIGITEL IT RM 3' },
              { id: 4, course: 'BSIT', block: 'Block C', code: 'CC 106', title: 'App Dev. And Emerging Technologies', instructor: 'VALDEZ RICHELLE JANE PAZ', room: 'DIGITEL IT RM 2' },
              { id: 5, course: 'BSIT', block: 'Block C', code: 'NET 102', title: 'Networking 2', instructor: 'ABELLERA APRIL ANNE MONTIPALCO', room: 'ONLINE' },
              { id: 6, course: 'BSIT', block: 'Block C', code: 'PF 102', title: 'Event Driven Programming', instructor: 'ABELLERA APRIL ANNE MONTIPALCO', room: 'DIGITEL IT RM 2' },
              { id: 7, course: 'BSIT', block: 'Block C', code: 'IT ELEC 2', title: 'IT Elective 2 (Social & Prof. Issues)', instructor: 'Collado Floresvein Elamparo', room: 'ONLINE' },
              { id: 8, course: 'BSIT', block: 'Block C', code: 'CAP 101', title: 'Capstone Project and Research 1', instructor: 'Ramos Mark Anthony', room: 'DIGITEL IT RM 1' },

              // Criminology Subjects (9-16)
              { id: 9, course: 'Criminology', block: 'Block A', code: 'CRIM 101', title: 'Introduction to Criminology', instructor: 'Dr. Dela Cruz Juan', room: 'Crim Hall RM 1' },
              { id: 10, course: 'Criminology', block: 'Block A', code: 'LEA 102', title: 'Law Enforcement Administration', instructor: 'Capt. Santos Roberto', room: 'Crim Hall RM 2' },
              { id: 11, course: 'Criminology', block: 'Block A', code: 'CDI 103', title: 'Crime Detection and Investigation', instructor: 'Insp. Mendoza Maria', room: 'Crim Lab RM 1' },
              { id: 12, course: 'Criminology', block: 'Block A', code: 'FOR 101', title: 'Personal Identification & Dactyloscopy', instructor: 'Dr. Reyes Gabriel', room: 'Forensic Lab' },
              { id: 13, course: 'Criminology', block: 'Block A', code: 'CORR 1', title: 'Institutional Corrections', instructor: 'Prof. Soriano Fernando', room: 'Crim Hall RM 3' },
              { id: 14, course: 'Criminology', block: 'Block A', code: 'SOC 102', title: 'Human Behavior and Victimology', instructor: 'Prof. Aguilar Teresa', room: 'Crim Hall RM 1' },
              { id: 15, course: 'Criminology', block: 'Block A', code: 'TAC 101', title: 'Marksmanship and Defensive Tactics', instructor: 'Sgt. Bautista Pedro', room: 'Firing Range / Gym' },
              { id: 16, course: 'Criminology', block: 'Block A', code: 'CLJ 104', title: 'Criminal Law and Procedure', instructor: 'Atty. Navarro Carlos', room: 'Crim Hall RM 4' },

              // Education Subjects (17-24)
              { id: 17, course: 'Education', block: 'Block B', code: 'EDUC 101', title: 'The Child and Adolescent Learners', instructor: 'Dr. Alonzo Elena', room: 'Educ Bldg RM 101' },
              { id: 18, course: 'Education', block: 'Block B', code: 'EDUC 102', title: 'The Teaching Profession', instructor: 'Prof. Garcia Beatris', room: 'Educ Bldg RM 102' },
              { id: 19, course: 'Education', block: 'Block B', code: 'EDUC 103', title: 'The Teacher and the Community', instructor: 'Prof. Pascual Ramon', room: 'Educ Bldg RM 103' },
              { id: 20, course: 'Education', block: 'Block B', code: 'EDUC 104', title: 'Foundation of Special & Inclusive Educ', instructor: 'Dr. Torres Sofia', room: 'Educ Bldg RM 104' },
              { id: 21, course: 'Education', block: 'Block B', code: 'ASSESS 1', title: 'Assessment in Learning 1', instructor: 'Prof. Domingo Arthur', room: 'Educ Bldg RM 101' },
              { id: 22, course: 'Education', block: 'Block B', code: 'CURR 101', title: 'The Teacher and the School Curriculum', instructor: 'Dr. Villanueva Liza', room: 'Educ Bldg RM 102' },
              { id: 23, course: 'Education', block: 'Block B', code: 'TECH 101', title: 'Technology for Teaching and Learning 1', instructor: 'Prof. Castro Noel', room: 'Computer Lab 2' },
              { id: 24, course: 'Education', block: 'Block B', code: 'ETHICS 1', title: 'Professional Ethics for Teachers', instructor: 'Prof. Morales Grace', room: 'Educ Bldg RM 105' },

              // Business Administration Subjects (25-32)
              { id: 25, course: 'Business Administration', block: 'Block C', code: 'BUS 101', title: 'Principles of Management', instructor: 'Prof. Tan Kenneth', room: 'BA Hall RM 201' },
              { id: 26, course: 'Business Administration', block: 'Block C', code: 'MKTG 101', title: 'Principles of Marketing', instructor: 'Prof. Lim Patricia', room: 'BA Hall RM 202' },
              { id: 27, course: 'Business Administration', block: 'Block C', code: 'ACTG 101', title: 'Financial Accounting & Reporting', instructor: 'CPA Sy Benjamin', room: 'BA Hall RM 203' },
              { id: 28, course: 'Business Administration', block: 'Block C', code: 'ECON 101', title: 'Basic Microeconomics', instructor: 'Dr. Hernandez Olivia', room: 'BA Hall RM 204' },
              { id: 29, course: 'Business Administration', block: 'Block C', code: 'FIN 101', title: 'Financial Management', instructor: 'Prof. Ocampo Ricardo', room: 'BA Hall RM 201' },
              { id: 30, course: 'Business Administration', block: 'Block C', code: 'LAW 101', title: 'Business Law and Obligations', instructor: 'Atty. Aquino Daniel', room: 'BA Hall RM 202' },
              { id: 31, course: 'Business Administration', block: 'Block C', code: 'HRM 101', title: 'Human Resource Management', instructor: 'Prof. Roxas Karen', room: 'BA Hall RM 203' },
              { id: 32, course: 'Business Administration', block: 'Block C', code: 'ENTREP 1', title: 'Entrepreneurial Mindset and Startups', instructor: 'Prof. Go Victor', room: 'BA Hall RM 205' },

              // Midwifery Subjects (33-40)
              { id: 33, course: 'Midwifery', block: 'Block D', code: 'MID 101', title: 'Anatomy and Physiology for Midwives', instructor: 'Dr. Santos Clarissa', room: 'Med Bldg RM 1' },
              { id: 34, course: 'Midwifery', block: 'Block D', code: 'MID 102', title: 'Normal Midwifery I', instructor: 'Prof. Villanueva Teresa', room: 'Med Bldg RM 2' },
              { id: 35, course: 'Midwifery', block: 'Block D', code: 'MID 103', title: 'Primary Health Care', instructor: 'Prof. Gomez Raul', room: 'Med Bldg RM 3' },
              { id: 36, course: 'Midwifery', block: 'Block D', code: 'MID 104', title: 'Microbiology and Parasitology', instructor: 'Dr. Reyes Nestor', room: 'Lab Room 4' },
              { id: 37, course: 'Midwifery', block: 'Block D', code: 'MID 105', title: 'Infant Feeding and Nutrition', instructor: 'Prof. Cruz Elena', room: 'Med Bldg RM 1' },
              { id: 38, course: 'Midwifery', block: 'Block D', code: 'MID 106', title: 'Professional Adjustment and Ethics', instructor: 'Atty. Ramos Dionisio', room: 'Med Bldg RM 2' },
              { id: 39, course: 'Midwifery', block: 'Block D', code: 'MID 107', title: 'Clinical Practice in Midwifery 1', instructor: 'Dr. Aquino Brenda', room: 'Hospital Ward' },
              { id: 40, course: 'Midwifery', block: 'Block D', code: 'MID 108', title: 'Community Health Nursing Services', instructor: 'Prof. Soriano Marites', room: 'Med Bldg RM 3' }
          ],

          selectedStudent: { id: '', student_id: '', name: '', email: '', course: '', block: '' },
          newStudent: { student_id: '', name: '', email: '', course: 'BSIT', block: 'Block C' },
          newSubject: { course: 'BSIT', block: 'Block C', code: '', title: '', instructor: '', room: '' },
          selectedSubject: { id: '', course: '', block: '', code: '', title: '', instructor: '', room: '' },

          async saveNewStudent() {
              if(!this.newStudent.student_id || !this.newStudent.name || !this.newStudent.email) {
                  alert('Please fill in all required student details.');
                  return;
              }

              this.students.push({
                  id: Date.now(),
                  student_id: this.newStudent.student_id,
                  name: this.newStudent.name,
                  email: this.newStudent.email,
                  course: this.newStudent.course,
                  block: this.newStudent.block,
                  status: 'Enrolled'
              });
              this.addStudentModal = false;
              this.newStudent = { student_id: '', name: '', email: '', course: 'BSIT', block: 'Block C' };
              this.triggerAlert('New student account added successfully!');
          },

          async saveEditedStudent() {
              let idx = this.students.findIndex(s => s.id === this.selectedStudent.id);
              if(idx !== -1) {
                  this.students[idx] = { ...this.selectedStudent };
              }
              this.editStudentModal = false;
              this.triggerAlert('Student record updated successfully!');
          },

          addSubject() {
              if(this.newSubject.code && this.newSubject.title) {
                  this.subjects.push({ id: Date.now(), ...this.newSubject });
                  this.newSubject = { course: 'BSIT', block: 'Block C', code: '', title: '', instructor: '', room: '' };
                  this.addSubjectModal = false;
                  this.triggerAlert('New subject added to Master Schedule!');
              }
          },

          saveEditedSubject() {
              let idx = this.subjects.findIndex(s => s.id === this.selectedSubject.id);
              if(idx !== -1) {
                  this.subjects[idx] = { ...this.selectedSubject };
                  this.editSubjectModal = false;
                  this.triggerAlert('Subject details updated successfully!');
              }
          },

          deleteSubject(subId, subCode) {
              if(confirm('Are you sure you want to delete ' + subCode + ' from the Master Schedule?')) {
                  this.subjects = this.subjects.filter(s => s.id !== subId);
                  this.triggerAlert('Subject ' + subCode + ' removed from schedule!');
              }
          },

          filteredStudents() {
              return this.students.filter(s => {
                  let matchesSearch = (s.name && s.name.toLowerCase().includes(this.searchStudent.toLowerCase())) || 
                                      (s.student_id && s.student_id.toLowerCase().includes(this.searchStudent.toLowerCase())) ||
                                      (s.email && s.email.toLowerCase().includes(this.searchStudent.toLowerCase()));
                  let matchesBlock = this.selectedBlockFilter === 'All' || s.block === this.selectedBlockFilter;
                  let matchesCourse = this.selectedCourseFilter === 'All' || s.course === this.selectedCourseFilter;
                  return matchesSearch && matchesBlock && matchesCourse;
              });
          },

          filteredSubjects() {
              return this.subjects.filter(sub => {
                  return this.selectedCourseFilter === 'All' || sub.course === this.selectedCourseFilter;
              });
          }
      }">

    <!-- GLOBAL ALERT TOAST NOTIFICATION -->
    <div x-show="showAlert" x-transition class="fixed top-20 right-6 z-50 bg-emerald-600 text-white font-extrabold px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs border border-emerald-400" x-cloak>
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span x-text="alertMessage"></span>
    </div>

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
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-emerald-600 flex items-center justify-center shadow-md shadow-amber-500/20">
                            <i class="fa-solid fa-user-shield text-slate-950 text-xs font-extrabold"></i>
                        </div>
                        <div>
                            <h1 class="text-xs sm:text-sm font-extrabold tracking-wide bg-gradient-to-r from-emerald-400 to-amber-300 bg-clip-text text-transparent">
                                University of Binalonan 
                            </h1>
                         
                        </div>
                    </div>
                </div>

                <!-- Right: Notification Bell & Profile Dropdown -->
                <div class="flex items-center gap-2 sm:gap-3" x-data="{ profileDropdown: false }">
                    <button @click="currentTab = (currentTab === 'logs' ? 'home' : 'logs')" 
                            :class="currentTab === 'logs' ? 'bg-emerald-600 text-white border-emerald-500 shadow-lg shadow-emerald-600/20' : (darkMode ? 'bg-slate-800/40 text-slate-400 hover:text-white border-slate-800' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border-slate-300')" 
                            class="relative p-2.5 border rounded-xl transition-all active:scale-95 flex items-center justify-center" 
                            title="Toggle Notification Logs / Home">
                        <i class="fa-solid fa-bell text-xs"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    </button>

                    <!-- PROFILE DROPDOWN -->
                    <div class="relative">
                        <button @click="profileDropdown = !profileDropdown" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-slate-200' : 'bg-slate-100 border-slate-300 text-slate-800'" class="flex items-center gap-2 border px-3 py-1.5 rounded-2xl transition">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center text-xs border border-amber-500/30">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold hidden sm:inline">My Profile</span>
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                        </button>

                        <div x-show="profileDropdown" @click.away="profileDropdown = false" :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-100' : 'bg-white border-slate-200 text-slate-800 shadow-xl'" class="absolute right-0 mt-2 w-56 border rounded-2xl p-2 space-y-1 z-50 text-xs" x-cloak>
                            <div class="px-3 py-2 border-b mb-1" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                                <p class="font-bold truncate" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ Auth::user()->name ?? 'System Administrator' }}</p>
                                <p class="text-[10px] text-amber-400 uppercase">Role: Administrator</p>
                            </div>
                            <button @click="profileModal = true; profileDropdown = false;" :class="darkMode ? 'hover:bg-slate-800 text-slate-300' : 'hover:bg-slate-100 text-slate-700'" class="w-full text-left px-3 py-2 rounded-xl flex items-center gap-2 transition">
                                <i class="fa-solid fa-user-gear text-emerald-400"></i> Profile Info
                            </button>
                            
                            <!-- SIGN OUT FORM -->
                            <form method="POST" action="{{ route('logout') }}" id="admin-logout-form">
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
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                            <i class="fa-solid fa-graduation-cap text-sm"></i>
                        </div>
                        <button @click="sidebarOpen = false" class="text-slate-400 hover:text-rose-500 text-xl font-bold">&times;</button>
                    </div>

                    <nav class="space-y-2 text-xs font-semibold">
                        <button @click="currentTab = 'home'; sidebarOpen = false;" :class="currentTab === 'home' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-house text-sm"></i> Dashboard Home
                        </button>
                        <button @click="currentTab = 'students'; sidebarOpen = false;" :class="currentTab === 'students' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-users-gear text-sm"></i> Student Accounts
                        </button>
                        <button @click="currentTab = 'schedule'; sidebarOpen = false;" :class="currentTab === 'schedule' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-calendar-days text-sm"></i> Master Class Schedule
                        </button>
                        <button @click="currentTab = 'grades'; sidebarOpen = false;" :class="currentTab === 'grades' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-graduation-cap text-sm"></i> Grades Control
                        </button>
                        <button @click="currentTab = 'logs'; sidebarOpen = false;" :class="currentTab === 'logs' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : (darkMode ? 'text-slate-400 hover:bg-slate-800 hover:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition">
                            <i class="fa-solid fa-bell text-sm"></i> Notification Logs
                        </button>
                    </nav>
                </div>

                <div :class="darkMode ? 'bg-slate-950 border-slate-800 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'" class="p-4 rounded-2xl border space-y-1">
                    <p class="text-[10px] text-amber-400 font-bold uppercase">Administrative Panel</p>
                    <p class="text-xs font-bold">University of Binalonan</p>
                    <p class="text-[10px] text-slate-500">AY 2026-2027 • Control Center</p>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- TAB 1: DASHBOARD HOME -->
            <div x-show="currentTab === 'home'" class="space-y-6">
                <div :class="darkMode ? 'from-emerald-950/60 via-slate-900 to-slate-900 border-emerald-500/20 text-white' : 'from-emerald-100 via-white to-white border-emerald-300 text-slate-800 shadow-sm'" class="bg-gradient-to-r border rounded-3xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden shadow-xl">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-full text-[10px] font-extrabold uppercase tracking-wider">Official Admin Portal</span>
                        <h2 class="text-2xl md:text-3xl font-extrabold">Welcome, Admin {{ Auth::user()->name ?? 'User' }}! </h2>
                    </div>
                    <button @click="currentTab = 'students'" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-2xl transition shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                        <i class="fa-solid fa-users-gear"></i> Manage Students
                    </button>
                </div>

                <!-- Admin Analytics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div @click="currentTab = 'students'" :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border hover:border-emerald-500/50 cursor-pointer rounded-2xl p-5 space-y-1 transition">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Total Registered Students</p>
                        <h3 class="text-xl font-extrabold" x-text="students.length + ' Enrolled Users'"></h3>
                        <p class="text-[10px] text-emerald-400 font-medium">University of Binalonan</p>
                    </div>
                    <div @click="currentTab = 'schedule'" :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border hover:border-amber-500/50 cursor-pointer rounded-2xl p-5 space-y-1 transition">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Master Subjects</p>
                        <h3 class="text-xl font-extrabold text-amber-400" x-text="subjects.length + ' Active Subjects'"></h3>
                        <p class="text-[10px] text-slate-400 font-medium">Across All Degree Programs</p>
                    </div>
                    <div @click="currentTab = 'logs'" :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border hover:border-teal-500/50 cursor-pointer rounded-2xl p-5 space-y-1 transition">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">SMTP Mailer Pipeline</p>
                        <h3 class="text-sm font-extrabold text-emerald-400 flex items-center gap-1.5"><i class="fa-solid fa-circle-check"></i> Connected & Active</h3>
                        <p class="text-[10px] text-slate-400 font-medium">bravoallyssa69@gmail.com</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: STUDENT ACCOUNTS MANAGEMENT -->
            <div x-show="currentTab === 'students'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold flex items-center gap-2">
                                    <i class="fa-solid fa-users-gear text-emerald-400"></i> Student Directory Management
                                </h2>
                                <span class="px-2.5 py-0.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full" x-text="'Total: ' + students.length"></span>
                            </div>
                            <p class="text-xs text-slate-400">View all students sorted by course and block.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            <!-- Course Filter (5 Kurso) -->
                            <select x-model="selectedCourseFilter" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-100 border-slate-300 text-slate-800'" class="border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-emerald-500">
                                <option value="All">All Courses</option>
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                            <!-- Block Filter -->
                            <select x-model="selectedBlockFilter" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-100 border-slate-300 text-slate-800'" class="border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-emerald-500">
                                <option value="All">All Blocks</option>
                                <option value="Block A">Block A</option>
                                <option value="Block B">Block B</option>
                                <option value="Block C">Block C</option>
                                <option value="Block D">Block D</option>
                            </select>
                            <input type="text" x-model="searchStudent" placeholder="Search name, ID..." :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-100 border-slate-300 text-slate-800'" class="w-full sm:w-44 border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-emerald-500">
                            <button @click="addStudentModal = true" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap">
                                <i class="fa-solid fa-user-plus"></i> Add Student
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead :class="darkMode ? 'bg-slate-950 text-slate-400 border-slate-800' : 'bg-slate-100 text-slate-600 border-slate-200'" class="uppercase font-bold border-b">
                                <tr>
                                    <th class="p-3">Student ID</th>
                                    <th class="p-3">Full Legal Name</th>
                                    <th class="p-3">Registered Gmail</th>
                                    <th class="p-3">Course / Program</th>
                                    <th class="p-3">Block / Section</th>
                                    <th class="p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y font-medium" :class="darkMode ? 'divide-slate-800/60' : 'divide-slate-200'">
                                <template x-for="(st, index) in filteredStudents()" :key="index">
                                    <tr :class="darkMode ? 'hover:bg-slate-800/30' : 'hover:bg-slate-50'" class="transition">
                                        <td class="p-3 font-bold text-amber-400" x-text="st.student_id"></td>
                                        <td class="p-3 font-bold" x-text="st.name"></td>
                                        <td class="p-3 text-slate-400" x-text="st.email"></td>
                                        <td class="p-3 font-semibold text-teal-400" x-text="st.course || 'BSIT'"></td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-400 font-bold text-[10px] rounded-full" x-text="st.block || 'Block C'"></span>
                                        </td>
                                        <td class="p-3 text-center space-x-1.5">
                                            <button @click="selectedStudent = {...st}; editStudentModal = true;" :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-white' : 'bg-slate-200 hover:bg-slate-300 text-slate-800'" class="px-2.5 py-1 rounded-lg text-[10px] font-bold">Edit</button>
                                            <button @click="triggerAlert('Password reset token dispatched via SMTP to ' + st.email)" class="px-2.5 py-1 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 rounded-lg text-[10px] font-bold">Reset Pass</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: MASTER CLASS SCHEDULE -->
            <div x-show="currentTab === 'schedule'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div>
                            <h2 class="text-base font-extrabold flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days text-emerald-400"></i> Master Class Schedules per Course & Block
                            </h2>
                            <p class="text-xs text-slate-400">Manage 8 distinct subjects for all 5 degree programs.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Course Filter for Schedule (5 Kurso) -->
                            <select x-model="selectedCourseFilter" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-100 border-slate-300 text-slate-800'" class="border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-emerald-500">
                                <option value="All">All Courses</option>
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                            <button @click="addSubjectModal = true" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 whitespace-nowrap">
                                <i class="fa-solid fa-plus"></i> Add Subject
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs max-h-[600px] overflow-y-auto pr-2">
                        <template x-for="(sub, i) in filteredSubjects()" :key="sub.id">
                            <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-teal-500/20 text-teal-300 font-bold text-[10px] rounded-md" x-text="sub.course"></span>
                                        <span class="px-2 py-0.5 bg-amber-500/20 text-amber-300 font-bold text-[10px] rounded-md" x-text="sub.block"></span>
                                        <span class="font-bold text-amber-400" x-text="sub.code"></span>
                                    </div>
                                    <h4 class="font-bold text-sm" x-text="sub.title"></h4>
                                    <p class="text-[10px] text-slate-400" x-text="'Instructor: ' + sub.instructor + ' • Room: ' + sub.room"></p>
                                </div>
                                <div class="flex items-center gap-2 self-end sm:self-center">
                                    <button @click="selectedSubject = {...sub}; editSubjectModal = true;" :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-white' : 'bg-slate-200 hover:bg-slate-300 text-slate-800'" class="px-3 py-1.5 font-bold rounded-xl transition">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </button>
                                    <button @click="deleteSubject(sub.id, sub.code)" class="px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 font-bold rounded-xl transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- TAB 4: GRADES CONTROL PER COURSE (5 Kurso) -->
            <div x-show="currentTab === 'grades'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <h2 class="text-base font-extrabold flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-teal-400"></i> Course-Specific Faculty Grade Approval & Lock Control
                        </h2>
                        <p class="text-xs text-slate-400">Lock or open semestral grade submissions individually for all 5 degree programs.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                        <!-- BSIT Grades Control -->
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-sm text-amber-400">BSIT Program</p>
                                <p class="text-[10px]" :class="gradesLocked['BSIT'] ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold'" x-text="gradesLocked['BSIT'] ? 'Status: LOCKED (Disabled)' : 'Status: OPEN for Encoding'"></p>
                            </div>
                            <button @click="gradesLocked['BSIT'] = !gradesLocked['BSIT']; triggerAlert('BSIT grade status updated!');" 
                                    :class="gradesLocked['BSIT'] ? 'bg-rose-600 hover:bg-rose-500' : 'bg-teal-600 hover:bg-teal-500'" 
                                    class="px-3 py-2 text-white font-bold rounded-xl transition shadow-md" 
                                    x-text="gradesLocked['BSIT'] ? 'Unlock' : 'Lock'">
                            </button>
                        </div>

                        <!-- Criminology Grades Control -->
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-sm text-amber-400">Criminology Program</p>
                                <p class="text-[10px]" :class="gradesLocked['Criminology'] ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold'" x-text="gradesLocked['Criminology'] ? 'Status: LOCKED (Disabled)' : 'Status: OPEN for Encoding'"></p>
                            </div>
                            <button @click="gradesLocked['Criminology'] = !gradesLocked['Criminology']; triggerAlert('Criminology grade status updated!');" 
                                    :class="gradesLocked['Criminology'] ? 'bg-rose-600 hover:bg-rose-500' : 'bg-teal-600 hover:bg-teal-500'" 
                                    class="px-3 py-2 text-white font-bold rounded-xl transition shadow-md" 
                                    x-text="gradesLocked['Criminology'] ? 'Unlock' : 'Lock'">
                            </button>
                        </div>

                        <!-- Education Grades Control -->
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-sm text-amber-400">Education Program</p>
                                <p class="text-[10px]" :class="gradesLocked['Education'] ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold'" x-text="gradesLocked['Education'] ? 'Status: LOCKED (Disabled)' : 'Status: OPEN for Encoding'"></p>
                            </div>
                            <button @click="gradesLocked['Education'] = !gradesLocked['Education']; triggerAlert('Education grade status updated!');" 
                                    :class="gradesLocked['Education'] ? 'bg-rose-600 hover:bg-rose-500' : 'bg-teal-600 hover:bg-teal-500'" 
                                    class="px-3 py-2 text-white font-bold rounded-xl transition shadow-md" 
                                    x-text="gradesLocked['Education'] ? 'Unlock' : 'Lock'">
                            </button>
                        </div>

                        <!-- Business Administration Grades Control -->
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-sm text-amber-400">Business Admin Program</p>
                                <p class="text-[10px]" :class="gradesLocked['Business Administration'] ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold'" x-text="gradesLocked['Business Administration'] ? 'Status: LOCKED (Disabled)' : 'Status: OPEN for Encoding'"></p>
                            </div>
                            <button @click="gradesLocked['Business Administration'] = !gradesLocked['Business Administration']; triggerAlert('Business Admin grade status updated!');" 
                                    :class="gradesLocked['Business Administration'] ? 'bg-rose-600 hover:bg-rose-500' : 'bg-teal-600 hover:bg-teal-500'" 
                                    class="px-3 py-2 text-white font-bold rounded-xl transition shadow-md" 
                                    x-text="gradesLocked['Business Administration'] ? 'Unlock' : 'Lock'">
                            </button>
                        </div>

                        <!-- Midwifery Grades Control -->
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-4 border rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-sm text-amber-400">Midwifery Program</p>
                                <p class="text-[10px]" :class="gradesLocked['Midwifery'] ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold'" x-text="gradesLocked['Midwifery'] ? 'Status: LOCKED (Disabled)' : 'Status: OPEN for Encoding'"></p>
                            </div>
                            <button @click="gradesLocked['Midwifery'] = !gradesLocked['Midwifery']; triggerAlert('Midwifery grade status updated!');" 
                                    :class="gradesLocked['Midwifery'] ? 'bg-rose-600 hover:bg-rose-500' : 'bg-teal-600 hover:bg-teal-500'" 
                                    class="px-3 py-2 text-white font-bold rounded-xl transition shadow-md" 
                                    x-text="gradesLocked['Midwifery'] ? 'Unlock' : 'Lock'">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: NOTIFICATION LOGS -->
            <div x-show="currentTab === 'logs'" class="space-y-6" style="display: none;">
                <div :class="darkMode ? 'bg-slate-900/90 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-sm'" class="border rounded-3xl p-6 space-y-4 shadow-xl">
                    <div class="flex justify-between items-center border-b pb-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                        <div>
                            <h2 class="text-base font-extrabold flex items-center gap-2">
                                <i class="fa-solid fa-bell text-amber-400"></i> System Audit & Gmail SMTP Dispatch Logs
                            </h2>
                            <p class="text-xs text-slate-400">Real-time status of outgoing security emails.</p>
                        </div>
                        <button onclick="window.print()" :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-white' : 'bg-slate-200 hover:bg-slate-300 text-slate-800'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Print Logs
                        </button>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-3 border rounded-xl flex justify-between items-center">
                            <div>
                                <p class="font-bold">Password Reset Token Email</p>
                                <p class="text-[10px] text-slate-400">Dispatched to registered student inbox via SMTP</p>
                            </div>
                            <span class="text-emerald-400 font-bold">Sent via SMTP</span>
                        </div>
                        <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200'" class="p-3 border rounded-xl flex justify-between items-center">
                            <div>
                                <p class="font-bold">Login Security Alert Dispatched</p>
                                <p class="text-[10px] text-slate-400">Dispatched upon user authentication verification</p>
                            </div>
                            <span class="text-emerald-400 font-bold">Sent via SMTP</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- MODAL: ADD STUDENT -->
        <div x-show="addStudentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak>
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-user-plus text-emerald-400"></i> Add New Student
                    </h3>
                    <button @click="addStudentModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Student ID Number</label>
                        <input type="text" x-model="newStudent.student_id" placeholder="e.g. 2026-00123" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Full Legal Name</label>
                        <input type="text" x-model="newStudent.name" placeholder="Juan Dela Cruz" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Registered Gmail</label>
                        <input type="email" x-model="newStudent.email" placeholder="student@gmail.com" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Course / Program</label>
                            <select x-model="newStudent.course" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Block / Section</label>
                            <select x-model="newStudent.block" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="Block A">Block A</option>
                                <option value="Block B">Block B</option>
                                <option value="Block C">Block C</option>
                                <option value="Block D">Block D</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button @click="addStudentModal = false" :class="darkMode ? 'bg-slate-800 text-slate-300' : 'bg-slate-200 text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-xl">Cancel</button>
                    <button @click="saveNewStudent()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">Save to Database</button>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT STUDENT -->
        <div x-show="editStudentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak>
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-amber-400"></i> Edit Student Information
                    </h3>
                    <button @click="editStudentModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Student ID Number</label>
                        <input type="text" x-model="selectedStudent.student_id" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Full Legal Name</label>
                        <input type="text" x-model="selectedStudent.name" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Registered Gmail</label>
                        <input type="email" x-model="selectedStudent.email" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Course / Program</label>
                            <select x-model="selectedStudent.course" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Block / Section</label>
                            <select x-model="selectedStudent.block" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="Block A">Block A</option>
                                <option value="Block B">Block B</option>
                                <option value="Block C">Block C</option>
                                <option value="Block D">Block D</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button @click="editStudentModal = false" :class="darkMode ? 'bg-slate-800 text-slate-300' : 'bg-slate-200 text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-xl">Cancel</button>
                    <button @click="saveEditedStudent()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">Save Changes</button>
                </div>
            </div>
        </div>

        <!-- MODAL: ADD SUBJECT -->
        <div x-show="addSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak>
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-plus text-emerald-400"></i> Add New Subject
                    </h3>
                    <button @click="addSubjectModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Course</label>
                            <select x-model="newSubject.course" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Block</label>
                            <select x-model="newSubject.block" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="Block A">Block A</option>
                                <option value="Block B">Block B</option>
                                <option value="Block C">Block C</option>
                                <option value="Block D">Block D</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Subject Code</label>
                        <input type="text" x-model="newSubject.code" placeholder="e.g. IT 101" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Descriptive Title</label>
                        <input type="text" x-model="newSubject.title" placeholder="e.g. Introduction to Computing" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Instructor</label>
                        <input type="text" x-model="newSubject.instructor" placeholder="Prof. Name" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Room Assignment</label>
                        <input type="text" x-model="newSubject.room" placeholder="DIGITEL RM 1 / ONLINE" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button @click="addSubjectModal = false" :class="darkMode ? 'bg-slate-800 text-slate-300' : 'bg-slate-200 text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-xl">Cancel</button>
                    <button @click="addSubject()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">Save Subject</button>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT SUBJECT -->
        <div x-show="editSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak>
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-emerald-400"></i> Edit Subject
                    </h3>
                    <button @click="editSubjectModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Course</label>
                            <select x-model="selectedSubject.course" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="BSIT">BSIT</option>
                                <option value="Criminology">Criminology</option>
                                <option value="Education">Education</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Midwifery">Midwifery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-400 font-bold mb-1">Block</label>
                            <select x-model="selectedSubject.block" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                                <option value="Block A">Block A</option>
                                <option value="Block B">Block B</option>
                                <option value="Block C">Block C</option>
                                <option value="Block D">Block D</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Subject Code</label>
                        <input type="text" x-model="selectedSubject.code" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Descriptive Title</label>
                        <input type="text" x-model="selectedSubject.title" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Instructor</label>
                        <input type="text" x-model="selectedSubject.instructor" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 font-bold mb-1">Room Assignment</label>
                        <input type="text" x-model="selectedSubject.room" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-300 text-slate-900'" class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button @click="editSubjectModal = false" :class="darkMode ? 'bg-slate-800 text-slate-300' : 'bg-slate-200 text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-xl">Cancel</button>
                    <button @click="saveEditedSubject()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">Save Changes</button>
                </div>
            </div>
        </div>

        <!-- MY PROFILE MODAL -->
        <div x-show="profileModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4" x-cloak>
            <div :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-800 shadow-2xl'" class="border rounded-3xl max-w-md w-full p-6 space-y-5">
                <div class="flex justify-between items-center border-b pb-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-emerald-400"></i> Admin Profile
                    </h3>
                    <button @click="profileModal = false" class="text-slate-400 hover:text-rose-500 font-bold">&times;</button>
                </div>

                <div :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-100 border-slate-200'" class="space-y-3 p-4 rounded-2xl border text-xs">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Administrator Name</span>
                        <p class="font-bold text-sm">{{ Auth::user()->name ?? 'System Admin' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Registered Email</span>
                        <p class="text-amber-400 font-bold text-sm">{{ Auth::user()->email ?? 'admin@uepbinalonan.edu.ph' }}</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="profileModal = false" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">Close Profile</button>
                </div>
            </div>
        </div>

    </div>

</body>
</html>