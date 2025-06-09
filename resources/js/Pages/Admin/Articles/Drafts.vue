<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />

      <main class="flex-1 p-6">
        <!-- Header & Tombol -->
        <div class="mb-8 flex items-center justify-between">
          <h2 class="text-2xl font-semibold text-gray-900">Draft Artikel</h2>
          <Link
            href="/admin/articles/create"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors duration-200 flex items-center"
          >
            <PlusIcon class="w-4 h-4 mr-2" />
            Baru
          </Link>
        </div>

        <!-- Tabel Draft Artikel -->
        <div class="overflow-auto bg-white shadow rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="draft in drafts.data" :key="draft.id">
                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ draft.title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ draft.category?.name || '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ new Date(draft.created_at).toLocaleDateString('id-ID') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link
                    :href="route('admin.articles.edit', draft.id)"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >
                    Edit
                  </Link>
                  <Link
                    :href="route('admin.articles.destroy', draft.id)"
                    method="delete"
                    as="button"
                    confirm="Anda yakin ingin menghapus draft ini?"
                    class="text-red-600 hover:text-red-900"
                  >
                    Hapus
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
          <Pagination :links="drafts.links" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
// PERBAIKAN: Gunakan import yang benar untuk Vue 3 + Inertia
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import Pagination from '@/Components/Pagination.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

// Ambil props dengan cara yang benar
const { props } = usePage()
const { drafts } = props

// Atau bisa juga menggunakan defineProps
// const props = defineProps({
//   drafts: Object
// })
</script>
