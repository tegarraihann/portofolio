<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />

      <main class="flex-1 p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Edit Artikel</h2>

        <form @submit.prevent="submit" class="space-y-6 bg-white p-6 shadow rounded-lg">
          <!-- Judul -->
          <div>
            <label class="block text-gray-700">Judul</label>
            <input v-model="form.title" type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500" />
            <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
          </div>

          <!-- Kategori -->
          <div>
            <label class="block text-gray-700">Kategori</label>
            <select v-model="form.category_id"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500">
              <option value="">-- Pilih Kategori --</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <div v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</div>
          </div>

          <!-- Tags -->
          <div>
            <label class="block text-gray-700">Tags</label>
            <select v-model="form.tags" multiple
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500">
              <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
            </select>
            <div v-if="form.errors.tags" class="text-red-600 text-sm mt-1">{{ form.errors.tags }}</div>
          </div>

          <!-- Thumbnail -->
          <div>
            <label class="block text-gray-700">Thumbnail</label>
            <div v-if="form.thumbnail_path" class="mb-2">
              <img :src="`/storage/${form.thumbnail_path}`" alt="Thumbnail"
                class="w-32 h-32 object-cover" />
            </div>
            <input @change="onFileChange" type="file" class="mt-1 block w-full text-gray-700" />
            <div v-if="form.errors.thumbnail" class="text-red-600 text-sm mt-1">{{ form.errors.thumbnail }}</div>
          </div>

          <!-- Ringkasan -->
          <div>
            <label class="block text-gray-700">Ringkasan</label>
            <textarea v-model="form.excerpt" rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500"></textarea>
          </div>

          <!-- Konten -->
          <div>
            <label class="block text-gray-700 mb-1">Konten</label>
            <RichTextEditor v-model="form.content" />
            <div v-if="form.errors.content" class="text-red-600 text-sm mt-1">{{ form.errors.content }}</div>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-gray-700 mb-1">Status</label>
            <div class="flex items-center space-x-4">
              <label class="inline-flex items-center">
                <input type="radio" value="draft" v-model="form.status" class="form-radio" />
                <span class="ml-2">Draft</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" value="published" v-model="form.status" class="form-radio" />
                <span class="ml-2">Published</span>
              </label>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex items-center justify-end space-x-4">
            <Link href="/admin/articles"
              class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
              Batal
            </Link>
            <button :disabled="form.processing" type="submit"
              class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors duration-200">
              Update
            </button>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import RichTextEditor from '@/Components/RichTextEditor.vue'

const { props } = usePage()
const { article, categories, tags } = props

const form = useForm({
  title: article.title,
  category_id: article.category_id || '',
  tags: article.tags.map(t => t.id),
  excerpt: article.excerpt,
  content: article.content,
  status: article.status,
  thumbnail_path: article.thumbnail_path || '', // Untuk menampilkan gambar thumbnail
})

function onFileChange(event) {
  form.thumbnail = event.target.files[0]
}

function submit() {
  const formData = new FormData();

  // Tambahkan semua field form
  formData.append('title', form.title);
  formData.append('category_id', form.category_id);
  formData.append('tags', form.tags.join(',')); // Konversi tags ke format yang sesuai
  formData.append('excerpt', form.excerpt);
  formData.append('content', form.content);
  formData.append('status', form.status);

  // Jika ada thumbnail baru, tambahkan file-nya
  if (form.thumbnail) {
    formData.append('thumbnail', form.thumbnail);
  }

  // Kirim data menggunakan FormData
  form.put(route('admin.articles.update', article.id), {
    data: formData,
    preserveScroll: true,
  })

}
</script>
