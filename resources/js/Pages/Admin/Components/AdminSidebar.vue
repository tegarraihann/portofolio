<template>
    <aside class="w-64 bg-indigo-700 min-h-screen text-white shadow-lg">
        <!-- Header -->
        <div class="p-6 border-b border-indigo-600">
            <div class="text-2xl font-bold text-white text-center">
                Admin Panel
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <!-- Dashboard -->
            <Link :href="route('dashboard')"
                class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                :class="{ 'bg-indigo-600': $page.url === '/admin/dashboard' }">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            Dashboard
            </Link>

            <!-- Content Management Section -->
            <div class="pt-4">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wide mb-2 px-3">
                    Content Management
                </div>

                <!-- Articles Dropdown -->
                <div class="space-y-1">
                    <button @click="toggleArticlesMenu"
                        class="flex items-center justify-between w-full p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                        :class="{ 'bg-indigo-600': isArticlesMenuOpen || $page.url.includes('/admin/articles') }">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Artikel
                        </div>
                        <svg class="w-4 h-4 transform transition-transform duration-200"
                            :class="{ 'rotate-180': isArticlesMenuOpen }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div v-show="isArticlesMenuOpen" class="ml-8 space-y-1">
                        <Link :href="route('admin.articles.index')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/articles' }">
                        Semua Artikel
                        </Link>
                        <Link :href="route('admin.articles.create')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/articles/create' }">
                        Tambah Artikel
                        </Link>
                        <Link :href="route('admin.articles.drafts')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/articles/drafts' }">
                        Draft Artikel
                        </Link>
                        <Link :href="route('admin.categories.index')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/categories' }">
                        Kategori
                        </Link>
                        <Link :href="route('admin.tags.index')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/tags' }">
                        Tags
                        </Link>
                    </div>
                </div>

                <!-- Projects Dropdown -->
                <div class="space-y-1">
                    <button @click="toggleProjectsMenu"
                        class="flex items-center justify-between w-full p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                        :class="{ 'bg-indigo-600': isProjectsMenuOpen || $page.url.includes('/admin/projects') }">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Projects
                        </div>
                        <svg class="w-4 h-4 transform transition-transform duration-200"
                            :class="{ 'rotate-180': isProjectsMenuOpen }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div v-show="isProjectsMenuOpen" class="ml-8 space-y-1">
                        <Link :href="route('admin.projects.index')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/projects' }">
                        Semua Projects
                        </Link>
                        <Link :href="route('admin.projects.create')"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/projects/create' }">
                        Tambah Project
                        </Link>
                        <!-- <Link href="/admin/project-category"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/project-categories' }">
                        Kategori Project
                        </Link>
                        <Link href="/admin/technologies"
                            class="block p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-600 transition-colors duration-200"
                            :class="{ 'text-white bg-indigo-600': $page.url === '/admin/technologies' }">
                        Technology Stack
                        </Link> -->
                    </div>
                </div>

                <!-- Media Library -->
                <Link href="/admin/media"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/media' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Media Library
                </Link>
            </div>

            <!-- Portfolio Management Section -->
            <div class="pt-4">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wide mb-2 px-3">
                    Portfolio Management
                </div>

                <Link href="/admin/about"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/about' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                About Management
                </Link>

                <Link href="/admin/hero"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/hero' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z">
                    </path>
                </svg>
                Hero Section
                </Link>

                <Link href="/admin/testimonials"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/testimonials' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                Testimonials
                </Link>

                <Link href="/admin/services"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/services' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6">
                    </path>
                </svg>
                Services
                </Link>
            </div>

            <!-- Analytics & Communication Section -->
            <div class="pt-4">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wide mb-2 px-3">
                    Analytics & Communication
                </div>

                <Link href="/admin/analytics"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/analytics' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Analytics
                </Link>

                <Link href="/admin/messages"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/messages' }">
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Contact Messages
                </div>
                <span v-if="unreadMessages > 0" class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">
                    {{ unreadMessages }}
                </span>
                </Link>

                <Link href="/admin/seo"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/seo' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                SEO Management
                </Link>
            </div>

            <!-- Settings Section -->
            <div class="pt-4">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wide mb-2 px-3">
                    Settings
                </div>

                <Link href="/admin/profile"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/profile' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Profile Settings
                </Link>

                <Link href="/admin/social-media"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'text-white bg-indigo-600': $page.url === '/admin/social-media' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                    </path>
                </svg>
                Social Media
                </Link>

                <Link :href="route('admin.cv.index')"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/cv' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11V3m0 8l-3-3m3 3l3-3m-9 4v7a2 2 0 002 2h8a2 2 0 002-2v-7m-2 4H9">
                    </path>
                </svg>
                CV Download
                </Link>

                <Link href="/admin/site-settings"
                    class="flex items-center p-3 rounded-lg text-white hover:bg-indigo-600 transition-colors duration-200"
                    :class="{ 'bg-indigo-600': $page.url === '/admin/site-settings' }">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Site Settings
                </Link>
            </div>

            <!-- Logout -->
            <div class="pt-6 border-t border-indigo-600 mt-6">
                <Link href="/logout" method="post" as="button"
                    class="flex items-center w-full p-3 rounded-lg text-white hover:bg-red-600 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
                </Link>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Dropdown states
const isArticlesMenuOpen = ref(false)
const isProjectsMenuOpen = ref(false)

// Example unread messages count (you can get this from props or API)
const unreadMessages = ref(3)

// Toggle functions
const toggleArticlesMenu = () => {
    isArticlesMenuOpen.value = !isArticlesMenuOpen.value
}

const toggleProjectsMenu = () => {
    isProjectsMenuOpen.value = !isProjectsMenuOpen.value
}
</script>
