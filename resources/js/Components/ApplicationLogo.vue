<script setup>
const logoSrc = '/images/admin-logo.png'; // letakkan file .png Anda di public/images dengan nama ini
</script>

<template>
    <img
        :src="logoSrc"
        alt="Logo"
        class="h-24 w-24 object-contain rounded-xl bg-gray-200 p-3 shadow-sm"
        v-bind="$attrs"
    />
</template>
