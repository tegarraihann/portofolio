<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 flex flex-col">
      <AdminNavbar />

      <main class="flex-1 p-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Project</h1>
            <p class="text-sm text-gray-600">Perbarui detail project portfolio Anda.</p>
          </div>
          <Link :href="route('admin.projects.index')" class="text-sm text-indigo-600 hover:text-indigo-800">
            &larr; Kembali
          </Link>
        </div>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
              <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200" required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
              <select v-model="form.category" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200">
                <option value="">Pilih Kategori</option>
                <option value="frontend">Frontend</option>
                <option value="backend">Backend</option>
                <option value="fullstack">Fullstack</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="4" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Live Demo URL</label>
              <input v-model="form.live_demo_url" type="url" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">GitHub URL</label>
              <input v-model="form.github_url" type="url" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <label class="flex items-center space-x-2">
              <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Aktif</span>
            </label>
            <label class="flex items-center space-x-2">
              <input v-model="form.is_featured" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Unggulan</span>
            </label>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Urutan (sort_order)</label>
              <input v-model.number="form.sort_order" type="number" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-200" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tech Stack</label>
            <div class="flex gap-2 mb-2">
              <input v-model="techInput" type="text" placeholder="Tambah teknologi (pisah dengan koma untuk banyak item)" class="flex-1 border rounded px-3 py-2 focus:ring focus:ring-indigo-200" />
              <button type="button" @click="addTech" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tambah</button>
            </div>
            <div class="flex flex-wrap gap-2">
              <span v-for="(tech, idx) in form.tech_stack" :key="idx" class="px-2 py-1 bg-gray-100 rounded text-xs flex items-center gap-1">
                {{ tech }}
                <button type="button" @click="removeTech(idx)" class="text-red-500 hover:text-red-700">×</button>
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
              <input type="file" @change="handleThumbnail" class="block w-full text-sm text-gray-700" />
              <p class="text-xs text-gray-500 mt-1">Opsional: upload untuk mengganti thumbnail.</p>
            </div>
            <div class="flex items-center">
              <div class="w-full h-28 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                <img v-if="project.thumbnail" :src="`/storage/${project.thumbnail}`" alt="Thumbnail" class="w-full h-full object-cover" />
                <span v-else class="text-gray-400 text-sm">Tidak ada thumbnail</span>
              </div>
            </div>
          </div>

          <div class="pt-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700" :disabled="form.processing">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Swal from 'sweetalert2'
import AdminSidebar from '../Components/AdminSidebar.vue'
import AdminNavbar from '../Components/AdminNavbar.vue'

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
})

const techInput = ref('')

const form = useForm({
  title: props.project?.title ?? '',
  description: props.project?.description ?? '',
  category: props.project?.category ?? '',
  tech_stack: props.project?.tech_stack ?? [],
  thumbnail: null,
  live_demo_url: props.project?.live_demo_url ?? '',
  github_url: props.project?.github_url ?? '',
  is_featured: !!props.project?.is_featured,
  is_active: !!props.project?.is_active,
  sort_order: props.project?.sort_order ?? 0,
})

const project = props.project

function addTech() {
  if (!techInput.value.trim()) return
  const techs = techInput.value.split(',').map(t => t.trim()).filter(Boolean)
  form.tech_stack.push(...techs)
  techInput.value = ''
}

function removeTech(index) {
  form.tech_stack.splice(index, 1)
}

function handleThumbnail(event) {
  form.thumbnail = event.target.files[0]
}

function submit() {
  form.transform(data => {
    const payload = { ...data, _method: 'put' }
    if (!data.thumbnail) {
      delete payload.thumbnail
    }
    return payload
  })
  form.post(route('admin.projects.update', project.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Project berhasil diperbarui',
        showConfirmButton: false,
        timer: 1500,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Periksa kembali input Anda.',
      })
    },
    onFinish: () => {
      form.transform(data => {
        const { _method, ...rest } = data
        return rest
      })
    },
  })
}
</script>
