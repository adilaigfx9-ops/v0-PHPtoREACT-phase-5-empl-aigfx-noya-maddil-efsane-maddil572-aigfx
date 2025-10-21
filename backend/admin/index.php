<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adil GFX CMS - Advanced Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.5; }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="cmsApp()" x-cloak class="min-h-screen">
        <!-- Login Screen -->
        <div x-show="!isAuthenticated" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">CMS Admin Panel</h2>
                    <p class="mt-2 text-sm text-gray-600">Advanced Content Management System</p>
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

        <!-- CMS Dashboard -->
        <div x-show="isAuthenticated" class="flex h-screen bg-gray-100">
            <!-- Enhanced Sidebar -->
            <div class="hidden md:flex md:w-64 md:flex-col">
                <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto border-r">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-xl font-bold text-gray-900">Adil GFX CMS</h1>
                    </div>
                    <div class="mt-5 flex-grow flex flex-col">
                        <nav class="flex-1 px-2 pb-4 space-y-1">
                            <!-- Dashboard -->
                            <a @click="currentView = 'dashboard'" :class="currentView === 'dashboard' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-chart-line mr-3"></i>
                                Dashboard
                            </a>

                            <!-- Global Settings -->
                            <a @click="currentView = 'settings'" :class="currentView === 'settings' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-cog mr-3"></i>
                                Global Settings
                            </a>

                            <!-- Page Management -->
                            <a @click="currentView = 'pages'" :class="currentView === 'pages' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-file-alt mr-3"></i>
                                Page Management
                            </a>

                            <!-- Carousel Management -->
                            <a @click="currentView = 'carousels'" :class="currentView === 'carousels' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-images mr-3"></i>
                                Carousels
                            </a>

                            <!-- Content Management -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Content</h3>
                                <a @click="currentView = 'blogs'" :class="currentView === 'blogs' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
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

                            <!-- User Management -->
                            <div class="mt-4">
                                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Users</h3>
                                <a @click="currentView = 'users'" :class="currentView === 'users' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-users mr-3"></i>
                                    Users
                                </a>
                                <a @click="currentView = 'contacts'" :class="currentView === 'contacts' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                    <i class="fas fa-envelope mr-3"></i>
                                    Contact Forms
                                </a>
                            </div>

                            <!-- Media -->
                            <a @click="currentView = 'media'" :class="currentView === 'media' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50'"
                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md cursor-pointer">
                                <i class="fas fa-photo-video mr-3"></i>
                                Media Library
                            </a>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <button @click="logout" class="flex-shrink-0 w-full group block">
                            <div class="flex items-center">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Logout</p>
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
                                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                        <i class="fas fa-search h-5 w-5"></i>
                                    </div>
                                    <input x-model="searchQuery" @input="handleSearch"
                                           class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent"
                                           placeholder="Search..." type="search">
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex items-center md:ml-6">
                            <span class="text-sm text-gray-700" x-text="'Welcome, ' + (user?.name || 'Admin')"></span>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <!-- Global Settings View -->
                    <div x-show="currentView === 'settings'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Global Settings</h1>
                            
                            <!-- Settings Tabs -->
                            <div class="bg-white shadow rounded-lg">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8 px-6">
                                        <template x-for="category in settingsCategories" :key="category">
                                            <button @click="activeSettingsTab = category"
                                                    :class="activeSettingsTab === category ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm capitalize"
                                                    x-text="category">
                                            </button>
                                        </template>
                                    </nav>
                                </div>
                                
                                <div class="p-6">
                                    <!-- Branding Settings -->
                                    <div x-show="activeSettingsTab === 'branding'" class="space-y-6">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Brand Identity</h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                                                <div class="flex items-center space-x-4">
                                                    <img x-show="settings.branding?.site_logo?.value" 
                                                         :src="settings.branding?.site_logo?.value" 
                                                         class="h-12 w-auto" alt="Current logo">
                                                    <input type="file" @change="handleFileUpload($event, 'site_logo')" 
                                                           accept="image/*" class="text-sm">
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                                                <input type="color" 
                                                       x-model="settings.branding.primary_color.value"
                                                       @change="updateSetting('primary_color', $event.target.value)"
                                                       class="h-10 w-20 border border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Settings -->
                                    <div x-show="activeSettingsTab === 'contact'" class="space-y-6">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                                <input type="email" 
                                                       x-model="settings.contact?.contact_email?.value"
                                                       @blur="updateSetting('contact_email', $event.target.value)"
                                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                                <input type="tel" 
                                                       x-model="settings.contact?.contact_phone?.value"
                                                       @blur="updateSetting('contact_phone', $event.target.value)"
                                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Media Settings -->
                                    <div x-show="activeSettingsTab === 'social'" class="space-y-6">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                                        
                                        <div class="space-y-4">
                                            <template x-for="(setting, key) in settings.social" :key="key">
                                                <div class="flex items-center space-x-4">
                                                    <label class="w-24 text-sm font-medium text-gray-700 capitalize" x-text="key.replace('social_', '')"></label>
                                                    <input type="url" 
                                                           x-model="setting.value"
                                                           @blur="updateSetting(key, $event.target.value)"
                                                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Feature Toggles -->
                                    <div x-show="activeSettingsTab === 'features'" class="space-y-6">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Feature Controls</h3>
                                        
                                        <div class="space-y-4">
                                            <template x-for="(setting, key) in settings.features" :key="key">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-700 capitalize" x-text="key.replace('enable_', '').replace('_', ' ')"></label>
                                                        <p class="text-xs text-gray-500" x-text="setting.description"></p>
                                                    </div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" 
                                                               x-model="setting.value"
                                                               @change="updateSetting(key, $event.target.checked)"
                                                               class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                                    </label>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Analytics & Integrations -->
                                    <div x-show="activeSettingsTab === 'integrations'" class="space-y-6">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Analytics & Integrations</h3>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                                                <input type="text" 
                                                       x-model="settings.analytics?.google_analytics_id?.value"
                                                       @blur="updateSetting('google_analytics_id', $event.target.value)"
                                                       placeholder="G-XXXXXXXXXX"
                                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Mailchimp API Key</label>
                                                <input type="password" 
                                                       x-model="settings.integrations?.mailchimp_api_key?.value"
                                                       @blur="updateSetting('mailchimp_api_key', $event.target.value)"
                                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page Management View -->
                    <div x-show="currentView === 'pages'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-semibold text-gray-900">Page Management</h1>
                                <button @click="showPageModal = true; editingPage = null"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    <i class="fas fa-plus mr-2"></i>Add New Page
                                </button>
                            </div>

                            <!-- Pages Table -->
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <template x-for="page in pages" :key="page.id">
                                        <li class="px-6 py-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="drag-handle text-gray-400 hover:text-gray-600">
                                                            <i class="fas fa-grip-vertical"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-sm font-medium text-gray-900" x-text="page.title"></h3>
                                                            <p class="text-sm text-gray-500">
                                                                <span x-text="'/' + page.slug"></span>
                                                                <span x-show="page.showInNav" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    In Navigation
                                                                </span>
                                                                <span x-show="page.status === 'draft'" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    Draft
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button @click="editPage(page)" class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button @click="deletePage(page.id)" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Management View -->
                    <div x-show="currentView === 'carousels'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-semibold text-gray-900">Carousel Management</h1>
                                <button @click="showSlideModal = true; editingSlide = null"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    <i class="fas fa-plus mr-2"></i>Add New Slide
                                </button>
                            </div>

                            <!-- Carousels List -->
                            <div class="space-y-6">
                                <template x-for="carousel in carousels" :key="carousel.name">
                                    <div class="bg-white shadow rounded-lg">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h3 class="text-lg font-medium text-gray-900 capitalize" x-text="carousel.name + ' Carousel'"></h3>
                                            <p class="text-sm text-gray-500" x-text="carousel.slideCount + ' slides'"></p>
                                        </div>
                                        <div class="p-6">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                <template x-for="slide in carousel.slides" :key="slide.id">
                                                    <div class="border border-gray-200 rounded-lg p-4">
                                                        <img x-show="slide.imageUrl" :src="slide.imageUrl" :alt="slide.title" class="w-full h-32 object-cover rounded-md mb-3">
                                                        <h4 class="font-medium text-gray-900 mb-1" x-text="slide.title"></h4>
                                                        <p class="text-sm text-gray-500 mb-3" x-text="slide.subtitle"></p>
                                                        <div class="flex space-x-2">
                                                            <button @click="editSlide(slide)" class="text-blue-600 hover:text-blue-900 text-sm">
                                                                <i class="fas fa-edit mr-1"></i>Edit
                                                            </button>
                                                            <button @click="deleteSlide(slide.id)" class="text-red-600 hover:text-red-900 text-sm">
                                                                <i class="fas fa-trash mr-1"></i>Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Media Library View -->
                    <div x-show="currentView === 'media'" class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-semibold text-gray-900">Media Library</h1>
                                <div class="flex space-x-3">
                                    <input type="file" @change="uploadMedia($event)" multiple accept="image/*,video/*" 
                                           class="hidden" id="media-upload">
                                    <label for="media-upload" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md cursor-pointer">
                                        <i class="fas fa-upload mr-2"></i>Upload Media
                                    </label>
                                </div>
                            </div>

                            <!-- Media Grid -->
                            <div class="bg-white shadow rounded-lg p-6">
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    <template x-for="media in mediaLibrary" :key="media.id">
                                        <div class="relative group border border-gray-200 rounded-lg overflow-hidden">
                                            <img :src="media.url" :alt="media.altText" class="w-full h-32 object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button @click="deleteMedia(media.id)" class="text-white hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="p-2">
                                                <p class="text-xs text-gray-600 truncate" x-text="media.originalName"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- Blogs Management View -->
