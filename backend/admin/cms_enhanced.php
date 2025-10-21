<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adil GFX - Complete Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.5; }
        .tooltip { position: relative; }
        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background: rgba(0,0,0,0.8);
            color: white;
            font-size: 12px;
            white-space: nowrap;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="adminApp()" x-cloak class="min-h-screen">
        <!-- Login Screen -->
        <div x-show="!isAuthenticated" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Adil GFX Admin Panel</h2>
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
            <!-- Enhanced Sidebar -->
            <div class="hidden md:flex md:w-64 md:flex-col">
                <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto border-r">
                    <div class="flex items-center flex-shrink-0 px-4 mb-8">
                        <h1 class="text-xl font-bold text-gray-900">Adil GFX Admin</h1>
                    </div>
                    <div class="mt-5 flex-grow flex flex-col">
                        <nav class="flex-1 px-2 pb-4 space-y-1">
                            <!-- Dashboard -->
                            <a @click="currentView = 'dashboard'" :class="currentView === 'dashboard' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-chart-line mr-3"></i>
                                Dashboard
                            </a>

                            <!-- Analytics -->
                            <a @click="currentView = 'analytics'" :class="currentView === 'analytics' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-chart-bar mr-3"></i>
                                Analytics
                            </a>

                            <!-- Content Section -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Content</h3>
                                <a @click="currentView = 'blogs'" :class="currentView === 'blogs' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                    <i class="fas fa-blog mr-3"></i>
                                    Blogs
                                </a>
                                <a @click="currentView = 'portfolio'" :class="currentView === 'portfolio' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-briefcase mr-3"></i>
                                    Portfolio
                                </a>
                                <a @click="currentView = 'services'" :class="currentView === 'services' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-cogs mr-3"></i>
                                    Services
                                </a>
                                <a @click="currentView = 'testimonials'" :class="currentView === 'testimonials' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-star mr-3"></i>
                                    Testimonials
                                </a>
                            </div>

                            <!-- CMS Section -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">CMS</h3>
                                <a @click="currentView = 'pages'" :class="currentView === 'pages' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                    <i class="fas fa-file-alt mr-3"></i>
                                    Pages
                                </a>
                                <a @click="currentView = 'carousels'" :class="currentView === 'carousels' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-images mr-3"></i>
                                    Carousels
                                </a>
                                <a @click="currentView = 'media'" :class="currentView === 'media' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-photo-video mr-3"></i>
                                    Media Library
                                </a>
                            </div>

                            <!-- User Management -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Users</h3>
                                <a @click="currentView = 'users'" :class="currentView === 'users' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                    <i class="fas fa-users mr-3"></i>
                                    Users
                                </a>
                                <a @click="currentView = 'contacts'" :class="currentView === 'contacts' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-envelope mr-3"></i>
                                    Contact Forms
                                </a>
                            </div>

                            <!-- Advanced -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Advanced</h3>
                                <a @click="currentView = 'translations'" :class="currentView === 'translations' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-1">
                                    <i class="fas fa-language mr-3"></i>
                                    Translations
                                </a>
                                <a @click="currentView = 'notifications'" :class="currentView === 'notifications' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-bell mr-3"></i>
                                    Notifications
                                </a>
                                <a @click="currentView = 'audit'" :class="currentView === 'audit' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-history mr-3"></i>
                                    Audit Log
                                </a>
                                <a @click="currentView = 'funnel'" :class="currentView === 'funnel' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-filter mr-3"></i>
                                    Funnel Testing
                                </a>
                            </div>

                            <!-- Settings -->
                            <a @click="currentView = 'settings'" :class="currentView === 'settings' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer mt-4">
                                <i class="fas fa-cog mr-3"></i>
                                Settings
                            </a>
                        </nav>
                    </div>
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

            <!-- Main Content Area -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <!-- Top Navigation -->
                <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                    <div class="flex-1 px-4 flex justify-between">
                        <div class="flex-1 flex">
                            <div class="w-full flex md:ml-0">
                                <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none pl-3">
                                        <i class="fas fa-search h-5 w-5"></i>
                                    </div>
                                    <input x-model="searchQuery" @input="handleSearch"
                                           class="block w-full h-full pl-10 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent"
                                           placeholder="Search..." type="search">
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex items-center md:ml-6 space-x-4">
                            <!-- Notifications Bell -->
                            <button @click="showNotifications = !showNotifications" class="relative p-2 rounded-full text-gray-400 hover:text-gray-500">
                                <i class="fas fa-bell text-xl"></i>
                                <span x-show="notificationCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full" x-text="notificationCount"></span>
                            </button>
                            <span class="text-sm text-gray-700" x-text="'Welcome, ' + (user?.name || 'Admin')"></span>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none" x-init="loadViewData()">
                    
                    <!-- Dashboard View -->
                    <div x-show="currentView === 'dashboard'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard Overview</h1>
                            
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                                    <div class="p-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-users text-blue-500 text-2xl"></i>
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
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-blog text-green-500 text-2xl"></i>
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
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-envelope text-yellow-500 text-2xl"></i>
                                            </div>
                                            <div class="ml-5 w-0 flex-1">
                                                <dl>
                                                    <dt class="text-sm font-medium text-gray-500 truncate">Contact Forms</dt>
                                                    <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalContacts || 0"></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                                    <div class="p-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-coins text-red-500 text-2xl"></i>
                                            </div>
                                            <div class="ml-5 w-0 flex-1">
                                                <dl>
                                                    <dt class="text-sm font-medium text-gray-500 truncate">Tokens Issued</dt>
                                                    <dd class="text-2xl font-bold text-gray-900" x-text="stats.totalTokens || 0"></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                                <div class="bg-white shadow rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Growth</h3>
                                    <canvas id="userGrowthChart" class="w-full" style="height: 250px;"></canvas>
                                </div>
                                
                                <div class="bg-white shadow rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Distribution</h3>
                                    <canvas id="contentDistChart" class="w-full" style="height: 250px;"></canvas>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="bg-white shadow rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                                    <div class="space-y-3">
                                        <template x-for="activity in recentActivity" :key="activity.id">
                                            <div class="flex items-center space-x-3 py-2 border-b border-gray-100 last:border-0">
                                                <div class="flex-shrink-0">
                                                    <i :class="activity.icon" class="text-gray-400"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-900" x-text="activity.description"></p>
                                                    <p class="text-xs text-gray-500" x-text="activity.time"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics View -->
                    <div x-show="currentView === 'analytics'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Analytics & Reports</h1>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                                <div class="bg-white shadow rounded-lg p-6">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Total Page Views</h3>
                                    <p class="text-3xl font-bold text-gray-900" x-text="analytics.pageViews || 0"></p>
                                    <p class="text-xs text-green-600 mt-1">+12% from last month</p>
                                </div>
                                
                                <div class="bg-white shadow rounded-lg p-6">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Conversion Rate</h3>
                                    <p class="text-3xl font-bold text-gray-900" x-text="(analytics.conversionRate || 0) + '%'"></p>
                                    <p class="text-xs text-green-600 mt-1">+3.2% from last month</p>
                                </div>
                                
                                <div class="bg-white shadow rounded-lg p-6">
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Avg. Session Duration</h3>
                                    <p class="text-3xl font-bold text-gray-900" x-text="(analytics.avgSession || 0) + 'm'"></p>
                                    <p class="text-xs text-red-600 mt-1">-5% from last month</p>
                                </div>
                            </div>

                            <div class="bg-white shadow rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Traffic Sources</h3>
                                <canvas id="trafficSourcesChart" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Blogs Management - Due to character limit, I'll create this as a separate comprehensive file -->
                    <!-- Portfolio, Services, Testimonials, Users, etc. will follow the same pattern -->
                    
                </main>
            </div>
        </div>
    </div>

    <script>
        // This is Part 1 - Showing the structure. The complete implementation will be in the final file.
        // I'll continue with the full JavaScript implementation...
    </script>
</body>
</html>
