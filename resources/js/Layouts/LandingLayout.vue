<!-- resources/js/Layouts/LandingLayout.vue -->
<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
      <Footer />
    </footer>
  </div>
</template>

<script setup>
import Footer from '@/Components/LandingPage/Footer.vue'

</script>