<div x-show="currentView === 'blogs'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Blog Management</h1>
            <button @click="showBlogModal = true; editingBlog = null"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-2"></i>Add New Blog
            </button>
        </div>

        <!-- Blogs Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <template x-for="blog in blogs" :key="blog.id">
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900" x-text="blog.title"></h3>
                                <p class="text-sm text-gray-500">
                                    <span x-text="blog.category"></span> • 
                                    <span x-text="new Date(blog.created_at).toLocaleDateString()"></span>
                                </p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                    <span><i class="fas fa-eye mr-1"></i><span x-text="blog.views || 0"></span> views</span>
                                    <span x-show="blog.featured" class="bg-red-100 text-red-800 px-2 py-1 rounded">Featured</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="editBlog(blog)" class="text-blue-600 hover:text-blue-900 p-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteBlog(blog.id)" class="text-red-600 hover:text-red-900 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>

<!-- Blog Modal -->
<div x-show="showBlogModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingBlog ? 'Edit Blog' : 'Add New Blog'"></h3>
            <form @submit.prevent="saveBlog">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input x-model="blogForm.title" type="text" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select x-model="blogForm.category" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="">Select Category</option>
                                <option value="Design Tips">Design Tips</option>
                                <option value="YouTube Growth">YouTube Growth</option>
                                <option value="Branding">Branding</option>
                                <option value="Case Studies">Case Studies</option>
                                <option value="Tutorials">Tutorials</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Featured Image URL</label>
                            <input x-model="blogForm.featured_image" type="url"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Excerpt</label>
                        <textarea x-model="blogForm.excerpt" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea x-model="blogForm.content" rows="10" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tags (comma-separated)</label>
                        <input x-model="blogForm.tags" type="text"
                               placeholder="design, branding, tips"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input x-model="blogForm.featured" type="checkbox" id="blog-featured"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="blog-featured" class="ml-2 block text-sm text-gray-900">Featured Post</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showBlogModal = false" type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" :disabled="blogSaving"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                        <span x-show="!blogSaving" x-text="editingBlog ? 'Update' : 'Create'"></span>
                        <span x-show="blogSaving">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Portfolio Management View -->
