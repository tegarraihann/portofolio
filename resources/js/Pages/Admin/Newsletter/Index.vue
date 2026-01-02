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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscribed</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="subscriber in subscribers.data" :key="subscriber.id">
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
                            <form @submit.prevent="send" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                                    <input
                                        v-model="form.subject"
                                        type="text"
                                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Update terbaru"
                                    >
                                    <p v-if="form.errors.subject" class="text-sm text-red-600 mt-1">{{ form.errors.subject }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                                    <textarea
                                        v-model="form.content"
                                        rows="6"
                                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Tulis pesan newsletter..."
                                    ></textarea>
                                    <p v-if="form.errors.content" class="text-sm text-red-600 mt-1">{{ form.errors.content }}</p>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Mengirim...' : 'Kirim ke Subscriber Aktif' }}
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-3">
                                Newsletter akan dikirim ke semua subscriber dengan status active.
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
})

const search = ref(props.filters?.q || '')
const status = ref(props.filters?.status || '')

const form = useForm({
    subject: '',
    content: '',
})

const page = usePage()
const statusMessage = computed(() => page.props.flash?.status || '')

const applyFilter = () => {
    router.get(
        route('admin.newsletter.index'),
        { q: search.value, status: status.value },
        { preserveState: true, preserveScroll: true }
    )
}

const send = () => {
    form.post(route('admin.newsletter.send'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('subject', 'content')
        },
    })
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
