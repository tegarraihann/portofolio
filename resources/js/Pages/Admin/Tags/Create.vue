<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />
      <main class="flex-1 p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Tambah Tag</h2>
        <form @submit.prevent="submit" class="bg-white p-6 shadow rounded-lg space-y-4">
          <div>
            <label class="block text-gray-700">Nama</label>
            <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded" />
            <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
          </div>
          <div class="flex justify-end">
            <Link href="/admin/tags" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 mr-2">Batal</Link>
            <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan</button>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminSidebar from '../Components/AdminSidebar.vue';
import AdminNavbar from '../Components/AdminNavbar.vue';
const form = useForm({ name: '' });
function submit() { form.post('/admin/tags'); }
</script>
