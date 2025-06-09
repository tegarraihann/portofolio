<template>
  <section id="latest-articles" class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
      <!-- Header Section -->
      <div v-motion-pop-visible class="text-center mb-20">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mb-6 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 011 1l4 4v9a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h2 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-purple-600 bg-clip-text text-transparent mb-6">
          Artikel Terbaru
        </h2>
        <div class="w-32 h-1.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 mx-auto rounded-full mb-6 shadow-sm"></div>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Temukan wawasan terbaru dalam pengembangan web, teknologi, dan tips praktis untuk meningkatkan skill coding Anda
        </p>
      </div>

      <!-- Artikel Cards -->
      <div v-if="articles && articles.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <article
          v-for="(article, index) in articles"
          :key="article.id"
          class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 overflow-hidden border border-gray-100/50 hover:border-blue-200/50 transform hover:-translate-y-4 hover:rotate-1"
          :class="[
            index === 0 ? 'md:col-span-2 xl:col-span-1' : '',
            'animate-fade-in-up'
          ]"
          :style="{ animationDelay: `${index * 150}ms` }"
        >
          <!-- Gradient Overlay Background -->
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-transparent to-purple-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

          <!-- Floating Badge -->
          <div class="absolute top-4 right-4 z-20">
            <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full border border-gray-200/50 shadow-lg">
              <span class="text-xs font-medium text-gray-600">{{ formatDate(article.created_at) }}</span>
            </div>
          </div>

          <!-- Image Container -->
          <div class="relative h-64 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>

            <img
              v-if="article.thumbnail_path"
              :src="`/storage/${article.thumbnail_path}`"
              :alt="article.title"
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center relative">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20"></div>
              <svg class="w-20 h-20 text-white/80 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 011 1l4 4v9a2 2 0 01-2 2z"></path>
              </svg>
            </div>

            <!-- Category Badge in Image -->
            <div v-if="article.category" class="absolute bottom-4 left-4 z-20">
              <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/95 backdrop-blur-sm border border-white/20 shadow-lg">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                <span class="text-sm font-semibold text-gray-700">{{ article.category.name }}</span>
              </span>
            </div>
          </div>

          <!-- Content Container -->
          <div class="p-8 relative z-10">
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors duration-300">
              <a :href="`/articles/${article.slug}`" class="hover:underline decoration-2 underline-offset-4">
                {{ article.title }}
              </a>
            </h3>

            <!-- Excerpt -->
            <p class="text-gray-600 text-base mb-6 leading-relaxed line-clamp-3">
              {{ article.excerpt || article.content?.substring(0, 150) + '...' }}
            </p>

            <!-- Tech Stack Tags -->
            <div v-if="article.tech_stack" class="flex flex-wrap gap-2 mb-6">
              <span
                v-for="(tech, techIndex) in parseTechStack(article.tech_stack).slice(0, 3)"
                :key="tech"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-300 hover:scale-105"
                :class="getTechStackColor(techIndex)"
              >
                {{ tech }}
              </span>
              <span
                v-if="parseTechStack(article.tech_stack).length > 3"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full"
              >
                +{{ parseTechStack(article.tech_stack).length - 3 }}
              </span>
            </div>

            <!-- Action Button -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
              <div class="flex items-center space-x-2 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm">{{ getReadingTime(article.content) }} min read</span>
              </div>

              <a
                :href="`/articles/${article.slug}`"
                class="group/btn inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
              >
                <span class="mr-2">Baca Artikel</span>
                <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
              </a>
            </div>
          </div>

          <!-- Decorative Corner -->
          <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-blue-500/10 to-transparent"></div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 011 1l4 4v9a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-600 mb-3">Belum Ada Artikel</h3>
        <p class="text-gray-500 text-lg">Artikel terbaru akan segera hadir untuk Anda</p>
      </div>

      <!-- Call to Action Button -->
      <div v-if="articles && articles.length > 0" class="flex justify-center mt-16">
        <a
          href="/articles"
          class="group inline-flex items-center px-10 py-4 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white font-bold text-lg rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
        >
          <span class="mr-3">Jelajahi Semua Artikel</span>
          <div class="flex items-center justify-center w-8 h-8 bg-white/20 rounded-full group-hover:bg-white/30 transition-colors duration-300">
            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </div>
        </a>
      </div>
    </div>

    <!-- Enhanced Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <!-- Floating Shapes -->
      <div class="absolute top-20 left-10 w-64 h-64 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full blur-3xl animate-float"></div>
      <div class="absolute bottom-20 right-10 w-80 h-80 bg-gradient-to-br from-purple-200/30 to-pink-200/30 rounded-full blur-3xl animate-float-delayed"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-blue-100/20 to-purple-100/20 rounded-full blur-3xl animate-pulse"></div>

      <!-- Grid Pattern -->
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    </div>
  </section>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  articles: {
    type: Array,
    default: () => []
  }
})

// Helper function untuk parsing tech stack
const parseTechStack = (techStack) => {
  if (!techStack) return []

  if (Array.isArray(techStack)) {
    return techStack
  }

  try {
    return JSON.parse(techStack)
  } catch (e) {
    return techStack.split(',').map(tech => tech.trim()).filter(tech => tech)
  }
}

// Helper function untuk format tanggal
const formatDate = (dateString) => {
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    timeZone: 'Asia/Jakarta'
  }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}

// Helper function untuk reading time
const getReadingTime = (content) => {
  if (!content) return 3
  const wordsPerMinute = 200
  const wordCount = content.split(' ').length
  return Math.ceil(wordCount / wordsPerMinute) || 3
}

// Helper function untuk tech stack colors
const getTechStackColor = (index) => {
  const colors = [
    'bg-blue-100 text-blue-700 hover:bg-blue-200',
    'bg-green-100 text-green-700 hover:bg-green-200',
    'bg-purple-100 text-purple-700 hover:bg-purple-200',
    'bg-pink-100 text-pink-700 hover:bg-pink-200',
    'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
  ]
  return colors[index % colors.length]
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animations */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

@keyframes float-delayed {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-30px) rotate(-180deg);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out forwards;
  opacity: 0;
}

.animate-float {
  animation: float 20s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float-delayed 25s ease-in-out infinite;
  animation-delay: -5s;
}

/* Grid Pattern */
.bg-grid-pattern {
  background-image:
    linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px);
  background-size: 50px 50px;
}

/* Enhanced Hover Effects */
.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Smooth Transitions */
* {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Backdrop Blur Support */
@supports (backdrop-filter: blur(10px)) {
  .backdrop-blur-sm {
    backdrop-filter: blur(4px);
  }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #2563eb, #7c3aed);
}
</style>
