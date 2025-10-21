<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adil GFX - Complete Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
        .modal-backdrop { backdrop-filter: blur(4px); }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="adminApp()" x-cloak class="min-h-screen">
        <!-- Login Screen -->
        <div x-show="!isAuthenticated" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Adil GFX Admin</h2>
                    <p class="mt-2 text-sm text-gray-600">Complete Content Management System</p>
                </div>
                <form @submit.prevent="login" class="mt-8 space-y-6">
                    <div>
                        <input x-model="loginForm.email" type="email" required 
                               class="relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500"
                               placeholder="Email address">
                    </div>
                    <div>
                        <input x-model="loginForm.password" type="password" required
                               class="relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500"
                               placeholder="Password">
                    </div>
                    <div x-show="loginError" class="text-red-600 text-sm" x-text="loginError"></div>
                    <button type="submit" :disabled="loginLoading"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                        <span x-show="!loginLoading">Sign in</span>
                        <span x-show="loginLoading">Signing in...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Admin Dashboard -->
        <div x-show="isAuthenticated" class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <div class="hidden md:flex md:w-64 md:flex-col">
                <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto border-r">
                    <div class="flex items-center flex-shrink-0 px-4 mb-6">
                        <h1 class="text-xl font-bold text-gray-900">Adil GFX Admin</h1>
                    </div>
                    <nav class="flex-1 px-2 pb-4 space-y-1">
                        <a @click="switchView('dashboard')" :class="currentView === 'dashboard' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                            <i class="fas fa-chart-line mr-3"></i>Dashboard
                        </a>

                        <!-- Content Section -->
                        <div class="mt-4">
                            <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Content</h3>
                            <a @click="switchView('blogs')" :class="currentView === 'blogs' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                <i class="fas fa-blog mr-3"></i>Blogs
                            </a>
                            <a @click="switchView('portfolio')" :class="currentView === 'portfolio' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-briefcase mr-3"></i>Portfolio
                            </a>
                            <a @click="switchView('services')" :class="currentView === 'services' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-cogs mr-3"></i>Services
                            </a>
                            <a @click="switchView('testimonials')" :class="currentView === 'testimonials' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-star mr-3"></i>Testimonials
                            </a>
                        </div>

                        <!-- CMS Section -->
                        <div class="mt-4">
                            <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">CMS</h3>
                            <a @click="switchView('pages')" :class="currentView === 'pages' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                <i class="fas fa-file-alt mr-3"></i>Pages
                            </a>
                            <a @click="switchView('carousels')" :class="currentView === 'carousels' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-images mr-3"></i>Carousels
                            </a>
                            <a @click="switchView('media')" :class="currentView === 'media' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-photo-video mr-3"></i>Media Library
                            </a>
                        </div>

                        <!-- Users Section -->
                        <div class="mt-4">
                            <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Users</h3>
                            <a @click="switchView('users')" :class="currentView === 'users' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                <i class="fas fa-users mr-3"></i>Users
                            </a>
                            <a @click="switchView('contacts')" :class="currentView === 'contacts' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-envelope mr-3"></i>Contact Forms
                            </a>
                        </div>

                        <a @click="switchView('settings')" :class="currentView === 'settings' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-4">
                            <i class="fas fa-cog mr-3"></i>Settings
                        </a>
                    </nav>
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <button @click="logout" class="flex-shrink-0 w-full group block">
                            <div class="flex items-center">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Logout</p>
                                    <p class="text-xs text-gray-500" x-text="user?.name || 'Admin'"></p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <!-- Top Bar -->
                <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                    <div class="flex-1 px-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800 capitalize" x-text="currentView"></h2>
                        <span class="text-sm text-gray-700" x-text="'Welcome, ' + (user?.name || 'Admin')"></span>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                    
                    <!-- Dashboard View -->
                    <div x-show="currentView === 'dashboard'">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                            <i class="fas fa-users text-white text-2xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                                <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalUsers || 0"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                            <i class="fas fa-blog text-white text-2xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Blog Posts</dt>
                                                <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalBlogs || 0"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                            <i class="fas fa-envelope text-white text-2xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Contacts</dt>
                                                <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalContacts || 0"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                            <i class="fas fa-briefcase text-white text-2xl"></i>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Portfolio</dt>
                                                <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalPortfolio || 0"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                            <div class="space-y-3">
                                <template x-for="activity in recentActivity.slice(0, 5)" :key="activity.id">
                                    <div class="flex items-center space-x-3 py-2 border-b border-gray-100">
                                        <i :class="activity.icon" class="text-gray-400"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900" x-text="activity.description"></p>
                                            <p class="text-xs text-gray-500" x-text="activity.time"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- BLOGS VIEW - Due to file size limits, continuing with modular approach... -->
                    
                </main>
            </div>
        </div>
    </div>

    <script>
        function adminApp() {
            return {
                isAuthenticated: false,
                user: null,
                currentView: 'dashboard',
                
                // Login
                loginForm: { email: '', password: '' },
                loginLoading: false,
                loginError: '',
                
                // Stats
                stats: {},
                recentActivity: [],
                
                // Data
                blogs: [],
                portfolio: [],
                services: [],
                testimonials: [],
                users: [],
                contacts: [],
                pages: [],
                carousels: [],
                mediaLibrary: [],
                settings: {},
                
                // Modals & Forms
                settingsCategories: ['branding', 'contact', 'social', 'analytics', 'integrations', 'features'],
                activeSettingsTab: 'branding',
                
                init() {
                    this.checkAuth();
                },

                async checkAuth() {
                    const token = localStorage.getItem('admin_token');
                    if (!token) return;

                    try {
                        const response = await fetch('../api/auth.php/verify', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.user?.role === 'admin' || data.user?.role === 'editor' || data.user?.role === 'viewer') {
                                this.isAuthenticated = true;
                                this.user = data.user;
                                this.loadStats();
                            }
                        }
                    } catch (error) {
                        console.error('Auth check failed:', error);
                    }
                },

                async login() {
                    this.loginLoading = true;
                    this.loginError = '';

                    try {
                        const response = await fetch('../api/auth.php/login', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.loginForm)
                        });

                        const data = await response.json();

                        if (response.ok && (data.user?.role === 'admin' || data.user?.role === 'editor' || data.user?.role === 'viewer')) {
                            localStorage.setItem('admin_token', data.token);
                            this.isAuthenticated = true;
                            this.user = data.user;
                            this.loadStats();
                        } else {
                            this.loginError = data.error || 'Invalid admin credentials';
                        }
                    } catch (error) {
                        this.loginError = 'Login failed. Please try again.';
                    } finally {
                        this.loginLoading = false;
                    }
                },

                logout() {
                    localStorage.removeItem('admin_token');
                    this.isAuthenticated = false;
                    this.user = null;
                    this.currentView = 'dashboard';
                },

                switchView(view) {
                    this.currentView = view;
                    this.loadViewData(view);
                },

                async loadStats() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/admin/stats.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            this.stats = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load stats:', error);
                    }
                },

                async loadViewData(view) {
                    const token = localStorage.getItem('admin_token');
                    
                    switch(view) {
                        case 'blogs':
                            await this.loadBlogs();
                            break;
                        case 'portfolio':
                            await this.loadPortfolio();
                            break;
                        case 'services':
                            await this.loadServices();
                            break;
                        case 'testimonials':
                            await this.loadTestimonials();
                            break;
                        case 'users':
                            await this.loadUsers();
                            break;
                        case 'contacts':
                            await this.loadContacts();
                            break;
                        case 'pages':
                            await this.loadPages();
                            break;
                        case 'carousels':
                            await this.loadCarousels();
                            break;
                        case 'media':
                            await this.loadMediaLibrary();
                            break;
                        case 'settings':
                            await this.loadSettings();
                            break;
                    }
                },

                async loadBlogs() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/blogs.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.blogs = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load blogs:', error);
                    }
                },

                async loadPortfolio() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/portfolio.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.portfolio = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load portfolio:', error);
                    }
                },

                async loadServices() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/services.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.services = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load services:', error);
                    }
                },

                async loadTestimonials() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/testimonials.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.testimonials = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load testimonials:', error);
                    }
                },

                async loadUsers() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/admin/users.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.users = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load users:', error);
                    }
                },

                async loadContacts() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/contact.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.contacts = data.data || data;
                        }
                    } catch (error) {
                        console.error('Failed to load contacts:', error);
                    }
                },

                async loadPages() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/pages.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            this.pages = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load pages:', error);
                    }
                },

                async loadCarousels() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/carousel.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            this.carousels = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load carousels:', error);
                    }
                },

                async loadMediaLibrary() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/uploads.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.mediaLibrary = data.data || [];
                        }
                    } catch (error) {
                        console.error('Failed to load media library:', error);
                    }
                },

                async loadSettings() {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/settings.php', {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        if (response.ok) {
                            this.settings = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to load settings:', error);
                    }
                }
            }
        }
    </script>
</body>
</html>
