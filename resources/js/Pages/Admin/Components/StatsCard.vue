<template>
  <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex items-center">
      <div
        class="p-3 rounded-full"
        :class="iconBgClass"
      >
        <component :is="iconComponent" class="w-6 h-6" :class="iconClass" />
      </div>
      <div class="ml-4 flex-1">
        <h2 class="text-sm font-medium text-gray-600">{{ title }}</h2>
        <p class="text-2xl font-bold text-gray-900">{{ formattedValue }}</p>
        <div v-if="trend" class="flex items-center mt-1">
          <svg
            v-if="trend.direction === 'up'"
            class="w-4 h-4 text-green-500 mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
          </svg>
          <svg
            v-else-if="trend.direction === 'down'"
            class="w-4 h-4 text-red-500 mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
          </svg>
          <span
            class="text-sm font-medium"
            :class="trend.direction === 'up' ? 'text-green-600' : 'text-red-600'"
          >
            {{ trend.value }}{{ trend.unit }} {{ trend.period }}
          </span>
        </div>
        <p v-if="subtitle" class="text-sm text-gray-500 mt-1">{{ subtitle }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'green', 'purple', 'orange', 'red', 'indigo'].includes(value)
  },
  trend: {
    type: Object,
    default: null
    // Expected format: { direction: 'up|down', value: '12%', unit: '%', period: 'dari bulan lalu' }
  },
  subtitle: {
    type: String,
    default: null
  }
})

// Color mappings
const colorMap = {
  blue: {
    iconBg: 'bg-blue-100',
    iconText: 'text-blue-600'
  },
  green: {
    iconBg: 'bg-green-100',
    iconText: 'text-green-600'
  },
  purple: {
    iconBg: 'bg-purple-100',
    iconText: 'text-purple-600'
  },
  orange: {
    iconBg: 'bg-orange-100',
    iconText: 'text-orange-600'
  },
  red: {
    iconBg: 'bg-red-100',
    iconText: 'text-red-600'
  },
  indigo: {
    iconBg: 'bg-indigo-100',
    iconText: 'text-indigo-600'
  }
}

// Icon mappings
const iconMap = {
  document: () => import('@heroicons/vue/24/outline').then(m => m.DocumentTextIcon),
  code: () => import('@heroicons/vue/24/outline').then(m => m.CodeBracketIcon),
  eye: () => import('@heroicons/vue/24/outline').then(m => m.EyeIcon),
  envelope: () => import('@heroicons/vue/24/outline').then(m => m.EnvelopeIcon),
  users: () => import('@heroicons/vue/24/outline').then(m => m.UsersIcon),
  chart: () => import('@heroicons/vue/24/outline').then(m => m.ChartBarIcon),
  star: () => import('@heroicons/vue/24/outline').then(m => m.StarIcon),
  clock: () => import('@heroicons/vue/24/outline').then(m => m.ClockIcon)
}

// Computed properties
// const iconBgClass = computed(() => colorMap[props.color].iconBg)
// const iconClass = computed(() => colorMap[props.color].iconText)

const iconComponent = computed(() => {
  // Fallback SVG component for common icons
  const svgIcons = {
    document: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`
    },
    code: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>`
    },
    eye: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`
    },
    envelope: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`
    },
    users: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>`
    },
    chart: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>`
    },
    star: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>`
    },
    clock: {
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
    }
  }

  return svgIcons[props.icon] || svgIcons.document
})

const formattedValue = computed(() => {
  if (typeof props.value === 'number') {
    return props.value.toLocaleString('id-ID')
  }
  return props.value
})
</script>
