<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6">
                <!-- Header & Tombol -->
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-900">Semua Artikel</h2>
                    <Link
                        href="/admin/articles/create"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors duration-200 flex items-center"
                    >
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Baru
                    </Link>
                </div>

                <!-- Tabel Artikel -->
                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm table-fixed">
                        <colgroup>
                            <col class="w-1/2" />
                            <col class="w-1/6" />
                            <col class="w-1/6" />
                            <col class="w-1/6" />
                            <col />
                        </colgroup>
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                                    Judul
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                    Kategori
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                    Dibuat
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="article in articles.data" :key="article.id">
                                <td class="px-6 py-4 w-1/2 max-w-0 align-top title-cell">
                                    <span class="block text-gray-800 font-medium">{{ article.title }}</span>
                                    <span class="block text-xs text-gray-500">{{ article.slug }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ article.category?.name || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="
                                            article.status === 'published'
                                                ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'
                                                : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600'
                                        "
                                    >
                                        {{ article.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ new Date(article.created_at).toLocaleDateString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('admin.articles.edit', article.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                        Edit
                                    </Link>
                                    <Link
                                        :href="route('admin.articles.destroy', article.id)"
                                        method="delete"
                                        as="button"
                                        class="text-red-600 hover:text-red-900"
                                        confirm="Anda yakin ingin menghapus?"
                                    >
                                        Hapus
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (jika pakai paginate) -->
                <div class="mt-4">
                    <Pagination :links="articles.links" />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import Pagination from '@/Components/Pagination.vue'
import PlusIcon from 'lucide-vue-next/dist/esm/icons/plus.js'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const articles = props.articles
</script>

<style scoped>
.title-cell {
    white-space: normal;
    word-break: break-word;
    overflow-wrap: anywhere;
}
</style>
