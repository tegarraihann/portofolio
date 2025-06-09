<template>
  <header
    :class="[
      'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
      isScrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'
    ]"
  >
    <nav class="container mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo/Brand -->
        <div class="flex items-center">
          <Link href="/" class="group flex items-center space-x-2">
            <!-- Logo Icon -->
            <div
              :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center font-bold text-lg transition-all duration-300',
                isScrolled
                  ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white'
                  : 'bg-white/20 backdrop-blur-sm text-white'
              ]"
            >
              T
            </div>
            <!-- Brand Text -->
            <span
              :class="[
                'font-bold text-xl transition-all duration-300 group-hover:scale-105',
                isScrolled ? 'text-gray-800' : 'text-white'
              ]"
            >
              Tegar's Portfolio
            </span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-1">
          <a
            v-for="item in navItems"
            :key="item.name"
            :href="item.href"
            @click="item.href.startsWith('#') ? scrollToSection(item.href.substring(1)) : null"
            :class="[
              'px-4 py-2 rounded-full font-medium transition-all duration-300 hover:scale-105',
              isScrolled
                ? 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'
                : 'text-white/90 hover:bg-white/20 hover:text-white'
            ]"
          >
            {{ item.name }}
          </a>

          <!-- CTA Button -->
          <a
            href="/contact"
            :class="[
              'ml-4 px-6 py-2 rounded-full font-semibold transition-all duration-300 transform hover:scale-105',
              isScrolled
                ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 shadow-lg'
                : 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
            ]"
          >
            Hubungi Saya
          </a>
        </div>

        <!-- Mobile Hamburger -->
        <button
          @click="toggleMenu"
          :class="[
            'md:hidden p-2 rounded-lg transition-all duration-300',
            isScrolled
              ? 'text-gray-800 hover:bg-gray-100'
              : 'text-white hover:bg-white/20'
          ]"
        >
          <svg
            :class="['w-6 h-6 transition-transform duration-300', isMenuOpen ? 'rotate-45' : '']"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              v-if="!isMenuOpen"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
            <path
              v-else
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </nav>

    <!-- Mobile Menu -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div
        v-show="isMenuOpen"
        class="md:hidden bg-white/95 backdrop-blur-md border-t border-gray-200/50"
      >
        <div class="container mx-auto px-6 py-6">
          <div class="flex flex-col space-y-4">
            <a
              v-for="item in navItems"
              :key="item.name"
              :href="item.href"
              @click="handleMobileNavClick(item.href)"
              class="text-gray-700 hover:text-blue-600 font-medium py-2 px-4 rounded-lg hover:bg-blue-50 transition-all duration-200"
            >
              {{ item.name }}
            </a>

            <!-- Mobile CTA -->
            <a
              href="/contact"
              @click="toggleMenu"
              class="bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg text-center hover:from-blue-700 hover:to-purple-700 transition-all duration-300 mt-4"
            >
              Hubungi Saya
            </a>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const isMenuOpen = ref(false)
const isScrolled = ref(false)

const navItems = [
  { name: 'Home', href: '#hero' },
  { name: 'About', href: '#about' },
  { name: 'Projects', href: '#projects' },
  { name: 'Article', href: '/articles' }
]

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  }
}

const handleMobileNavClick = (href) => {
  if (href.startsWith('#')) {
    scrollToSection(href.substring(1))
  }
  toggleMenu()
}

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  handleScroll() // Check initial scroll position
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
/* Ensure backdrop blur works */
.backdrop-blur-md {
  backdrop-filter: blur(12px);
}

/* Active link styles */
.router-link-active {
  @apply text-blue-600 bg-blue-50;
}

/* Mobile menu overlay */
@media (max-width: 768px) {
  .mobile-menu-overlay {
    background: rgba(0, 0, 0, 0.5);
  }
}
</style>