<div x-show="currentView === 'portfolio'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Portfolio Management</h1>
            <button @click="showPortfolioModal = true; editingPortfolio = null"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-2"></i>Add New Project
            </button>
        </div>

        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="item in portfolio" :key="item.id">
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <img x-show="item.image_url" :src="item.image_url" :alt="item.title" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1" x-text="item.title"></h3>
                        <p class="text-sm text-gray-500 mb-2" x-text="item.category"></p>
                        <p class="text-xs text-gray-600 mb-3 line-clamp-2" x-text="item.description"></p>
                        <div class="flex space-x-2">
                            <button @click="editPortfolio(item)" class="text-blue-600 hover:text-blue-900 text-sm">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button @click="deletePortfolio(item.id)" class="text-red-600 hover:text-red-900 text-sm">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Portfolio Modal -->
<div x-show="showPortfolioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingPortfolio ? 'Edit Project' : 'Add New Project'"></h3>
            <form @submit.prevent="savePortfolio">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Title</label>
                        <input x-model="portfolioForm.title" type="text" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select x-model="portfolioForm.category" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="">Select Category</option>
                                <option value="Logo Design">Logo Design</option>
                                <option value="Thumbnail Design">Thumbnail Design</option>
                                <option value="Complete Branding">Complete Branding</option>
                                <option value="Video Editing">Video Editing</option>
                                <option value="Web Design">Web Design</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Client Name</label>
                            <input x-model="portfolioForm.client_name" type="text"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea x-model="portfolioForm.description" rows="4" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Main Image URL</label>
                            <input x-model="portfolioForm.image_url" type="url"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Before Image URL</label>
                            <input x-model="portfolioForm.before_image" type="url"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Technologies Used (comma-separated)</label>
                        <input x-model="portfolioForm.technologies" type="text"
                               placeholder="Photoshop, Illustrator, After Effects"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input x-model="portfolioForm.featured" type="checkbox" id="portfolio-featured"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="portfolio-featured" class="ml-2 block text-sm text-gray-900">Featured Project</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showPortfolioModal = false" type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" :disabled="portfolioSaving"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                        <span x-show="!portfolioSaving" x-text="editingPortfolio ? 'Update' : 'Create'"></span>
                        <span x-show="portfolioSaving">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Services Management View -->
<div x-show="currentView === 'services'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Services Management</h1>
            <button @click="showServiceModal = true; editingService = null"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-2"></i>Add New Service
            </button>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <template x-for="service in services" :key="service.id">
                <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900" x-text="service.name"></h3>
                            <p class="text-sm text-gray-500" x-text="service.category"></p>
                        </div>
                        <span x-show="service.popular" class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Popular</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4" x-text="service.description"></p>
                    <div class="text-sm text-gray-700 mb-4">
                        <span class="font-semibold">Starting at:</span>
                        <span class="text-red-600 font-bold" x-text="'$' + (service.price || 0)"></span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="editService(service)" class="text-blue-600 hover:text-blue-900 text-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button @click="deleteService(service.id)" class="text-red-600 hover:text-red-900 text-sm">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Service Modal -->
