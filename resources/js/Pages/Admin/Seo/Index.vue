<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">SEO Management</h1>
                        <p class="text-gray-600 mt-1">Atur meta default, OG/Twitter image, dan indexing.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6 space-y-6">
                        <div v-if="statusMessage" class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                            {{ statusMessage }}
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Default Title</label>
                                <input
                                    v-model="form.default_title"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Misal: Tegar Akmali - Portfolio"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Default Description</label>
                                <textarea
                                    v-model="form.default_description"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Deskripsi singkat situs Anda"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center space-x-2">
                                    <input id="robots_index" type="checkbox" v-model="form.robots_index" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                                    <label for="robots_index" class="text-sm text-gray-700">Izinkan indexing (robots)</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input id="sitemap_enabled" type="checkbox" v-model="form.sitemap_enabled" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                                    <label for="sitemap_enabled" class="text-sm text-gray-700">Aktifkan sitemap</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Default OG Image</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="file" accept="image/*" @change="onFileChange($event, 'default_og_image')" />
                                    </div>
                                    <div v-if="setting?.default_og_image" class="text-xs text-gray-600 break-all">
                                        Saat ini: /storage/{{ setting.default_og_image }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Default Twitter Image</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="file" accept="image/*" @change="onFileChange($event, 'default_twitter_image')" />
                                    </div>
                                    <div v-if="setting?.default_twitter_image" class="text-xs text-gray-600 break-all">
                                        Saat ini: /storage/{{ setting.default_twitter_image }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button
                                type="button"
                                :disabled="form.processing"
                                @click="submit"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition disabled:opacity-50"
                            >
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tips</h3>
                        <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                            <li>Title ideal &lt;= 60 karakter; description &lt;= 160.</li>
                            <li>OG/Twitter image: 1200x630 (JPG/PNG), ukuran &lt;3MB.</li>
                            <li>Nonaktifkan indexing saat staging dengan uncheck “Izinkan indexing”.</li>
                            <li>Pastikan symlink storage aktif untuk menayangkan gambar OG/Twitter.</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import AdminSidebar from '../Components/AdminSidebar.vue'

const props = defineProps({
    setting: {
        type: Object,
        default: null
    }
})

const form = useForm({
    default_title: props.setting?.default_title || '',
    default_description: props.setting?.default_description || '',
    robots_index: props.setting?.robots_index ?? true,
    sitemap_enabled: props.setting?.sitemap_enabled ?? true,
    default_og_image: null,
    default_twitter_image: null,
})

const page = usePage()
const statusMessage = computed(() => page.props.flash?.status || '')

const onFileChange = (event, field) => {
    form[field] = event.target.files[0]
}

const submit = () => {
    form.post(route('admin.seo.update'), {
        forceFormData: true,
        preserveScroll: true,
    })
}
</script>
