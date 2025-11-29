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
import {
  DocumentTextIcon,
  CodeBracketIcon,
  EyeIcon,
  EnvelopeIcon,
  UsersIcon,
  ChartBarIcon,
  StarIcon,
  ClockIcon
} from '@heroicons/vue/24/outline'

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

// Color mappings (selaras dengan ikon stroke di sidebar)
const colorMap = {
  blue: { iconBg: 'bg-blue-100', iconText: 'text-blue-600' },
  green: { iconBg: 'bg-green-100', iconText: 'text-green-600' },
  purple: { iconBg: 'bg-purple-100', iconText: 'text-purple-600' },
  orange: { iconBg: 'bg-orange-100', iconText: 'text-orange-600' },
  red: { iconBg: 'bg-red-100', iconText: 'text-red-600' },
  indigo: { iconBg: 'bg-indigo-100', iconText: 'text-indigo-600' }
}

// Icon mappings: gunakan SVG stroke (gaya sama seperti sidebar)
const iconMap = {
  document: DocumentTextIcon,
  code: CodeBracketIcon,
  eye: EyeIcon,
  envelope: EnvelopeIcon,
  users: UsersIcon,
  chart: ChartBarIcon,
  star: StarIcon,
  clock: ClockIcon
}

// Computed properties
const iconBgClass = computed(() => colorMap[props.color]?.iconBg || colorMap.blue.iconBg)
const iconClass = computed(() => colorMap[props.color]?.iconText || colorMap.blue.iconText)

const iconComponent = computed(() => iconMap[props.icon] || iconMap.document)

const formattedValue = computed(() => {
  if (typeof props.value === 'number') {
    return props.value.toLocaleString('id-ID')
  }
  return props.value
})
</script>