<div x-show="showServiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingService ? 'Edit Service' : 'Add New Service'"></h3>
            <form @submit.prevent="saveService">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input x-model="serviceForm.name" type="text" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select x-model="serviceForm.category" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="">Select Category</option>
                                <option value="Design">Design</option>
                                <option value="Video">Video</option>
                                <option value="Branding">Branding</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Starting Price ($)</label>
                            <input x-model="serviceForm.price" type="number" step="0.01" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea x-model="serviceForm.description" rows="4" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Delivery Time (days)</label>
                            <input x-model="serviceForm.delivery_time" type="number" min="1"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon</label>
                            <select x-model="serviceForm.icon"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="🎨">🎨 Design</option>
                                <option value="📸">📸 Photography</option>
                                <option value="🎬">🎬 Video</option>
                                <option value="💻">💻 Development</option>
                                <option value="✏️">✏️ Writing</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Features (one per line)</label>
                        <textarea x-model="serviceForm.features" rows="5"
                                  placeholder="Feature 1&#10;Feature 2&#10;Feature 3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input x-model="serviceForm.popular" type="checkbox" id="service-popular"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="service-popular" class="ml-2 block text-sm text-gray-900">Mark as Popular</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showServiceModal = false" type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" :disabled="serviceSaving"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                        <span x-show="!serviceSaving" x-text="editingService ? 'Update' : 'Create'"></span>
                        <span x-show="serviceSaving">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Testimonials Management View -->
<div x-show="currentView === 'testimonials'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Testimonials Management</h1>
            <button @click="showTestimonialModal = true; editingTestimonial = null"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-2"></i>Add New Testimonial
            </button>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="testimonial in testimonials" :key="testimonial.id">
                <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <img x-show="testimonial.avatar_url" :src="testimonial.avatar_url" :alt="testimonial.client_name" 
                             class="w-12 h-12 rounded-full object-cover mr-3">
                        <div>
                            <h3 class="font-medium text-gray-900" x-text="testimonial.client_name"></h3>
                            <p class="text-sm text-gray-500" x-text="testimonial.role"></p>
                        </div>
                    </div>
                    <div class="flex mb-2">
                        <template x-for="i in 5" :key="i">
                            <i :class="i <= (testimonial.rating || 0) ? 'fas fa-star text-yellow-400' : 'far fa-star text-gray-300'"></i>
                        </template>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3" x-text="testimonial.content"></p>
                    <div class="flex items-center justify-between">
                        <span x-show="testimonial.verified" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Verified</span>
                        <div class="flex space-x-2">
                            <button @click="editTestimonial(testimonial)" class="text-blue-600 hover:text-blue-900 text-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="deleteTestimonial(testimonial.id)" class="text-red-600 hover:text-red-900 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Testimonial Modal -->
<div x-show="showTestimonialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingTestimonial ? 'Edit Testimonial' : 'Add New Testimonial'"></h3>
            <form @submit.prevent="saveTestimonial">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Client Name</label>
                            <input x-model="testimonialForm.client_name" type="text" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role/Company</label>
                            <input x-model="testimonialForm.role" type="text"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Testimonial Content</label>
                        <textarea x-model="testimonialForm.content" rows="5" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rating (1-5 stars)</label>
                            <select x-model="testimonialForm.rating" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Avatar URL</label>
                            <input x-model="testimonialForm.avatar_url" type="url"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Type</label>
                        <input x-model="testimonialForm.project_type" type="text"
                               placeholder="e.g., Logo Design, YouTube Management"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input x-model="testimonialForm.verified" type="checkbox" id="testimonial-verified"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="testimonial-verified" class="ml-2 block text-sm text-gray-900">Verified Testimonial</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showTestimonialModal = false" type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" :disabled="testimonialSaving"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                        <span x-show="!testimonialSaving" x-text="editingTestimonial ? 'Update' : 'Create'"></span>
                        <span x-show="testimonialSaving">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Users Management View -->
<div x-show="currentView === 'users'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">User Management</h1>

        <!-- Users Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tokens</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Streak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="user in users" :key="user.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-500 flex items-center justify-center text-white font-medium"
                                             x-text="user.name.charAt(0).toUpperCase()"></div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="user.name"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="user.email"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'bg-red-100 text-red-800': user.role === 'admin',
                                    'bg-green-100 text-green-800': user.role === 'editor',
                                    'bg-yellow-100 text-yellow-800': user.role === 'viewer',
                                    'bg-blue-100 text-blue-800': user.role === 'user'
                                }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize" x-text="user.role"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                <span x-text="user.token_balance || 0"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-fire text-red-500 mr-1"></i>
                                <span x-text="(user.current_streak || 0) + ' days'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="new Date(user.created_at).toLocaleDateString()"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button @click="editUserRole(user)" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-user-cog"></i>
                                </button>
                                <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900"
                                        x-show="user.role !== 'admin' || users.filter(u => u.role === 'admin').length > 1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit User Role Modal -->
<div x-show="showUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit User Role</h3>
            <form @submit.prevent="saveUserRole">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                        <p class="text-sm text-gray-900" x-text="userForm.name"></p>
                        <p class="text-xs text-gray-500" x-text="userForm.email"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select x-model="userForm.role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="user">User - Portal access only</option>
                            <option value="viewer">Viewer - Read-only admin access</option>
                            <option value="editor">Editor - Can edit content</option>
                            <option value="admin">Admin - Full access</option>
                        </select>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3">
                        <p class="text-xs text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Changing roles affects what this user can access in the admin panel.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showUserModal = false" type="button"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" :disabled="userSaving"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                        <span x-show="!userSaving">Update Role</span>
                        <span x-show="userSaving">Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contact Forms View -->
