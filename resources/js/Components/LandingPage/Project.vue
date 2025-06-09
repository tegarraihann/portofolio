<script setup>
import { ref, computed } from 'vue'
import { useMotion } from '@vueuse/motion'

const props = defineProps({
  projects: Array
})

const selectedCategory = ref('all')
const isLoading = ref(false)

const filteredProjects = computed(() => {
  if (selectedCategory.value === 'all') {
    return props.projects
  }
  return props.projects.filter(project => project.category === selectedCategory.value)
})

const categories = computed(() => {
  const all = props.projects.length
  const fullstack = props.projects.filter(p => p.category === 'fullstack').length
  const frontend = props.projects.filter(p => p.category === 'frontend').length
  const backend = props.projects.filter(p => p.category === 'backend').length
  return [
    { id: 'all', name: 'Semua Project', count: all },
    { id: 'fullstack', name: 'Fullstack', count: fullstack },
    { id: 'frontend', name: 'Frontend', count: frontend },
    { id: 'backend', name: 'Backend', count: backend }
  ]
})

const filterProjects = (category) => {
  isLoading.value = true
  setTimeout(() => {
    selectedCategory.value = category
    isLoading.value = false
  }, 300)
}
</script>

<template>
  <section id="projects" class="py-20 bg-white relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
      <div v-motion-pop-visible class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
          Proyek Saya
        </h2>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto rounded-full mb-6"></div>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Kumpulan project yang telah saya kerjakan menggunakan teknologi modern
        </p>
      </div>

      <div v-motion-pop-visible :delay="200" class="flex flex-wrap justify-center gap-4 mb-12">
        <button
          v-for="category in categories"
          :key="category.id"
          @click="filterProjects(category.id)"
          :class="[
            'px-6 py-3 rounded-full font-medium transition-all duration-300 transform hover:scale-105',
            selectedCategory === category.id
              ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ category.name }}
          <span class="ml-2 text-sm opacity-75">({{ category.count }})</span>
        </button>
      </div>

      <div v-if="isLoading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="(project, index) in filteredProjects"
          :key="project.id"
          v-motion-pop-visible
          :delay="400 + (index * 100)"
          class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-blue-200 transform hover:-translate-y-3 min-h-[460px]"
        >
          <div class="relative h-48 overflow-hidden">
            <img
              v-if="project.thumbnail"
              :src="`/storage/${project.thumbnail}`"
              alt="Project Thumbnail"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-all duration-300"></div>
            <div v-if="project.is_featured" class="absolute top-4 left-4 z-10">
              <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                ⭐ Featured
              </span>
            </div>
            <div v-if="project.demo_url || project.github_url" class="absolute inset-0 flex items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <a
                v-if="project.demo_url"
                :href="project.demo_url"
                target="_blank"
                class="bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-all duration-200"
                title="Live Demo"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0c3.866 0 7 4.03 7 9s-3.134 9-7 9-7-4.03-7-9 3.134-9 7-9z"/>
                </svg>
              </a>
            </div>
          </div>

          <div class="p-6">
            <div class="mb-2">
              <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-800 uppercase">
                {{ project.category }}
              </span>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-3">
              {{ project.title }}
            </h3>

            <p class="text-gray-600 text-sm mb-3 leading-relaxed">
              {{ project.description }}
            </p>

            <div class="flex flex-wrap gap-2 mb-3">
              <span
                v-for="tech in Array.isArray(project.tech_stack) ? project.tech_stack : JSON.parse(project.tech_stack || '[]')"
                :key="tech"
                class="text-xs font-medium px-3 py-1 bg-gray-100 text-gray-800 rounded"
              >
                {{ tech }}
              </span>
            </div>

            <div class="flex items-center gap-6 text-sm mt-4 border-t pt-4 text-gray-600">
              <a
                v-if="project.live_demo_url"
                :href="project.live_demo_url"
                target="_blank"
                class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 underline"
              >
                <span>Lihat Demo</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-6-10h6m0 0v6m0-6L10 14"></path>
                </svg>
              </a>
              <a
                v-if="project.github_url"
                :href="project.github_url"
                target="_blank"
                class="hover:text-black flex items-center gap-1"
              >
                <span>GitHub</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 0C5.37 0 0 5.373 0 12a12 12 0 008.207 11.387c.6.111.793-.261.793-.577v-2.234C6.2 20.137 5.578 18.36 5.578 18.36c-.546-1.387-1.333-1.756-1.333-1.756-1.088-.745.084-.729.084-.729 1.204.084 1.838 1.237 1.838 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.666-.305-5.468-1.334-5.468-5.931 0-1.311.468-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23a11.512 11.512 0 013.003-.404c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.652 1.653.241 2.874.117 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576A12 12 0 0024 12c0-6.627-5.373-12-12-12z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
