<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const isVisible = ref(false)
const props = defineProps({
  cvUrl: {
    type: String,
    default: '/cv.pdf'
  }
})

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true
  }, 100)
})

const scrollToAbout = () => {
  document.getElementById('about').scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  })
}
</script>

<template>
  <section id="hero" class="hero-section relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-purple-800"></div>
    <div class="absolute inset-0 bg-black/20"></div>

    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
      <div class="absolute top-20 left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-20 w-96 h-96 bg-yellow-400/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 container mx-auto px-6 text-center">
      <div
        :class="['transition-all duration-1000 transform', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0']"
      >
        <!-- Greeting -->
        <div class="mb-6">
          <span class="inline-block text-2xl mb-4 animate-bounce">👋</span>
          <p class="text-xl text-white/90 font-medium">Halo, saya</p>
        </div>

        <!-- Main Title -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
          <span class="bg-gradient-to-r from-white to-yellow-200 bg-clip-text text-transparent">
            Tegar Raihan Akmali
          </span>
        </h1>

        <!-- Subtitle with Typing Effect -->
        <div class="text-xl md:text-2xl text-white/90 mb-8 h-8">
          <span class="border-r-2 border-yellow-400 animate-pulse">
            Fullstack Developer 
          </span>
        </div>

        <!-- Description -->
        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
          Passionate about creating modern, user-friendly web applications using cutting-edge technologies like Vue.js, Laravel, and Tailwind CSS.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <button
            @click="scrollToAbout"
            class="group px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-semibold rounded-full hover:from-yellow-300 hover:to-yellow-400 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          >
            <span class="flex items-center">
              Lihat Portfolio Saya
              <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
              </svg>
            </span>
          </button>

          <a
            :href="props.cvUrl || '/cv.pdf'"
            target="_blank"
            class="px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-full hover:bg-white/10 hover:border-white/50 transition-all duration-300 backdrop-blur-sm"
          >
            Download CV
          </a>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60">
        <div class="flex flex-col items-center animate-bounce">
          <span class="text-sm mb-2">Scroll Down</span>
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
          </svg>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  position: relative;
}

/* Custom animations */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Glassmorphism effect */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}
</style>