<div x-show="currentView === 'contacts'" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Contact Form Submissions</h1>

        <!-- Contact Forms Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <template x-for="contact in contacts" :key="contact.id">
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-sm font-medium text-gray-900 mr-3" x-text="contact.name"></h3>
                                    <span x-show="!contact.read" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">New</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fas fa-envelope mr-1"></i>
                                    <span x-text="contact.email"></span>
                                </p>
                                <p class="text-sm text-gray-600 mb-2" x-show="contact.phone">
                                    <i class="fas fa-phone mr-1"></i>
                                    <span x-text="contact.phone"></span>
                                </p>
                                <p class="text-sm font-medium text-gray-700 mb-1" x-show="contact.subject" x-text="'Subject: ' + contact.subject"></p>
                                <p class="text-sm text-gray-600" x-text="contact.message"></p>
                                <p class="text-xs text-gray-500 mt-2" x-text="'Submitted: ' + new Date(contact.created_at).toLocaleString()"></p>
                            </div>
                            <div class="flex flex-col space-y-2 ml-4">
                                <button @click="markAsRead(contact.id)" 
                                        x-show="!contact.read"
                                        class="text-blue-600 hover:text-blue-900 text-sm whitespace-nowrap">
                                    <i class="fas fa-check-circle mr-1"></i>Mark as Read
                                </button>
                                <button @click="deleteContact(contact.id)" 
                                        class="text-red-600 hover:text-red-900 text-sm whitespace-nowrap">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </li>
                </template>
                
                <li x-show="contacts.length === 0" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No contact form submissions yet.</p>
                </li>
            </ul>
        </div>
    </div>
