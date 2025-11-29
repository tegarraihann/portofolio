<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />

      <main class="flex-1 p-6">
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ project.title }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ project.category || 'Tidak ada kategori' }}</p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full"
              :class="project.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
              {{ project.is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
            <span v-if="project.is_featured" class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
              Unggulan
            </span>
            <Link :href="route('admin.projects.edit', project.id)" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
              Edit
            </Link>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
          <div class="w-full h-60 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
            <img v-if="project.thumbnail" :src="`/storage/${project.thumbnail}`" alt="Thumbnail" class="w-full h-full object-cover" />
            <span v-else class="text-gray-400 text-sm">Tidak ada thumbnail</span>
          </div>

          <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-1">Deskripsi</h2>
            <p class="text-gray-800 whitespace-pre-wrap">{{ project.description || 'Tidak ada deskripsi' }}</p>
          </div>

          <div v-if="project.tech_stack?.length">
            <h2 class="text-sm font-semibold text-gray-700 mb-1">Tech Stack</h2>
            <div class="flex flex-wrap gap-2">
              <span v-for="(tech, idx) in project.tech_stack" :key="idx" class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                {{ tech }}
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h2 class="text-sm font-semibold text-gray-700 mb-1">Live Demo</h2>
              <a v-if="project.live_demo_url" :href="project.live_demo_url" target="_blank" class="text-indigo-600 hover:underline text-sm">
                {{ project.live_demo_url }}
              </a>
              <span v-else class="text-gray-500 text-sm">-</span>
            </div>
            <div>
              <h2 class="text-sm font-semibold text-gray-700 mb-1">Repository</h2>
              <a v-if="project.github_url" :href="project.github_url" target="_blank" class="text-indigo-600 hover:underline text-sm">
                {{ project.github_url }}
              </a>
              <span v-else class="text-gray-500 text-sm">-</span>
            </div>
          </div>

          <div class="text-sm text-gray-500">
            <div>Dibuat: {{ new Date(project.created_at).toLocaleDateString('id-ID') }}</div>
            <div>Diperbarui: {{ new Date(project.updated_at).toLocaleDateString('id-ID') }}</div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import { computed } from 'vue'

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
})

const project = computed(() => props.project || {})
</script>
