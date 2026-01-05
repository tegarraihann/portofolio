<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6 space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Newsletter</h1>
                    <p class="text-gray-600 mt-1">Kelola subscriber dan kirim update terbaru.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6 space-y-4">
                        <div v-if="statusMessage" class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                            {{ statusMessage }}
                        </div>

                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Email</label>
                                <input
                                    v-model="search"
                                    @keyup.enter="applyFilter"
                                    type="text"
                                    placeholder="contoh@domain.com"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    v-model="status"
                                    @change="applyFilter"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">Semua</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <button
                                type="button"
                                @click="applyFilter"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                            >
                                Terapkan
                            </button>
                        </div>

                        <div v-if="subscribers.data.length === 0" class="text-sm text-gray-500">
                            Belum ada subscriber sesuai filter.
                        </div>

                        <div v-else class="overflow-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            <input
                                                type="checkbox"
                                                :checked="allSelectedOnPage"
                                                :disabled="activeIdsOnPage.length === 0"
                                                @change="toggleAllOnPage"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50"
                                                title="Pilih semua subscriber aktif di halaman ini"
                                            >
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscribed</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr
                                        v-for="subscriber in subscribers.data"
                                        :key="subscriber.id"
                                        @click="toggleSelection(subscriber)"
                                        :class="[
                                            subscriber.status === 'active' ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed opacity-70',
                                            selectedIds.includes(subscriber.id) ? 'bg-indigo-50' : ''
                                        ]"
                                    >
                                        <td class="px-4 py-4">
                                            <input
                                                v-model="selectedIds"
                                                type="checkbox"
                                                :value="subscriber.id"
                                                :disabled="subscriber.status !== 'active'"
                                                @click.stop
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50"
                                                :title="subscriber.status !== 'active' ? 'Hanya subscriber aktif yang bisa dipilih' : 'Pilih subscriber'"
                                            >
                                        </td>
                                        <td class="px-6 py-4 text-gray-800">{{ subscriber.email }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                                                :class="subscriber.status === 'active'
                                                    ? 'bg-green-50 text-green-700'
                                                    : 'bg-gray-100 text-gray-600'"
                                            >
                                                {{ subscriber.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ subscriber.subscribed_at || '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button
                                                type="button"
                                                @click="remove(subscriber.id)"
                                                @click.stop
                                                class="text-red-600 hover:text-red-900 text-sm"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <Pagination :links="subscribers.links" />
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h2>
                            <div class="space-y-3 text-sm text-gray-700">
                                <div class="flex items-center justify-between">
                                    <span>Total Subscriber</span>
                                    <span class="font-semibold">{{ stats.total }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Active</span>
                                    <span class="font-semibold text-green-600">{{ stats.active }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Inactive</span>
                                    <span class="font-semibold text-gray-600">{{ stats.inactive }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kirim Newsletter</h2>
                            <form @submit.prevent="send('all')" class="space-y-4">
                                <p class="text-sm text-gray-600">
                                    Pilih satu artikel atau project yang ingin dikirim. Email menggunakan template HTML otomatis.
                                </p>
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800 mb-2">Artikel Terbaru</div>
                                        <div v-if="articles.length === 0" class="text-xs text-gray-500">
                                            Belum ada artikel yang dapat dikirim.
                                        </div>
                                        <div v-else class="space-y-2">
                                            <label
                                                v-for="article in articles"
                                                :key="`article-${article.id}`"
                                                class="flex items-start gap-3 rounded-lg border px-3 py-2 cursor-pointer transition"
                                                :class="form.item_type === 'article' && form.item_id === article.id
                                                    ? 'border-indigo-500 bg-indigo-50'
                                                    : 'border-gray-200 hover:border-indigo-300'"
                                            >
                                                <input
                                                    type="radio"
                                                    name="newsletter-item"
                                                    class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                    :checked="form.item_type === 'article' && form.item_id === article.id"
                                                    @change="selectItem('article', article)"
                                                >
                                                <div class="flex items-start gap-3 flex-1">
                                                    <div
                                                        v-if="article.thumbnail_url"
                                                        class="h-10 w-10 rounded-md overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0"
                                                    >
                                                        <img
                                                            :src="article.thumbnail_url"
                                                            :alt="article.title"
                                                            class="h-full w-full object-cover"
                                                        >
                                                    </div>
                                                    <div class="space-y-1">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ article.title }}
                                                        </div>
                                                        <div v-if="article.excerpt" class="text-xs text-gray-600">
                                                            {{ article.excerpt }}
                                                        </div>
                                                        <div v-if="article.date" class="text-xs text-gray-400">
                                                            {{ article.date }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-gray-800 mb-2">Project Terbaru</div>
                                        <div v-if="projects.length === 0" class="text-xs text-gray-500">
                                            Belum ada project yang dapat dikirim.
                                        </div>
                                        <div v-else class="space-y-2">
                                            <label
                                                v-for="project in projects"
                                                :key="`project-${project.id}`"
                                                class="flex items-start gap-3 rounded-lg border px-3 py-2 cursor-pointer transition"
                                                :class="form.item_type === 'project' && form.item_id === project.id
                                                    ? 'border-indigo-500 bg-indigo-50'
                                                    : 'border-gray-200 hover:border-indigo-300'"
                                            >
                                                <input
                                                    type="radio"
                                                    name="newsletter-item"
                                                    class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                    :checked="form.item_type === 'project' && form.item_id === project.id"
                                                    @change="selectItem('project', project)"
                                                >
                                                <div class="flex items-start gap-3 flex-1">
                                                    <div
                                                        v-if="project.thumbnail_url"
                                                        class="h-10 w-10 rounded-md overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0"
                                                    >
                                                        <img
                                                            :src="project.thumbnail_url"
                                                            :alt="project.title"
                                                            class="h-full w-full object-cover"
                                                        >
                                                    </div>
                                                    <div class="space-y-1">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ project.title }}
                                                        </div>
                                                        <div v-if="project.excerpt" class="text-xs text-gray-600">
                                                            {{ project.excerpt }}
                                                        </div>
                                                        <div v-if="project.date" class="text-xs text-gray-400">
                                                            {{ project.date }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <span v-if="hasSelectedItem">
                                        Konten terpilih: <span class="font-semibold text-gray-700">{{ selectedItemTitle }}</span>
                                    </span>
                                    <span v-else>Belum ada konten yang dipilih.</span>
                                </div>
                                <div class="space-y-2">
                                    <button
                                        type="button"
                                        @click="send('selected')"
                                        :disabled="form.processing || selectedCount === 0 || !hasSelectedItem"
                                        class="w-full px-4 py-2 bg-indigo-500 text-white font-semibold rounded-lg shadow hover:bg-indigo-600 transition disabled:opacity-50"
                                    >
                                        {{ form.processing ? 'Mengirim...' : 'Kirim ke Pilihan' }}
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing || !hasSelectedItem"
                                        class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition disabled:opacity-50"
                                    >
                                        {{ form.processing ? 'Mengirim...' : 'Kirim ke Semua Subscriber Aktif' }}
                                    </button>
                                </div>
                            </form>
                            <p class="text-xs text-gray-500 mt-3">
                                Terpilih {{ selectedCount }} subscriber aktif. Gunakan tombol "Kirim ke Pilihan" untuk mengirim sebagian.
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Swal from 'sweetalert2'
import AdminNavbar from '../Components/AdminNavbar.vue'
import AdminSidebar from '../Components/AdminSidebar.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
    subscribers: Object,
    filters: Object,
    stats: Object,
    articles: Array,
    projects: Array,
})

const search = ref(props.filters?.q || '')
const status = ref(props.filters?.status || '')

const form = useForm({
    item_type: '',
    item_id: null,
    scope: 'all',
    subscriber_ids: [],
})

const page = usePage()
const statusMessage = computed(() => page.props.flash?.status || '')
const selectedIds = ref([])

const activeIdsOnPage = computed(() =>
    props.subscribers.data.filter((subscriber) => subscriber.status === 'active').map((subscriber) => subscriber.id)
)

const allSelectedOnPage = computed(() => {
    if (activeIdsOnPage.value.length === 0) return false
    return activeIdsOnPage.value.every((id) => selectedIds.value.includes(id))
})

const selectedCount = computed(() => selectedIds.value.length)
const hasSelectedItem = computed(() => form.item_type !== '' && form.item_id !== null)
const selectedItemTitle = computed(() => {
    if (!hasSelectedItem.value) return ''
    const list = form.item_type === 'article' ? props.articles : props.projects
    const item = list.find((entry) => entry.id === form.item_id)
    return item?.title || ''
})

const applyFilter = () => {
    router.get(
        route('admin.newsletter.index'),
        { q: search.value, status: status.value },
        { preserveState: true, preserveScroll: true }
    )
}

const send = (scope) => {
    if (!hasSelectedItem.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih konten',
            text: 'Silakan pilih satu artikel atau project sebelum mengirim newsletter.',
        })
        return
    }
    form.scope = scope
    form.subscriber_ids = scope === 'selected' ? [...selectedIds.value] : []
    form.post(route('admin.newsletter.send'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('scope', 'subscriber_ids')
            selectedIds.value = []
        },
    })
}

const selectItem = (type, item) => {
    form.item_type = type
    form.item_id = item.id
}

const toggleAllOnPage = () => {
    if (allSelectedOnPage.value) {
        selectedIds.value = selectedIds.value.filter((id) => !activeIdsOnPage.value.includes(id))
        return
    }

    const next = new Set(selectedIds.value)
    activeIdsOnPage.value.forEach((id) => next.add(id))
    selectedIds.value = Array.from(next)
}

const toggleSelection = (subscriber) => {
    if (subscriber.status !== 'active') return

    const id = subscriber.id
    if (selectedIds.value.includes(id)) {
        selectedIds.value = selectedIds.value.filter((selectedId) => selectedId !== id)
        return
    }

    selectedIds.value = [...selectedIds.value, id]
}

const remove = (id) => {
    Swal.fire({
        title: 'Hapus subscriber?',
        text: 'Email akan dihapus dari daftar newsletter.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (!result.isConfirmed) return
        router.delete(route('admin.newsletter.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Subscriber dihapus',
                    showConfirmButton: false,
                    timer: 1500,
                })
            },
            onError: () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak bisa menghapus subscriber.',
                })
            },
        })
    })
}
</script>