</div>

                </main>
            </div>
        </div>

        <!-- Page Modal -->
        <div x-show="showPageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingPage ? 'Edit Page' : 'Add New Page'"></h3>
                    <form @submit.prevent="savePage">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Page Title</label>
                                <input x-model="pageForm.title" type="text" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select x-model="pageForm.status" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Show in Navigation</label>
                                    <select x-model="pageForm.showInNav"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                                <textarea x-model="pageForm.metaDescription" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Page Sections (JSON)</label>
                                <textarea x-model="pageForm.sections" rows="6" placeholder='[{"type": "hero", "title": "Welcome", "content": "..."}]'
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 font-mono text-sm"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Define page sections as JSON array</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click="showPageModal = false" type="button"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </button>
                            <button type="submit" :disabled="pageSaving"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                                <span x-show="!pageSaving" x-text="editingPage ? 'Update' : 'Create'"></span>
                                <span x-show="pageSaving">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Slide Modal -->
        <div x-show="showSlideModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingSlide ? 'Edit Slide' : 'Add New Slide'"></h3>
                    <form @submit.prevent="saveSlide">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carousel Name</label>
                                <select x-model="slideForm.carouselName" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="hero">Hero Carousel</option>
                                    <option value="services">Services Carousel</option>
                                    <option value="testimonials">Testimonials Carousel</option>
                                    <option value="portfolio">Portfolio Carousel</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input x-model="slideForm.title" type="text"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subtitle</label>
                                    <input x-model="slideForm.subtitle" type="text"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea x-model="slideForm.description" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image URL</label>
                                    <input x-model="slideForm.imageUrl" type="url"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CTA Text</label>
                                    <input x-model="slideForm.ctaText" type="text"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">CTA URL</label>
                                <input x-model="slideForm.ctaUrl" type="url"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click="showSlideModal = false" type="button"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </button>
                            <button type="submit" :disabled="slideSaving"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50">
                                <span x-show="!slideSaving" x-text="editingSlide ? 'Update' : 'Create'"></span>
                                <span x-show="slideSaving">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cmsApp() {
            return {
                isAuthenticated: false,
                user: null,
                currentView: 'dashboard',
                searchQuery: '',
                
                // Login
                loginForm: { email: '', password: '' },
                loginLoading: false,
                loginError: '',
                
                // Settings
                settings: {},
                settingsCategories: ['branding', 'contact', 'social', 'analytics', 'integrations', 'features'],
                activeSettingsTab: 'branding',
                
                // Pages
                pages: [],
                showPageModal: false,
                editingPage: null,
                pageForm: {
                    title: '',
                    status: 'draft',
                    showInNav: true,
                    metaDescription: '',
                    sections: '[]'
                },
                pageSaving: false,
                
                // Carousels
                carousels: [],
                showSlideModal: false,
                editingSlide: null,
                slideForm: {
                    carouselName: 'hero',
                    title: '',
                    subtitle: '',
                    description: '',
                    imageUrl: '',
                    ctaText: '',
                    ctaUrl: ''
                },
                slideSaving: false,
                
                // Media
                mediaLibrary: [],

                blogs: [],
showBlogModal: false,
editingBlog: null,
blogForm: {
    title: '',
    category: '',
    excerpt: '',
    content: '',
    featured_image: '',
    tags: '',
    featured: false
},
blogSaving: false,

// Portfolio
portfolio: [],
showPortfolioModal: false,
editingPortfolio: null,
portfolioForm: {
    title: '',
    category: '',
    client_name: '',
    description: '',
    image_url: '',
    before_image: '',
    technologies: '',
    featured: false
},
portfolioSaving: false,

// Services
services: [],
showServiceModal: false,
editingService: null,
serviceForm: {
    name: '',
    category: '',
    description: '',
    price: '',
    delivery_time: '',
    icon: '🎨',
    features: '',
    popular: false
},
serviceSaving: false,

// Testimonials
testimonials: [],
showTestimonialModal: false,
editingTestimonial: null,
testimonialForm: {
    client_name: '',
    role: '',
    content: '',
    rating: 5,
    avatar_url: '',
    project_type: '',
    verified: false
},
testimonialSaving: false,

// Users
users: [],
showUserModal: false,
editingUser: null,
userForm: {
    id: null,
    name: '',
    email: '',
    role: 'user'
},
userSaving: false,

// Contacts
contacts: [],

// Stats for dashboard
stats: {
    totalUsers: 0,
    totalBlogs: 0,
    totalContacts: 0,
    totalPortfolio: 0,
    totalTokens: 0
},
recentActivity: [],


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
                            if (data.user?.role === 'admin') {
                                this.isAuthenticated = true;
                                this.user = data.user;
                                this.loadInitialData();
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

                        if (response.ok && data.user?.role === 'admin') {
                            localStorage.setItem('admin_token', data.token);
                            this.isAuthenticated = true;
                            this.user = data.user;
                            this.loadInitialData();
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

                async loadInitialData() {
                    await Promise.all([
                        this.loadStats(),
                        this.loadSettings(),
                        this.loadPages(),
                        this.loadCarousels(),
                        this.loadMediaLibrary(),
                        this.loadBlogs(),
                        this.loadPortfolio(),
                        this.loadServices(),
                        this.loadTestimonials(),
                        this.loadUsers(),
                        this.loadContacts()
                    ]);
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
                },

                async updateSetting(key, value) {
                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch(`../api/settings.php/${key}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify({ value })
                        });

                        if (response.ok) {
                            console.log(`Setting ${key} updated successfully`);
                        }
                    } catch (error) {
                        console.error('Failed to update setting:', error);
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
                            this.mediaLibrary = data.data;
                        }
                    } catch (error) {
                        console.error('Failed to load media library:', error);
                    }
                },

                editPage(page) {
                    this.editingPage = page;
                    this.pageForm = {
                        title: page.title,
                        status: page.status,
                        showInNav: page.showInNav,
                        metaDescription: page.metaDescription || '',
                        sections: JSON.stringify(page.sections || [], null, 2)
                    };
                    this.showPageModal = true;
                },

                async savePage() {
                    this.pageSaving = true;
                    
                    try {
                        const token = localStorage.getItem('admin_token');
                        const url = this.editingPage 
                            ? `../api/pages.php/${this.editingPage.id}`
                            : '../api/pages.php';
                        
                        const method = this.editingPage ? 'PUT' : 'POST';
                        
                        const formData = {
                            ...this.pageForm,
                            sections: JSON.parse(this.pageForm.sections || '[]')
                        };

                        const response = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(formData)
                        });

                        if (response.ok) {
                            this.showPageModal = false;
                            this.loadPages();
                            this.resetPageForm();
                        } else {
                            const error = await response.json();
                            alert('Error: ' + (error.error || 'Failed to save page'));
                        }
                    } catch (error) {
                        alert('Error: Failed to save page');
                    } finally {
                        this.pageSaving = false;
                    }
                },

                async deletePage(id) {
                    if (!confirm('Are you sure you want to delete this page?')) return;

                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch(`../api/pages.php/${id}`, {
                            method: 'DELETE',
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.ok) {
                            this.loadPages();
                        } else {
                            alert('Failed to delete page');
                        }
                    } catch (error) {
                        alert('Error: Failed to delete page');
                    }
                },

                resetPageForm() {
                    this.pageForm = {
                        title: '',
                        status: 'draft',
                        showInNav: true,
                        metaDescription: '',
                        sections: '[]'
                    };
                },

                editSlide(slide) {
                    this.editingSlide = slide;
                    this.slideForm = { ...slide };
                    this.showSlideModal = true;
                },

                async saveSlide() {
                    this.slideSaving = true;
                    
                    try {
                        const token = localStorage.getItem('admin_token');
                        const url = this.editingSlide 
                            ? `../api/carousel.php/${this.editingSlide.id}`
                            : '../api/carousel.php';
                        
                        const method = this.editingSlide ? 'PUT' : 'POST';

                        const response = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(this.slideForm)
                        });

                        if (response.ok) {
                            this.showSlideModal = false;
                            this.loadCarousels();
                            this.resetSlideForm();
                        } else {
                            const error = await response.json();
                            alert('Error: ' + (error.error || 'Failed to save slide'));
                        }
                    } catch (error) {
                        alert('Error: Failed to save slide');
                    } finally {
                        this.slideSaving = false;
                    }
                },

                async deleteSlide(id) {
                    if (!confirm('Are you sure you want to delete this slide?')) return;

                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch(`../api/carousel.php/${id}`, {
                            method: 'DELETE',
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.ok) {
                            this.loadCarousels();
                        } else {
                            alert('Failed to delete slide');
                        }
                    } catch (error) {
                        alert('Error: Failed to delete slide');
                    }
                },

                resetSlideForm() {
                    this.slideForm = {
                        carouselName: 'hero',
                        title: '',
                        subtitle: '',
                        description: '',
                        imageUrl: '',
                        ctaText: '',
                        ctaUrl: ''
                    };
                },

                async uploadMedia(event) {
                    const files = event.target.files;
                    if (!files.length) return;

                    const token = localStorage.getItem('admin_token');
                    
                    for (let file of files) {
                        const formData = new FormData();
                        formData.append('file', file);

                        try {
                            const response = await fetch('../api/uploads.php', {
                                method: 'POST',
                                headers: { 'Authorization': `Bearer ${token}` },
                                body: formData
                            });

                            if (response.ok) {
                                console.log('File uploaded successfully');
                            }
                        } catch (error) {
                            console.error('Upload failed:', error);
                        }
                    }
                    
                    this.loadMediaLibrary();
                    event.target.value = ''; // Reset file input
                },

                async deleteMedia(id) {
                    if (!confirm('Are you sure you want to delete this media file?')) return;

                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch(`../api/uploads.php/${id}`, {
                            method: 'DELETE',
                            headers: { 'Authorization': `Bearer ${token}` }
                        });

                        if (response.ok) {
                            this.loadMediaLibrary();
                        } else {
                            alert('Failed to delete media');
                        }
                    } catch (error) {
                        alert('Error: Failed to delete media');
                    }
                },

                handleSearch() {
                    console.log('Searching for:', this.searchQuery);
                },

                async handleFileUpload(event, settingKey) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('file', file);

                    try {
                        const token = localStorage.getItem('admin_token');
                        const response = await fetch('../api/uploads.php', {
                            method: 'POST',
                            headers: { 'Authorization': `Bearer ${token}` },
                            body: formData
                        });

                        if (response.ok) {
                            const data = await response.json();
                            await this.updateSetting(settingKey, data.file.url);
                            this.loadSettings();
                        }
                    } catch (error) {
                        console.error('File upload failed:', error);
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

editBlog(blog) {
    this.editingBlog = blog;
    this.blogForm = {
        title: blog.title,
        category: blog.category,
        excerpt: blog.excerpt,
        content: blog.content,
        featured_image: blog.featured_image || '',
        tags: (blog.tags || []).join(', '),
        featured: blog.featured || false
    };
    this.showBlogModal = true;
},

async saveBlog() {
    this.blogSaving = true;
    try {
        const token = localStorage.getItem('admin_token');
        const url = this.editingBlog 
            ? `../api/blogs.php/${this.editingBlog.id}`
            : '../api/blogs.php';
        const method = this.editingBlog ? 'PUT' : 'POST';

        const formData = {
            ...this.blogForm,
            tags: this.blogForm.tags.split(',').map(t => t.trim()).filter(t => t)
        };

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            this.showBlogModal = false;
            this.loadBlogs();
            this.resetBlogForm();
            alert('Blog saved successfully!');
        } else {
            const error = await response.json();
            alert('Error: ' + (error.error || 'Failed to save blog'));
        }
    } catch (error) {
        alert('Error: Failed to save blog');
    } finally {
        this.blogSaving = false;
    }
},

async deleteBlog(id) {
    if (!confirm('Are you sure you want to delete this blog?')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/blogs.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadBlogs();
            alert('Blog deleted successfully!');
        }
    } catch (error) {
        alert('Error: Failed to delete blog');
    }
},

resetBlogForm() {
    this.blogForm = {
        title: '',
        category: '',
        excerpt: '',
        content: '',
        featured_image: '',
        tags: '',
        featured: false
    };
    this.editingBlog = null;
},

// === PORTFOLIO FUNCTIONS ===
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

editPortfolio(item) {
    this.editingPortfolio = item;
    this.portfolioForm = {
        title: item.title,
        category: item.category,
        client_name: item.client_name || '',
        description: item.description,
        image_url: item.image_url || '',
        before_image: item.before_image || '',
        technologies: (item.technologies || []).join(', '),
        featured: item.featured || false
    };
    this.showPortfolioModal = true;
},

async savePortfolio() {
    this.portfolioSaving = true;
    try {
        const token = localStorage.getItem('admin_token');
        const url = this.editingPortfolio 
            ? `../api/portfolio.php/${this.editingPortfolio.id}`
            : '../api/portfolio.php';
        const method = this.editingPortfolio ? 'PUT' : 'POST';

        const formData = {
            ...this.portfolioForm,
            technologies: this.portfolioForm.technologies.split(',').map(t => t.trim()).filter(t => t)
        };

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            this.showPortfolioModal = false;
            this.loadPortfolio();
            this.resetPortfolioForm();
            alert('Portfolio item saved successfully!');
        } else {
            alert('Error: Failed to save portfolio item');
        }
    } catch (error) {
        alert('Error: Failed to save portfolio item');
    } finally {
        this.portfolioSaving = false;
    }
},

async deletePortfolio(id) {
    if (!confirm('Are you sure you want to delete this portfolio item?')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/portfolio.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadPortfolio();
            alert('Portfolio item deleted successfully!');
        }
    } catch (error) {
        alert('Error: Failed to delete portfolio item');
    }
},

resetPortfolioForm() {
    this.portfolioForm = {
        title: '',
        category: '',
        client_name: '',
        description: '',
        image_url: '',
        before_image: '',
        technologies: '',
        featured: false
    };
    this.editingPortfolio = null;
},

// === SERVICES FUNCTIONS ===
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

editService(service) {
    this.editingService = service;
    this.serviceForm = {
        name: service.name,
        category: service.category || '',
        description: service.description,
        price: service.price || '',
        delivery_time: service.delivery_time || '',
        icon: service.icon || '🎨',
        features: (service.features || []).join('\n'),
        popular: service.popular || false
    };
    this.showServiceModal = true;
},

async saveService() {
    this.serviceSaving = true;
    try {
        const token = localStorage.getItem('admin_token');
        const url = this.editingService 
            ? `../api/services.php/${this.editingService.id}`
            : '../api/services.php';
        const method = this.editingService ? 'PUT' : 'POST';

        const formData = {
            ...this.serviceForm,
            features: this.serviceForm.features.split('\n').map(f => f.trim()).filter(f => f)
        };

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            this.showServiceModal = false;
            this.loadServices();
            this.resetServiceForm();
            alert('Service saved successfully!');
        } else {
            alert('Error: Failed to save service');
        }
    } catch (error) {
        alert('Error: Failed to save service');
    } finally {
        this.serviceSaving = false;
    }
},

async deleteService(id) {
    if (!confirm('Are you sure you want to delete this service?')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/services.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadServices();
            alert('Service deleted successfully!');
        }
    } catch (error) {
        alert('Error: Failed to delete service');
    }
},

resetServiceForm() {
    this.serviceForm = {
        name: '',
        category: '',
        description: '',
        price: '',
        delivery_time: '',
        icon: '🎨',
        features: '',
        popular: false
    };
    this.editingService = null;
},

// === TESTIMONIALS FUNCTIONS ===
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

editTestimonial(testimonial) {
    this.editingTestimonial = testimonial;
    this.testimonialForm = {
        client_name: testimonial.client_name,
        role: testimonial.role || '',
        content: testimonial.content,
        rating: testimonial.rating || 5,
        avatar_url: testimonial.avatar_url || '',
        project_type: testimonial.project_type || '',
        verified: testimonial.verified || false
    };
    this.showTestimonialModal = true;
},

async saveTestimonial() {
    this.testimonialSaving = true;
    try {
        const token = localStorage.getItem('admin_token');
        const url = this.editingTestimonial 
            ? `../api/testimonials.php/${this.editingTestimonial.id}`
            : '../api/testimonials.php';
        const method = this.editingTestimonial ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(this.testimonialForm)
        });

        if (response.ok) {
            this.showTestimonialModal = false;
            this.loadTestimonials();
            this.resetTestimonialForm();
            alert('Testimonial saved successfully!');
        } else {
            alert('Error: Failed to save testimonial');
        }
    } catch (error) {
        alert('Error: Failed to save testimonial');
    } finally {
        this.testimonialSaving = false;
    }
},

async deleteTestimonial(id) {
    if (!confirm('Are you sure you want to delete this testimonial?')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/testimonials.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadTestimonials();
            alert('Testimonial deleted successfully!');
        }
    } catch (error) {
        alert('Error: Failed to delete testimonial');
    }
},

resetTestimonialForm() {
    this.testimonialForm = {
        client_name: '',
        role: '',
        content: '',
        rating: 5,
        avatar_url: '',
        project_type: '',
        verified: false
    };
    this.editingTestimonial = null;
},

// === USERS FUNCTIONS ===
async loadUsers() {
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch('../api/admin/users.php', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            const data = await response.json();
            this.users = data.users || data.data || data;
        }
    } catch (error) {
        console.error('Failed to load users:', error);
    }
},

editUserRole(user) {
    this.editingUser = user;
    this.userForm = {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role
    };
    this.showUserModal = true;
},

async saveUserRole() {
    this.userSaving = true;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/admin/users.php/${this.userForm.id}/role`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ role: this.userForm.role })
        });

        if (response.ok) {
            this.showUserModal = false;
            this.loadUsers();
            alert('User role updated successfully!');
        } else {
            alert('Error: Failed to update user role');
        }
    } catch (error) {
        alert('Error: Failed to update user role');
    } finally {
        this.userSaving = false;
    }
},

async deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user? This cannot be undone.')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/admin/users.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadUsers();
            alert('User deleted successfully!');
        } else {
            alert('Error: Failed to delete user');
        }
    } catch (error) {
        alert('Error: Failed to delete user');
    }
},

// === CONTACTS FUNCTIONS ===
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

async markAsRead(id) {
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/contact.php/${id}/read`, {
            method: 'PUT',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadContacts();
        }
    } catch (error) {
        console.error('Failed to mark as read:', error);
    }
},

async deleteContact(id) {
    if (!confirm('Are you sure you want to delete this contact submission?')) return;
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch(`../api/contact.php/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            this.loadContacts();
            alert('Contact deleted successfully!');
        }
    } catch (error) {
        alert('Error: Failed to delete contact');
    }
},

// === STATS & ACTIVITY ===
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

    // Load recent activity
    try {
        const token = localStorage.getItem('admin_token');
        const response = await fetch('../api/admin/activity.php', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (response.ok) {
            const data = await response.json();
            this.recentActivity = (data.activities || data.data || data).slice(0, 10);
        }
    } catch (error) {
        console.error('Failed to load activity:', error);
    }
}
            }
        }
    </script>
</body>
</html>