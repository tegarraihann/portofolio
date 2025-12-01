<template>
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <div class="font-semibold text-lg">Selamat datang, Admin!</div>
    </header>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()

const showFlash = (flash) => {
    if (!flash) return
    const message = flash.status || flash.success
    if (message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000,
        })
    }
    if (flash.error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: flash.error,
        })
    }
}

onMounted(() => {
    showFlash(page.props.flash)
})

watch(
    () => page.props.flash,
    (flash) => {
        showFlash(flash)
    }
)
</script>
