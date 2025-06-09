<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />
      <main class="flex-1 p-6">
        <div class="mb-6 flex items-center justify-between">
          <h2 class="text-2xl font-semibold text-gray-900">Kategori</h2>
          <Link
            href="/admin/categories/create"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
          >Baru</Link>
        </div>
        <div class="bg-white shadow rounded-lg overflow-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="cat in categories.data" :key="cat.id">
                <td class="px-6 py-4 text-gray-800">{{ cat.name }}</td>
                <td class="px-6 py-4 text-right">
                  <Link
                    :href="`/admin/categories/${cat.id}/edit`"
                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                  >Edit</Link>
                  <Link
                    :href="`/admin/categories/${cat.id}`"
                    method="delete"
                    as="button"
                    confirm="Hapus kategori ini?"
                    class="text-red-600 hover:text-red-900"
                  >Hapus</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4">
          <Pagination :links="categories.links" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AdminSidebar from '../Components/AdminSidebar.vue';
import AdminNavbar from '../Components/AdminNavbar.vue';
import Pagination from '@/Components/Pagination.vue';

const { categories } = usePage().props;
</script>
