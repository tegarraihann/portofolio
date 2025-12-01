<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Media Library</h1>
                        <p class="text-gray-600 mt-1">Kelola file yang digunakan untuk artikel dan projects.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6 space-y-6">
                        <div v-if="statusMessage" class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                            {{ statusMessage }}
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input v-model="search" @keyup.enter="applyFilter" type="text" placeholder="Nama file atau mime type"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                                <select v-model="type" @change="applyFilter"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Semua</option>
                                    <option value="image">Image</option>
                                    <option value="document">Document</option>
                                    <option value="video">Video</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload</label>
                                <input type="file" @change="onFileChange" />
                            </div>
                            <button
                                type="button"
                                :disabled="form.processing || !form.file"
                                @click="submit"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition disabled:opacity-50">
                                Upload
                            </button>
                        </div>

                        <div v-if="media.data.length === 0" class="text-gray-500 text-sm mt-6">
                            Belum ada file yang cocok dengan filter.
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                            <div v-for="item in media.data" :key="item.id" class="bg-white border rounded-lg shadow-sm overflow-hidden flex flex-col">
                                <div class="h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
                                    <img v-if="item.type === 'image'" :src="item.url" alt="" class="w-full h-full object-cover" />
                                    <div v-else class="text-gray-500 text-sm p-4 text-center break-words">
                                        {{ item.original_name }}
                                    </div>
                                </div>
                                <div class="p-3 space-y-1 text-sm text-gray-700 flex-1">
                                    <div class="font-semibold truncate" :title="item.original_name">{{ item.original_name }}</div>
                                    <div class="text-xs text-gray-500">{{ item.mime_type }}</div>
                                    <div class="text-xs text-gray-500">{{ formatSize(item.size) }}</div>
                                </div>
                                <div class="p-3 border-t flex items-center justify-between space-x-2">
                                    <button @click="copyLink(item.url)" class="text-indigo-600 text-sm hover:underline">Copy URL</button>
                                    <button @click="remove(item.id)" class="text-red-600 text-sm hover:underline">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-between items-center text-sm text-gray-600" v-if="media.data.length">
                            <span>Menampilkan {{ media.from }} - {{ media.to }} dari {{ media.total }}</span>
                            <div class="space-x-2">
                                <Link
                                    v-if="media.prev_page_url"
                                    :href="media.prev_page_url"
                                    class="px-3 py-1 border rounded hover:bg-gray-100"
                                    preserve-scroll
                                >
                                    Prev
                                </Link>
                                <Link
                                    v-if="media.next_page_url"
                                    :href="media.next_page_url"
                                    class="px-3 py-1 border rounded hover:bg-gray-100"
                                    preserve-scroll
                                >
                                    Next
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6 space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900">Tips</h3>
                        <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                            <li>Upload gambar maksimal 10MB.</li>
                            <li>Gunakan format JPG/PNG/WebP untuk gambar.</li>
                            <li>Copy URL untuk dipakai di artikel atau projects.</li>
                            <li>Pastikan symlink storage aktif untuk akses /storage/...</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Swal from 'sweetalert2'
import AdminNavbar from '../Components/AdminNavbar.vue'
import AdminSidebar from '../Components/AdminSidebar.vue'

const props = defineProps({
    media: Object,
    filters: Object,
})

const form = useForm({
    file: null,
})

const search = ref(props.filters?.q || '')
const type = ref(props.filters?.type || '')

const page = usePage()
const statusMessage = computed(() => page.props.flash?.status || '')

const onFileChange = (event) => {
    form.file = event.target.files[0]
}

const submit = () => {
    if (!form.file) return
    form.post(route('admin.media.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset('file')
        },
    })
}

const applyFilter = () => {
    router.get(route('admin.media.index'), { q: search.value, type: type.value }, { preserveScroll: true, preserveState: true })
}

const copyLink = async (url) => {
    if (!navigator?.clipboard) return
    try {
        await navigator.clipboard.writeText(url)
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'URL tersalin',
            showConfirmButton: false,
            timer: 1200,
        })
    } catch (e) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal menyalin URL',
        })
    }
}

const remove = (id) => {
    Swal.fire({
        title: 'Hapus file?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (!result.isConfirmed) return
        router.delete(route('admin.media.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'File dihapus',
                    showConfirmButton: false,
                    timer: 1500,
                })
            },
            onError: () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menghapus file',
                })
            },
        })
    })
}

const formatSize = (bytes) => {
    if (!bytes) return '0 B'
    const units = ['B', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${units[i]}`
}
</script>
