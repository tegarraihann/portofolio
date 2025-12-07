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
              <Link
                href="/admin/articles/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tulis Artikel
              </Link>
              <Link
                href="/admin/projects/create"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Project
              </Link>
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
            :trend="trendProps(stats.trends.totalArticles)"
          />

          <StatsCard
            title="Total Projects"
            :value="stats.totalProjects"
            icon="code"
            color="green"
            :trend="trendProps(stats.trends.totalProjects)"
          />

          <StatsCard
            title="Visitor Bulan Ini"
            :value="stats.monthlyVisitors"
            icon="eye"
            color="purple"
            :trend="trendProps(stats.trends.monthlyVisitors)"
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
            :trend="trendProps(stats.trends.popularArticles)"
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
            <div class="space-y-4">
              <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                  <span class="inline-block w-3 h-3 rounded-full bg-indigo-500"></span> Total Visits
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                  <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Unique Visitors
                </div>
              </div>
              <div v-if="currentTrend.labels.length" class="h-64 bg-gray-50 rounded-lg p-4">
                <Line :data="chartData" :options="chartOptions" />
              </div>
              <div v-else class="h-64 flex items-center justify-center text-gray-500 bg-gray-50 rounded-lg">
                Belum ada data kunjungan.
              </div>
            </div>
          </div>

          <!-- Popular Articles -->
          <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Artikel Populer</h3>
              <Link href="/admin/articles" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua
              </Link>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-2 no-scrollbar">
              <div
                v-for="article in popularArticles"
                :key="article.id"
                class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition"
              >
                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 break-words mb-2">{{ article.title }}</h4>
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>{{ article.views }} views</span>
                  <span>{{ formatDate(article.created_at) }}</span>
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
import { Link, router } from '@inertiajs/vue3'
import {
  Chart,
  LineElement,
  PointElement,
  LinearScale,
  Title,
  CategoryScale,
  Tooltip,
  Legend,
} from 'chart.js'
import { Line } from 'vue-chartjs'
import AdminSidebar from './Components/AdminSidebar.vue'
import AdminNavbar from './Components/AdminNavbar.vue'
import StatsCard from './Components/StatsCard.vue'
import RecentActivity from './Components/RecentActivity.vue'

Chart.register(LineElement, PointElement, LinearScale, Title, CategoryScale, Tooltip, Legend)

// Reactive data
const chartPeriod = ref('7')

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
  visitTrends: {
    type: Object,
    default: () => ({})
  },
  popularArticles: {
    type: Array,
    default: () => []
  },
  recentArticles: {
    type: Array,
    default: () => []
  },
  recentProjects: {
    type: Array,
    default: () => []
  }
})

const stats = reactive({
  totalArticles: props.stats.totalArticles ?? 0,
  totalProjects: props.stats.totalProjects ?? 0,
  monthlyVisitors: props.stats.monthlyVisitors ?? 0,
  unreadMessages: props.stats.unreadMessages ?? 0,
  newMessagesToday: props.stats.newMessagesToday ?? 0,
  draftArticles: props.stats.draftArticles ?? 0,
  totalCategories: props.stats.totalCategories ?? 0,
  popularArticles: props.stats.popularArticles ?? 0,
  averageViews: props.stats.averageViews ?? 0,
  trends: props.stats.trends ?? {}
})

const currentTrend = computed(() => props.visitTrends?.[chartPeriod.value] ?? { labels: [], total: [], unique: [] })
const chartData = computed(() => ({
  labels: currentTrend.value.labels,
  datasets: [
    {
      label: 'Total Visits',
      data: currentTrend.value.total,
      borderColor: '#6366f1',
      backgroundColor: 'rgba(99, 102, 241, 0.1)',
      tension: 0.3,
      pointRadius: 2,
    },
    {
      label: 'Unique Visitors',
      data: currentTrend.value.unique,
      borderColor: '#22c55e',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      tension: 0.3,
      pointRadius: 2,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
      },
    },
    tooltip: {
      mode: 'index',
      intersect: false,
    },
  },
  interaction: {
    mode: 'nearest',
    intersect: false,
  },
  scales: {
    x: {
      grid: { display: false },
      ticks: { autoSkip: true, maxTicksLimit: 6 },
    },
    y: {
      beginAtZero: true,
      ticks: { precision: 0 },
    },
  },
}

const popularArticles = computed(() => props.popularArticles ?? [])
const recentArticles = computed(() => props.recentArticles ?? [])
const recentProjects = computed(() => props.recentProjects ?? [])

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
  router.visit('/admin/articles')
}

const handleViewAllProjects = () => {
  router.visit('/admin/projects')
}

const handleViewAllMessages = () => {
  router.visit('/admin/messages')
}

const handleCreateArticle = () => {
  router.visit('/admin/articles/create')
}

const handleCreateProject = () => {
  router.visit('/admin/projects/create')
}

const handleManageCategories = () => {
  router.visit('/admin/categories')
}

const handleSettings = () => {
  router.visit('/admin/settings')
}

// Computed properties
const totalContent = computed(() => {
  return stats.totalArticles + stats.totalProjects
})

const engagementRate = computed(() => {
  if (stats.monthlyVisitors === 0) return 0
  return ((stats.averageViews / stats.monthlyVisitors) * 100).toFixed(2)
})

const trendProps = (percent) => {
  const value = Math.abs(percent || 0)
  const direction = percent >= 0 ? 'up' : 'down'
  return {
    direction,
    value: `${value}%`,
    unit: '',
    period: 'dari bulan lalu'
  }
}

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

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Hilangkan override dark untuk menjaga konsistensi tema terang di panel admin */
</style>
