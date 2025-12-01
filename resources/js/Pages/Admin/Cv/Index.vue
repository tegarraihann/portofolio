<template>
    <div class="flex min-h-screen bg-gray-50">
        <AdminSidebar />

        <div class="flex-1 flex flex-col">
            <AdminNavbar />

            <main class="flex-1 p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">CV Download</h1>
                        <p class="text-gray-600 mt-1">Unggah CV terbaru agar tombol "Download CV" di landing page selalu up to date.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Upload CV</h2>

                        <div v-if="statusMessage" class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                            {{ statusMessage }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label for="cv" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih file (PDF/DOC/DOCX, maks 5MB)
                                </label>
                                <input
                                    id="cv"
                                    type="file"
                                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    @change="onFileChange"
                                />
                                <p class="text-xs text-gray-500 mt-2">File lama akan digantikan secara otomatis.</p>
                            </div>

                            <div class="flex items-center space-x-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition disabled:opacity-50"
                                >
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                    </svg>
                                    Simpan CV
                                </button>
                                <span v-if="form.errors.cv" class="text-sm text-red-600">{{ form.errors.cv }}</span>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6 space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">Link Saat Ini</h2>

                        <div v-if="hasCv" class="space-y-2">
                            <p class="text-sm text-gray-600 break-all">
                                {{ cvUrl }}
                            </p>
                            <div class="flex space-x-2">
                                <Link
                                    :href="cvUrl"
                                    class="px-3 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium"
                                    target="_blank"
                                >
                                    Lihat/Unduh
                                </Link>
                                <button
                                    type="button"
                                    @click="copyLink"
                                    class="px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 text-sm font-medium"
                                >
                                    Salin Link
                                </button>
                            </div>
                            <p v-if="copySuccess" class="text-green-600 text-sm">Link tersalin.</p>
                        </div>

                        <div v-else class="text-sm text-gray-500">
                            Belum ada CV yang diunggah. Setelah upload, tombol di landing page akan menggunakan file ini.
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AdminNavbar from '../Components/AdminNavbar.vue'
import AdminSidebar from '../Components/AdminSidebar.vue'

const props = defineProps({
    cvUrl: {
        type: String,
        default: null
    },
    hasCv: {
        type: Boolean,
        default: false
    }
})

const form = useForm({
    cv: null
})

const page = usePage()
const statusMessage = computed(() => page.props.flash?.status || '')
const copySuccess = ref(false)

const onFileChange = (event) => {
    form.cv = event.target.files[0]
    copySuccess.value = false
}

const submit = () => {
    if (!form.cv) return

    form.post(route('admin.cv.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset('cv')
        }
    })
}

const copyLink = async () => {
    if (!props.cvUrl || !navigator?.clipboard) return
    try {
        await navigator.clipboard.writeText(props.cvUrl)
        copySuccess.value = true
    } catch (e) {
        copySuccess.value = false
    }
}
</script>
