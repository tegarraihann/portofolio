<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />

    <div class="flex-1 flex flex-col">
      <AdminNavbar />

      <main class="flex-1 p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
              <p class="text-gray-600 mt-1">Selamat datang kembali! Berikut ringkasan website Anda hari ini.</p>
            </div>
            <div class="flex space-x-3">
              <router-link
                to="/admin/articles/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tulis Artikel
              </router-link>
              <router-link
                to="/admin/projects/create"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Project
              </router-link>
            </div>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatsCard
            title="Total Artikel"
            :value="stats.totalArticles"
            icon="document"
            color="blue"
            :trend="{ direction: 'up', value: '+12%', period: 'dari bulan lalu' }"
          />

          <StatsCard
            title="Total Projects"
            :value="stats.totalProjects"
            icon="code"
            color="green"
            :trend="{ direction: 'up', value: '+8%', period: 'dari bulan lalu' }"
          />

          <StatsCard
            title="Visitor Bulan Ini"
            :value="stats.monthlyVisitors"
            icon="eye"
            color="purple"
            :trend="{ direction: 'up', value: '+15%', period: 'dari bulan lalu' }"
          />

          <StatsCard
            title="Pesan Masuk"
            :value="stats.unreadMessages"
            icon="envelope"
            color="orange"
            :subtitle="`${stats.newMessagesToday} pesan baru hari ini`"
          />
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatsCard
            title="Draft Artikel"
            :value="stats.draftArticles"
            icon="clock"
            color="orange"
          />

          <StatsCard
            title="Total Kategori"
            :value="stats.totalCategories"
            icon="star"
            color="indigo"
          />

          <StatsCard
            title="Artikel Populer"
            :value="stats.popularArticles"
            icon="chart"
            color="green"
          />

          <StatsCard
            title="Rata-rata Views"
            :value="stats.averageViews"
            icon="eye"
            color="blue"
            subtitle="per artikel"
          />
        </div>

        <!-- Charts and Analytics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Visitor Chart -->
          <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Visitor Trends</h3>
              <select v-model="chartPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-1">
                <option value="7">7 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir</option>
                <option value="90">3 Bulan Terakhir</option>
              </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
              <!-- Placeholder for chart -->
              <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500">Chart akan ditampilkan di sini</p>
                <p class="text-sm text-gray-400">Integrasi dengan analytics library</p>
              </div>
            </div>
          </div>

          <!-- Popular Articles -->
          <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Artikel Populer</h3>
              <router-link to="/admin/articles" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua
              </router-link>
            </div>
            <div class="space-y-4">
              <div
                v-for="article in popularArticles"
                :key="article.id"
                class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 cursor-pointer"
              >
                <div class="flex-1">
                  <h4 class="text-sm font-medium text-gray-900 truncate">{{ article.title }}</h4>
                  <div class="flex items-center mt-1 space-x-4">
                    <span class="text-xs text-gray-500">{{ article.views }} views</span>
                    <span class="text-xs text-gray-500">{{ formatDate(article.created_at) }}</span>
                  </div>
                </div>
                <div class="ml-4">
                  <span class="text-sm font-semibold text-indigo-600">{{ article.views }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          <!-- Recent Articles -->
          <RecentActivity
            title="Artikel Terbaru"
            :items="recentArticles"
            type="articles"
            @view-all="handleViewAllArticles"
          />

          <!-- Recent Projects -->
          <RecentActivity
            title="Project Terbaru"
            :items="recentProjects"
            type="projects"
            @view-all="handleViewAllProjects"
          />

          <!-- Recent Messages -->
          <RecentActivity
            title="Pesan Terbaru"
            :items="recentMessages"
            type="messages"
            @view-all="handleViewAllMessages"
          />
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button
              @click="handleCreateArticle"
              class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-indigo-400 hover:bg-indigo-50 transition-colors duration-200"
            >
              <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span class="text-sm font-medium text-gray-700">Artikel Baru</span>
            </button>

            <button
              @click="handleCreateProject"
              class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-green-400 hover:bg-green-50 transition-colors duration-200"
            >
              <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              <span class="text-sm font-medium text-gray-700">Project Baru</span>
            </button>

            <button
              @click="handleManageCategories"
              class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-purple-400 hover:bg-purple-50 transition-colors duration-200"
            >
              <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              <span class="text-sm font-medium text-gray-700">Kategori</span>
            </button>

            <button
              @click="handleSettings"
              class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-orange-400 hover:bg-orange-50 transition-colors duration-200"
            >
              <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span class="text-sm font-medium text-gray-700">Pengaturan</span>
            </button>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import AdminSidebar from './Components/AdminSidebar.vue'
import AdminNavbar from './Components/AdminNavbar.vue'
import StatsCard from './Components/StatsCard.vue'
import RecentActivity from './Components/RecentActivity.vue'

const router = useRouter()

// Reactive data
const chartPeriod = ref('7')

const stats = reactive({
  totalArticles: 145,
  totalProjects: 23,
  monthlyVisitors: 12847,
  unreadMessages: 8,
  newMessagesToday: 3,
  draftArticles: 12,
  totalCategories: 8,
  popularArticles: 5,
  averageViews: 1247
})

const popularArticles = ref([
  {
    id: 1,
    title: 'Cara Mengoptimalkan Performance Website dengan Vue.js',
    views: 2547,
    created_at: '2024-01-15'
  },
  {
    id: 2,
    title: 'Tutorial Lengkap Laravel untuk Pemula',
    views: 1892,
    created_at: '2024-01-14'
  },
  {
    id: 3,
    title: 'Best Practices dalam Development API RESTful',
    views: 1654,
    created_at: '2024-01-13'
  },
  {
    id: 4,
    title: 'Implementasi Authentication dengan JWT',
    views: 1423,
    created_at: '2024-01-12'
  },
  {
    id: 5,
    title: 'Design Pattern yang Wajib Diketahui Developer',
    views: 1287,
    created_at: '2024-01-11'
  }
])

const recentArticles = ref([
  {
    id: 1,
    title: 'Tips Optimasi Database MySQL',
    status: 'published',
    created_at: '2024-01-16',
    author: 'Admin'
  },
  {
    id: 2,
    title: 'Pengenalan Docker untuk Developer',
    status: 'draft',
    created_at: '2024-01-15',
    author: 'Admin'
  },
  {
    id: 3,
    title: 'Microservices Architecture Guide',
    status: 'published',
    created_at: '2024-01-14',
    author: 'Admin'
  }
])

const recentProjects = ref([
  {
    id: 1,
    title: 'E-Commerce Platform',
    status: 'in_progress',
    created_at: '2024-01-15',
    technology: 'Laravel + Vue.js'
  },
  {
    id: 2,
    title: 'Portfolio Website',
    status: 'completed',
    created_at: '2024-01-14',
    technology: 'Next.js'
  },
  {
    id: 3,
    title: 'API Management System',
    status: 'planning',
    created_at: '2024-01-13',
    technology: 'Node.js'
  }
])

const recentMessages = ref([
  {
    id: 1,
    name: 'Ahmad Rizky',
    email: 'ahmad@example.com',
    subject: 'Inquiry tentang project website',
    created_at: '2024-01-16',
    status: 'unread'
  },
  {
    id: 2,
    name: 'Siti Nurhaliza',
    email: 'siti@example.com',
    subject: 'Request untuk kerjasama',
    created_at: '2024-01-15',
    status: 'read'
  },
  {
    id: 3,
    name: 'Budi Santoso',
    email: 'budi@example.com',
    subject: 'Konsultasi development',
    created_at: '2024-01-14',
    status: 'replied'
  }
])

// Methods
const formatDate = (dateString) => {
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    timeZone: 'Asia/Jakarta'
  }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}

