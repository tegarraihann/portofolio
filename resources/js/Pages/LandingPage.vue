<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted, ref, defineProps } from 'vue'
import Navbar from '@/Components/LandingPage/Navbar.vue'
import Hero from '@/Components/LandingPage/Hero.vue'
import About from '@/Components/LandingPage/About.vue'
import Projects from '@/Components/LandingPage/Project.vue'
import Footer from '@/Components/LandingPage/Footer.vue'
import LatestArticles from '@/Components/LandingPage/LatestArticles.vue'

const props = defineProps({
  blogs: {
    type: Array,
    default: () => []
  },
  cvUrl: {
    type: String,
    default: null
  },
  projects: {
    type: Array,
    default: () => []
  },
  articles: {
    type: Array,
    default: () => []
  }
})

const isLoading = ref(true)

onMounted(() => {
  console.log('Props received:', {
    articles: props.articles,
    projects: props.projects,
    blogs: props.blogs
  })

  setTimeout(() => {
    isLoading.value = false
  }, 500)
})
</script>

<template>
  <div>
    <Head>
      <title>Tegar Raihan Akmali - Portfolio</title>
      <meta name="description" content="Fullstack Developer & Web Designer dengan expertise dalam Vue.js, Laravel, dan Tailwind CSS">
      <meta property="og:title" content="Tegar Raihan Akmali - Portfolio">
      <meta property="og:description" content="Professional portfolio showcasing modern web development projects">
    </Head>

    <!-- Loading Screen -->
    <div v-if="isLoading" class="fixed inset-0 bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center z-50">
      <div class="text-center text-white">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
        <p class="text-lg">Loading Portfolio...</p>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else class="landing-page">
      <Navbar />
      <main>
        <Hero :cv-url="cvUrl" />
        <About :blogs="blogs" />
        <LatestArticles :articles="articles" />
        <Projects :projects="projects" />
      </main>
      <Footer />
    </div>
  </div>
</template>

<style scoped>
.landing-page {
  scroll-behavior: smooth;
}

/* Smooth scroll for anchor links */
html {
  scroll-behavior: smooth;
}

/* Remove the problematic scroll-snap and fixed heights */
section {
  min-height: 100vh;
  display: flex;
  align-items: center;
}
</style>
