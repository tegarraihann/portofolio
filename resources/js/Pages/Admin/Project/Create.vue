<template>
    <div class="p-8 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Tambah Project Baru</h1>

        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Title -->
            <div>
                <label class="block font-medium mb-1">Judul</label>
                <input v-model="form.title" type="text" class="w-full border rounded px-4 py-2" required />
            </div>

            <!-- Description -->
            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea v-model="form.description" class="w-full border rounded px-4 py-2"></textarea>
            </div>

            <!-- Category -->
            <div>
                <label class="block font-medium mb-1">Kategori</label>
                <select v-model="form.category" class="w-full border rounded px-4 py-2">
                    <option value="">Pilih Kategori</option>
                    <option value="frontend">Frontend</option>
                    <option value="backend">Backend</option>
                    <option value="fullstack">Fullstack</option>
                </select>
            </div>

            <!-- Tech Stack -->
            <div class="flex gap-2 mb-2">
                <input type="text" placeholder="Tambah teknologi (contoh: Vue.js)" v-model="techInput"
                    class="flex-1 border rounded px-4 py-2" />
                <button type="button" @click="addTech"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    +
                </button>
            </div>
            <pre class="text-xs bg-gray-100 p-2 rounded">TechStack: {{ form.tech_stack }}</pre>

            <!-- Thumbnail -->
            <div>
                <label class="block font-medium mb-1">Thumbnail</label>
                <input type="file" @change="handleThumbnail" class="block w-full" />
            </div>

            <!-- Live Demo URL -->
            <div>
                <label class="block font-medium mb-1">Live Demo URL</label>
                <input v-model="form.live_demo_url" type="url" class="w-full border rounded px-4 py-2" />
            </div>

            <!-- GitHub URL -->
            <div>
                <label class="block font-medium mb-1">GitHub URL</label>
                <input v-model="form.github_url" type="url" class="w-full border rounded px-4 py-2" />
            </div>

            <!-- Featured Toggle -->
            <div class="flex items-center">
                <input id="featured" type="checkbox" v-model="form.is_featured" class="mr-2" />
                <label for="featured">Tampilkan sebagai Project Unggulan</label>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Simpan Project
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    description: '',
    category: '',
    tech_stack: [],
    thumbnail: null,
    live_demo_url: '',
    github_url: '',
    is_featured: false,
})

const techInput = ref('')

function addTech() {
  if (techInput.value.trim() !== '') {
    // Split berdasarkan koma jika ada
    const techs = techInput.value.split(',').map(t => t.trim()).filter(Boolean)
    form.tech_stack.push(...techs)
    techInput.value = ''
  }
}

function removeTech(index) {
    form.tech_stack.splice(index, 1)
}

function handleThumbnail(event) {
    form.thumbnail = event.target.files[0]
}

function submitForm() {
    console.log('Tech Stack to submit:', form.tech_stack)
    form.post(route('admin.projects.store'))
}

</script>