const loadDashboardData = async () => {
  try {
    // Simulate API call
    console.log('Loading dashboard data...')
    // In real app, you would make API calls here
    // const response = await dashboardApi.getStats()
    // stats.value = response.data
  } catch (error) {
    console.error('Error loading dashboard data:', error)
  }
}

// Event handlers
const handleViewAllArticles = () => {
  router.push('/admin/articles')
}

const handleViewAllProjects = () => {
  router.push('/admin/projects')
}

const handleViewAllMessages = () => {
  router.push('/admin/messages')
}

const handleCreateArticle = () => {
  router.push('/admin/articles/create')
}

const handleCreateProject = () => {
  router.push('/admin/projects/create')
}

const handleManageCategories = () => {
  router.push('/admin/categories')
}

const handleSettings = () => {
  router.push('/admin/settings')
}

// Computed properties
const totalContent = computed(() => {
  return stats.totalArticles + stats.totalProjects
})

const engagementRate = computed(() => {
  if (stats.monthlyVisitors === 0) return 0
  return ((stats.averageViews / stats.monthlyVisitors) * 100).toFixed(2)
})

// Lifecycle
onMounted(() => {
  loadDashboardData()
})

// Watch for chart period changes
watch(chartPeriod, (newPeriod) => {
  console.log(`Chart period changed to: ${newPeriod} days`)
  // In real app, you would reload chart data here
})
</script>

<style scoped>
/* Custom styles for dashboard */
.dashboard-card {
  @apply bg-white rounded-xl shadow-md p-6;
}

.dashboard-card:hover {
  @apply shadow-lg transform scale-[1.02] transition-all duration-200;
}

/* Animation for stats cards */
.stats-enter-active, .stats-leave-active {
  transition: all 0.3s ease;
}

.stats-enter-from, .stats-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Loading skeleton styles */
.skeleton {
  @apply animate-pulse bg-gray-200 rounded;
}

.skeleton-text {
  @apply h-4 bg-gray-200 rounded w-3/4 mb-2;
}

.skeleton-title {
  @apply h-6 bg-gray-200 rounded w-1/2 mb-4;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .bg-gray-50 {
    @apply bg-gray-900;
  }

  .text-gray-900 {
    @apply text-white;
  }

  .text-gray-600 {
    @apply text-gray-300;
  }

  .bg-white {
    @apply bg-gray-800;
  }
}
</style>
