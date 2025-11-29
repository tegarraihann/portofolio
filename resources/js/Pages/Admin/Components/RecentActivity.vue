<template>
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
      <Link
        v-if="viewAllLink"
        :href="viewAllLink"
        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
      >
        Lihat Semua
      </Link>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="n in 3" :key="n" class="animate-pulse">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
          <div class="flex-1">
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="items.length === 0" class="text-center py-8">
      <div class="text-gray-400 mb-2">
        <component :is="emptyStateIcon" class="w-12 h-12 mx-auto" />
      </div>
      <p class="text-gray-500">{{ emptyStateMessage }}</p>
    </div>

    <div v-else class="space-y-4">
      <div
        v-for="item in items"
        :key="item.id"
        class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200"
      >
        <!-- Icon or Image -->
        <div class="flex-shrink-0">
          <div
            v-if="item.image"
            class="w-10 h-10 rounded-full overflow-hidden"
          >
            <img
              :src="item.image"
              :alt="item.title"
              class="w-full h-full object-cover"
            >
          </div>
          <div
            v-else
            class="w-10 h-10 rounded-full flex items-center justify-center"
            :class="getStatusColor(item.status)"
          >
            <component :is="getStatusIcon(item.status)" class="w-5 h-5" />
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h4 class="text-sm font-medium text-gray-900 truncate">
                {{ item.title }}
              </h4>
              <p v-if="item.description" class="text-sm text-gray-600 mt-1">
                {{ item.description }}
              </p>
              <div class="flex items-center mt-2 space-x-4">
                <span class="text-xs text-gray-500">
                  {{ formatDate(item.created_at) }}
                </span>
                <span
                  v-if="item.status"
                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                  :class="getStatusBadgeColor(item.status)"
                >
                  {{ getStatusText(item.status) }}
                </span>
                <span
                  v-if="item.category"
                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                >
                  {{ item.category }}
                </span>
              </div>
            </div>

            <!-- Action Button -->
            <div v-if="item.action" class="ml-4">
              <Link
                :href="item.action.url"
                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
              >
                {{ item.action.text }}
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Load More Button -->
    <div v-if="showLoadMore && items.length > 0" class="mt-4 text-center">
      <button
        @click="$emit('loadMore')"
        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
        :disabled="loadingMore"
      >
        {{ loadingMore ? 'Loading...' : 'Load More' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import {
  CheckIcon,
  ClockIcon,
  EnvelopeIcon,
  DocumentTextIcon,
  InboxIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  items: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  viewAllLink: {
    type: String,
    default: null
  },
  emptyStateMessage: {
    type: String,
    default: 'Belum ada data'
  },
  showLoadMore: {
    type: Boolean,
    default: false
  },
  loadingMore: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['loadMore'])

// Status mappings
const statusConfig = {
  published: {
    icon: 'check',
    color: 'bg-green-100 text-green-600',
    badge: 'bg-green-100 text-green-800',
    text: 'Published'
  },
  draft: {
    icon: 'clock',
    color: 'bg-yellow-100 text-yellow-600',
    badge: 'bg-yellow-100 text-yellow-800',
    text: 'Draft'
  },
  pending: {
    icon: 'clock',
    color: 'bg-orange-100 text-orange-600',
    badge: 'bg-orange-100 text-orange-800',
    text: 'Pending'
  },
  unread: {
    icon: 'envelope',
    color: 'bg-blue-100 text-blue-600',
    badge: 'bg-blue-100 text-blue-800',
    text: 'Unread'
  },
  completed: {
    icon: 'check',
    color: 'bg-green-100 text-green-600',
    badge: 'bg-green-100 text-green-800',
    text: 'Completed'
  }
}

// Icon components
const iconComponents = {
  check: CheckIcon,
  clock: ClockIcon,
  envelope: EnvelopeIcon,
  document: DocumentTextIcon
}

const emptyStateIcon = InboxIcon

// Helper functions
const getStatusColor = (status) => {
  return statusConfig[status]?.color || 'bg-gray-100 text-gray-600'
}

const getStatusIcon = (status) => {
  const iconName = statusConfig[status]?.icon || 'document'
  return iconComponents[iconName]
}

const getStatusBadgeColor = (status) => {
  return statusConfig[status]?.badge || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  return statusConfig[status]?.text || status
}

const formatDate = (dateString) => {
  if (!dateString) return ''

  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now - date)
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

  if (diffDays === 1) return 'Kemarin'
  if (diffDays < 7) return `${diffDays} hari lalu`
  if (diffDays < 30) return `${Math.ceil(diffDays / 7)} minggu lalu`

  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}
</script>
