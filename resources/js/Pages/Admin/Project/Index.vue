<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Kelola Projects</h1>
                            <p class="text-gray-600 mt-1">Manage semua project portfolio Anda.</p>
                        </div>
                        <Link :href="route('admin.projects.create')"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Project
                        </Link>
                    </div>
                </div>

                <!-- Filter and Search -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Cari Project
                            </label>
                            <div class="relative">
                                <input id="search" v-model="filters.search" type="text"
                                    placeholder="Cari berdasarkan judul atau teknologi..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori
                            </label>
                            <select id="category-filter" v-model="filters.category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Kategori</option>
                                <option value="fullstack">Full Stack</option>
                                <option value="frontend">Frontend</option>
                                <option value="backend">Backend</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status-filter" v-model="filters.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                                <option value="featured">Unggulan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div v-if="projects.length === 0" class="bg-white rounded-xl p-6 text-center text-gray-500">
                    Belum ada project.
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="project in filteredProjects" :key="project.id"
                        class="bg-white border rounded-xl p-4 hover:shadow transition">
                        <div class="relative h-40 bg-gray-100 flex items-center justify-center mb-4">
                            <img v-if="project.thumbnail" :src="`/storage/${project.thumbnail}`" alt=""
                                class="w-full h-full object-cover" />
                            <div v-if="project.is_featured"
                                class="absolute top-2 left-2 bg-yellow-400 text-white text-xs px-2 py-1 rounded-full font-semibold shadow">
                                Unggulan
                            </div>
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs text-white rounded-full font-semibold"
                                    :class="project.is_active ? 'bg-green-500' : 'bg-red-500'">
                                    {{ project.is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>

                        <h2 class="font-bold text-lg text-gray-900 truncate">{{ project.title }}</h2>
                        <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ project.description }}</p>
                        <div class="text-xs uppercase text-gray-500 mb-2 font-semibold">{{
                            project.category?.toUpperCase() }}</div>

                        <div class="flex flex-wrap gap-1 mb-3">
                            <span v-for="(tech, index) in (project.tech_stack ?? []).slice(0, 3)" :key="index"
                                class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ tech }}</span>
                            <span v-if="(project.tech_stack ?? []).length > 3"
                                class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded">
                                +{{ project.tech_stack.length - 3 }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm mt-2">
                            <div class="space-x-2">
                                <Link :href="route('admin.projects.show', project.id)"
                                    class="text-blue-600 hover:underline">Lihat</Link>
                                <Link :href="route('admin.projects.edit', project.id)"
                                    class="text-gray-700 hover:underline">Edit</Link>
                            </div>
                            <button @click="deleteProject(project.id)"
                                class="text-red-600 hover:underline">Hapus</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, computed, reactive } from 'vue'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    projects: Array
})
const viewMode = ref('grid')


const projects = ref(props.projects ?? [])

const filters = reactive({
    search: '',
    category: '',
    status: ''
})

const filteredProjects = computed(() => {
    let filtered = projects.value

    // Search filter
    if (filters.search) {
        const searchTerm = filters.search.toLowerCase()
        filtered = filtered.filter(project => {
            const title = (project.title || '').toLowerCase()
            const description = (project.description || '').toLowerCase()
            const techs = Array.isArray(project.tech_stack) ? project.tech_stack : []
            return (
                title.includes(searchTerm) ||
                description.includes(searchTerm) ||
                techs.some(tech => (tech || '').toLowerCase().includes(searchTerm))
            )
        })
    }

    // Category filter
    if (filters.category) {
        filtered = filtered.filter(project => project.category === filters.category)
    }

    // Status filter
    if (filters.status) {
        switch (filters.status) {
            case 'active':
                filtered = filtered.filter(project => project.is_active)
                break
            case 'inactive':
                filtered = filtered.filter(project => !project.is_active)
                break
            case 'featured':
                filtered = filtered.filter(project => project.is_featured)
                break
        }
    }

    // Sort by sort_order
    return filtered.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0))
})

// Methods
const getCategoryLabel = (category) => {
    const labels = {
        fullstack: 'Full Stack',
        frontend: 'Frontend',
        backend: 'Backend'
    }
    return labels[category] || category
}

const deleteProject = async (id) => {
    const result = await Swal.fire({
        title: 'Hapus project?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
    })

    if (!result.isConfirmed) return

    router.delete(route('admin.projects.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('admin.projects.index'), {
                preserveScroll: true,
                replace: true,
                only: ['projects'],
            })
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Project dihapus',
                showConfirmButton: false,
                timer: 1500,
            })
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menghapus project',
            })
        },
    })
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Hover effects */
.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Responsive table */
@media (max-width: 768px) {
    .overflow-x-auto {
        overflow-x: auto;
    }

    table {
        min-width: 600px;
    }
}

/* Animation for grid items */
.grid>div {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status badge styles */
.bg-yellow-500 {
    background-color: #f59e0b;
}

.bg-green-500 {
    background-color: #10b981;
}

.bg-red-500 {
    background-color: #ef4444;
}

.bg-yellow-100 {
    background-color: #fef3c7;
}

.text-yellow-800 {
    color: #92400e;
}

.bg-green-100 {
    background-color: #dcfce7;
}

.text-green-800 {
    color: #166534;
}

.bg-red-100 {
    background-color: #fee2e2;
}

.text-red-800 {
    color: #991b1b;
}
</style>
