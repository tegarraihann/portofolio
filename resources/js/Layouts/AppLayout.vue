<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
      <header class="p-4 shadow-md bg-white dark:bg-gray-800">
        <div class="container mx-auto flex justify-between items-center">
          <div class="text-lg font-bold">Nama Kamu</div>
          <nav class="flex space-x-4">
            <Link href="/">Home</Link>
            <Link href="/projects">Projects</Link>
            <Link href="/blog">Blog</Link>
            <Link href="/contact">Contact</Link>
          </nav>
        </div>
      </header>

      <main class="flex-1 container mx-auto p-4">
        <slot />
      </main>

      <footer class="p-4 text-center text-sm bg-white dark:bg-gray-800">
        © 2025 Nama Kamu. All rights reserved.
      </footer>
    </div>
  </template>
