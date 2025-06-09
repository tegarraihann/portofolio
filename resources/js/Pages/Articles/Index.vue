<template>
  <LandingLayout>
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
      <!-- Hero Section -->
      <div class="relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5"></div>
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
          <!-- Navigation -->
          <div class="flex items-center justify-between mb-8 sm:mb-12">
            <Link href="/"
              class="group inline-flex items-center text-slate-600 hover:text-blue-600 font-medium text-sm sm:text-base transition-all duration-200 hover:scale-105">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Kembali ke Beranda
            </Link>
          </div>

          <!-- Header -->
          <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-6">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
              </svg>
              Artikel Terbaru
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-4 sm:mb-6">
              Temukan Inspirasi
              <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                Melalui Artikel
              </span>
            </h1>

            <p class="text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
              Jelajahi koleksi artikel menarik yang dikurasi dengan cermat untuk memberikan wawasan dan inspirasi bagi perjalanan Anda
            </p>
          </div>
        </div>
      </div>

      <!-- Articles Section -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-20">
        <!-- Articles Grid -->
        <div v-if="articles.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-12 sm:mb-16">
          <article v-for="post in articles.data" :key="post.id"
            class="group bg-white rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-slate-100 hover:border-blue-200 hover:-translate-y-2">

            <!-- Thumbnail -->
            <div class="relative overflow-hidden">
              <div class="aspect-w-16 aspect-h-9">
                <img v-if="post.thumbnail_path"
                  :src="`/storage/${post.thumbnail_path}`"
                  :alt="post.title"
                  class="w-full h-48 sm:h-52 object-cover group-hover:scale-110 transition-transform duration-700" />
                <div v-else class="w-full h-48 sm:h-52 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                  <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>

              <!-- Category Badge -->
              <div v-if="post.category" class="absolute top-3 sm:top-4 left-3 sm:left-4">
                <span class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 bg-white/95 backdrop-blur-sm text-blue-600 text-xs sm:text-sm font-semibold rounded-full shadow-sm">
                  {{ post.category.name }}
                </span>
              </div>

              <!-- Reading time estimate -->
              <div class="absolute top-3 sm:top-4 right-3 sm:right-4">
                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 bg-black/20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                  </svg>
                  5 min
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-5 sm:p-6 lg:p-7">
              <!-- Title -->
              <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 mb-3 sm:mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors duration-300">
                <Link :href="route('articles.show', post.slug)"
                  class="hover:text-blue-600 transition-colors">
                  {{ post.title }}
                </Link>
              </h2>

              <!-- Excerpt -->
              <p class="text-slate-600 mb-4 sm:mb-5 line-clamp-3 text-sm sm:text-base leading-relaxed">
                {{ post.excerpt }}
              </p>

              <!-- Tags -->
              <div v-if="post.tags && post.tags.length" class="flex flex-wrap gap-2 mb-5 sm:mb-6">
                <span v-for="tag in post.tags.slice(0, 3)" :key="tag.id"
                  class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-700 text-xs font-medium rounded-full transition-colors cursor-pointer">
                  #{{ tag.name }}
                </span>
                <span v-if="post.tags.length > 3"
                  class="inline-flex items-center px-2.5 py-1 bg-gradient-to-r from-blue-100 to-purple-100 text-blue-700 text-xs font-medium rounded-full">
                  +{{ post.tags.length - 3 }} lainnya
                </span>
              </div>

              <!-- Footer -->
              <div class="flex items-center justify-between pt-4 sm:pt-5 border-t border-slate-100">
                <div class="flex items-center text-xs sm:text-sm text-slate-500">
                  <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ new Date(post.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                  }) }}
                </div>

                <Link :href="route('articles.show', post.slug)"
                  class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm group-hover:translate-x-1 transition-all duration-300 hover:scale-105">
                  Baca Artikel
                  <svg class="w-4 h-4 ml-1.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </article>
        </div>

        <!-- Empty State -->
        <div v-if="!articles.data.length" class="text-center py-16 sm:py-20">
          <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 text-slate-400 mb-6">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3">Belum Ada Artikel</h3>
          <p class="text-slate-500 text-sm sm:text-base max-w-md mx-auto leading-relaxed">
            Artikel menarik akan segera hadir di sini. Pantau terus untuk mendapatkan konten terbaru dari kami.
          </p>
          <div class="mt-8">
            <Link href="/"
              class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
              <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Kembali ke Beranda
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="articles.links && articles.data.length" class="mt-12 sm:mt-16">
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6">
            <Pagination :links="articles.links" />
          </div>
        </div>
      </div>
    </section>
  </LandingLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import LandingLayout from '@/Layouts/LandingLayout.vue';
import Pagination from '@/Components/Pagination.vue';

// Receive props dari controller
defineProps({
  articles: Object
});
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

.aspect-w-16 {
  position: relative;
  width: 100%;
}

.aspect-w-16::before {
  content: '';
  display: block;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-h-9 > * {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Custom scrollbar untuk mobile */
@media (max-width: 768px) {
  .overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
  }

  .overflow-x-auto::-webkit-scrollbar {
    height: 4px;
  }

  .overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
  }

  .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
}

/* Hover animations */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

.group:hover .group-hover\:-translate-y-2 {
  transform: translateY(-0.5rem);
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Loading animation untuk images */
img {
  transition: opacity 0.3s ease-in-out;
}

img[loading="lazy"] {
  opacity: 0;
}

img[loading="lazy"].loaded {
  opacity: 1;
}

/* Focus states untuk accessibility */
a:focus,
button:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }

  .print-block {
    display: block !important;
  }
}
</style>
