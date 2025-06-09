<template>
  <nav v-if="links.length" class="flex justify-center mt-6">
    <ul class="inline-flex items-center -space-x-px">
      <li v-for="(link, index) in links" :key="index">
        <!-- Disabled link (no url) -->
        <span
          v-if="!link.url"
          v-html="link.label"
          class="px-3 py-1 mx-1 text-gray-400 bg-white border border-gray-200 cursor-default rounded"
        ></span>
        <!-- Active or normal link -->
        <Link
          v-else
          :href="link.url"
          v-html="link.label"
          class="px-3 py-1 mx-1 text-sm font-medium border rounded hover:bg-gray-100 transition-colors duration-150"
          :class="link.active
            ? 'text-white bg-indigo-600 border-indigo-600'
            : 'text-gray-600 bg-white border-gray-200'"
        ></Link>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: {
    type: Array,
    required: true,
  },
})
</script>
