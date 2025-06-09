<template>
  <section id="about" class="about-section py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full">
      <div class="absolute top-20 right-20 w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-30"></div>
      <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-100 rounded-full blur-3xl opacity-30"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
      <!-- Section Title -->
      <div v-motion-pop-visible class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
          Tentang Saya
        </h2>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto rounded-full"></div>
      </div>

      <!-- About Content -->
      <div class="max-w-4xl mx-auto mb-16">
        <div v-motion-pop-visible :delay="200" class="bg-white p-8 md:p-12 rounded-2xl shadow-xl border border-gray-100">
          <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Profile Image -->
            <div class="flex-shrink-0">
              <div class="w-48 h-48 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full p-1">
                <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center">
                  <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- About Text -->
            <div class="flex-1 text-center md:text-left">
              <h3 class="text-2xl font-bold text-gray-800 mb-4">
                Fullstack Developer & Web Designer
              </h3>
              <p class="text-lg text-gray-600 leading-relaxed mb-6">
                Saya adalah seorang Fullstack Developer dan Web Designer yang passionate dalam menciptakan
                aplikasi web modern dan user-friendly. Dengan pengalaman mendalam dalam teknologi seperti
                <span class="font-semibold text-blue-600">Vue.js</span>,
                <span class="font-semibold text-red-600">Laravel</span>, dan
                <span class="font-semibold text-cyan-600">Tailwind CSS</span>,
                saya berkomitmen untuk menghadirkan solusi digital yang inovatif dan efektif.
              </p>

              <!-- Skills -->
              <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                <span v-for="skill in skills" :key="skill"
                  class="px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 text-gray-700 rounded-full text-sm font-medium border border-gray-200">
                  {{ skill }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Blog Section -->
      <div v-if="blogs.length > 0">
        <div v-motion-pop-visible :delay="400" class="text-center mb-12">
          <h3 class="text-3xl font-bold text-gray-800 mb-4">
            Artikel Terbaru
          </h3>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Berbagi pengalaman dan pengetahuan tentang teknologi web development
          </p>
        </div>

        <!-- Blog Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="(blog, index) in blogs.slice(0, 3)"
            :key="blog.id"
            v-motion-pop-visible
            :delay="600 + (index * 100)"
            class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 transform hover:-translate-y-2"
          >
            <!-- Blog Image Placeholder -->
            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 relative overflow-hidden">
              <div class="absolute inset-0 bg-black/20"></div>
              <div class="absolute bottom-4 left-4 right-4">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full">
                  Artikel
                </span>
              </div>
            </div>

            <!-- Blog Content -->
            <div class="p-6">
              <h4 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                {{ blog.title }}
              </h4>
              <p class="text-gray-600 mb-4 line-clamp-3">
                {{ blog.content.substring(0, 120) }}...
              </p>

              <!-- Read More -->
              <Link
                :href="`/blog/${blog.slug}`"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold group-hover:underline transition-all"
                target="_blank"
              >
                Baca Selengkapnya
                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
              </Link>
            </div>
          </div>
        </div>

        <!-- View All Blogs Button -->
        <div class="text-center mt-12">
          <Link
            href="/blogs"
            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
          >
            Lihat Semua Artikel
            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  blogs: {
    type: Array,
    default: () => []
  }
})

const skills = [
  'Vue.js', 'Laravel', 'PHP', 'JavaScript', 'Tailwind CSS',
  'MySQL', 'Git', 'REST API', 'Inertia.js', 'Responsive Design'
]
</script>

<style scoped>
/* Line clamp utilities */
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
</style>
