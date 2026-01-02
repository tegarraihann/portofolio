<template>
  <footer class="bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
      <div class="absolute top-20 left-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 py-16 relative z-10">
      <!-- Main Footer Content -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
        <!-- Brand Section -->
        <div class="md:col-span-2">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center font-bold text-xl mr-4">
              T
            </div>
            <h3 class="text-2xl font-bold">Tegar Raihan Akmali</h3>
          </div>
          <p class="text-gray-300 mb-6 max-w-md leading-relaxed">
            Passionate Fullstack Developer yang fokus pada pengembangan aplikasi web modern
            dengan teknologi terdepan. Mari berkolaborasi untuk mewujudkan ide digital Anda!
          </p>

          <!-- Social Links -->
          <div class="flex space-x-4">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.url"
              target="_blank"
              :class="[
                'w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110',
                social.bgColor, social.hoverColor
              ]"
              :title="social.name"
            >
              <i :class="social.icon" class="text-sm"></i>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-lg font-semibold mb-6">Quick Links</h4>
          <ul class="space-y-3">
            <li v-for="link in quickLinks" :key="link.name">
              <a
                :href="link.href"
                @click="link.href.startsWith('#') ? scrollToSection(link.href.substring(1)) : null"
                class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center group"
              >
                <span class="group-hover:translate-x-1 transition-transform duration-200">{{ link.name }}</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h4 class="text-lg font-semibold mb-6">Kontak</h4>
          <ul class="space-y-4">
            <li class="flex items-center text-gray-300">
              <svg class="w-5 h-5 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              <a href="mailto:tegar@example.com" class="hover:text-white transition-colors">
                tegarraihanakmali@gmail.com
              </a>
            </li>
            <li class="flex items-center text-gray-300">
              <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              <a href="tel:+6281234567890" class="hover:text-white transition-colors">
                +62 852-2620-4424
              </a>
            </li>
            <li class="flex items-center text-gray-300">
              <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>Pekanbaru, Indonesia</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Newsletter Section -->
      <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 mb-12">
        <div class="text-center max-w-2xl mx-auto">
          <h4 class="text-2xl font-bold mb-4">Stay Updated</h4>
          <p class="text-gray-300 mb-6">
            Dapatkan update terbaru tentang project dan artikel tech dari saya
          </p>
          <form @submit.prevent="handleNewsletterSubmit" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input
              v-model="email"
              type="email"
              placeholder="Masukkan email Anda"
              class="flex-1 px-4 py-3 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
              required
            >
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubmitting ? 'Sending...' : 'Subscribe' }}
            </button>
          </form>
          <p v-if="successMessage" class="mt-4 text-sm text-emerald-200">
            {{ successMessage }}
          </p>
          <p v-if="errorMessage" class="mt-4 text-sm text-red-200">
            {{ errorMessage }}
          </p>
        </div>
      </div>

      <!-- Bottom Footer -->
      <div class="border-t border-white/10 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="text-gray-300 mb-4 md:mb-0">
            <p>&copy; {{ currentYear }} Tegar Raihan Akmali. All rights reserved.</p>
          </div>

          <!-- Footer Links -->
          <div class="flex space-x-6">
            <a href="/privacy" class="text-gray-300 hover:text-white transition-colors text-sm">
              Privacy Policy
            </a>
            <a href="/terms" class="text-gray-300 hover:text-white transition-colors text-sm">
              Terms of Service
            </a>
            <a href="/sitemap" class="text-gray-300 hover:text-white transition-colors text-sm">
              Sitemap
            </a>
          </div>
        </div>

        <!-- Tech Stack -->
        <div class="mt-6 pt-6 border-t border-white/10 text-center">
          <p class="text-gray-400 text-sm mb-3">Built with ❤️ using</p>
          <div class="flex flex-wrap justify-center gap-4">
            <span v-for="tech in techStack" :key="tech" class="px-3 py-1 bg-white/10 rounded-full text-xs text-gray-300">
              {{ tech }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <button
      @click="scrollToTop"
      class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 z-50"
      title="Back to Top"
    >
      <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
      </svg>
    </button>
  </footer>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const email = ref('')
const isSubmitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const currentYear = computed(() => new Date().getFullYear())

const socialLinks = [
  {
    name: 'GitHub',
    url: 'https://github.com/tegarraihann',
    icon: 'fa-brands fa-github',
    bgColor: 'bg-gray-700/50',
    hoverColor: 'hover:bg-gray-700'
  },
  {
    name: 'LinkedIn',
    url: 'https://linkedin.com/in/tegarraihann',
    icon: 'fa-brands fa-linkedin-in',
    bgColor: 'bg-blue-600/50',
    hoverColor: 'hover:bg-blue-600'
  },
  {
    name: 'Instagram',
    url: 'https://instagram.com/tegarraihann.a',
    icon: 'fa-brands fa-instagram',
    bgColor: 'bg-pink-600/50',
    hoverColor: 'hover:bg-pink-600'
  },
  {
    name: 'Twitter',
    url: 'https://twitter.com/',
    icon: 'fa-brands fa-x-twitter',
    bgColor: 'bg-blue-400/50',
    hoverColor: 'hover:bg-blue-400'
  }
]

const quickLinks = [
  { name: 'Home', href: '#hero' },
  { name: 'About', href: '#about' },
  { name: 'Projects', href: '#projects' },
  { name: 'Blog', href: '/blogs' },
  { name: 'Contact', href: '/contact' }
]

const techStack = [
  'Laravel', 'Vue.js', 'Inertia.js', 'Tailwind CSS', 'MySQL', 'PHP', 'JavaScript'
]

const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  }
}

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

const handleNewsletterSubmit = async () => {
  if (!email.value) return

  isSubmitting.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const subscribeUrl = typeof route === 'function'
      ? route('newsletter.subscribe')
      : '/newsletter/subscribe'

    const response = await axios.post(subscribeUrl, { email: email.value })
    successMessage.value = response?.data?.message || 'Terima kasih! Anda berhasil subscribe newsletter.'
    email.value = ''
  } catch (error) {
    errorMessage.value = error?.response?.data?.errors?.email?.[0]
      || error?.response?.data?.message
      || 'Terjadi kesalahan. Silakan coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
/* Custom scrollbar for newsletter input */
input::-webkit-scrollbar {
  display: none;
}

/* Backdrop blur support */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}

/* Font Awesome icons - make sure to include Font Awesome in your project */
/* You can add this to your main layout or use CDN */
</style>
