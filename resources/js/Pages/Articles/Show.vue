<!-- resources/js/Pages/Articles/Show.vue -->
<template>

    <Head>
        <title>{{ metaData.title }}</title>
        <meta name="description" :content="metaData.description">
        <!-- Open Graph / Facebook -->
        <meta property="og:type" :content="metaData.type">
        <meta property="og:url" :content="metaData.url">
        <meta property="og:title" :content="metaData.title">
        <meta property="og:description" :content="metaData.description">
        <meta property="og:image" :content="metaData.image" v-if="metaData.image">
        <meta property="og:site_name" :content="metaData.site_name">
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" :content="metaData.url">
        <meta property="twitter:title" :content="metaData.title">
        <meta property="twitter:description" :content="metaData.description">
        <meta property="twitter:image" :content="metaData.image" v-if="metaData.image">
    </Head>
    <LandingLayout>
        <div class="max-w-4xl mx-auto py-12 px-4">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <Link href="/articles"
                    class="text-indigo-600 hover:text-indigo-700 text-sm font-medium inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                Kembali ke Artikel
                </Link>
            </div>

            <!-- Article Header -->
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ article.title }}
                </h1>

                <!-- Article Meta -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ formatDate(article.created_at) }}
                    </span>

                    <span v-if="article.category" class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-md text-xs font-medium">
                            {{ article.category.name }}
                        </span>
                    </span>

                    <span v-if="article.status" class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span :class="statusClass(article.status)" class="px-2 py-1 rounded-md text-xs font-medium">
                            {{ statusText(article.status) }}
                        </span>
                    </span>
                </div>

                <!-- Tags -->
                <div v-if="article.tags && article.tags.length" class="flex flex-wrap gap-2 mb-6">
                    <span v-for="tag in article.tags" :key="tag.id"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                        #{{ tag.name }}
                    </span>
                </div>

                <!-- Thumbnail -->
                <div v-if="article.thumbnail_path" class="mb-8">
                    <img :src="getThumbnailUrl(article.thumbnail_path)" :alt="article.title"
                        class="w-full h-64 md:h-96 object-cover rounded-xl shadow-lg" @error="handleImageError" />
                </div>
            </header>

            <!-- Article Content -->
            <article class="article-content">
                <div v-html="article.content" class="prose prose-lg prose-slate max-w-none
                 prose-headings:text-gray-900 prose-headings:font-bold
                 prose-h1:text-3xl prose-h1:mb-6 prose-h1:mt-8
                 prose-h2:text-2xl prose-h2:mb-5 prose-h2:mt-7
                 prose-h3:text-xl prose-h3:mb-4 prose-h3:mt-6
                 prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                 prose-a:text-indigo-600 prose-a:font-medium hover:prose-a:text-indigo-700
                 prose-strong:text-gray-900 prose-strong:font-semibold
                 prose-em:text-gray-800 prose-em:italic
                 prose-ul:list-disc prose-ul:ml-6 prose-ul:mb-4
                 prose-ol:list-decimal prose-ol:ml-6 prose-ol:mb-4
                 prose-li:mb-2 prose-li:text-gray-700
                 prose-blockquote:border-l-4 prose-blockquote:border-indigo-500
                 prose-blockquote:pl-6 prose-blockquote:italic prose-blockquote:text-gray-600
                 prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm
                 prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:p-4 prose-pre:rounded-lg prose-pre:overflow-x-auto
                 prose-img:rounded-lg prose-img:shadow-md prose-img:mx-auto
                 prose-table:table-auto prose-table:border-collapse prose-table:w-full
                 prose-th:border prose-th:border-gray-300 prose-th:bg-gray-50 prose-th:p-2
                 prose-td:border prose-td:border-gray-300 prose-td:p-2"></div>
            </article>

            <!-- Article Footer -->
            <footer class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <Link href="/articles"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Lihat Artikel Lainnya
                    </Link>

                    <!-- Share buttons -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 mr-2">Bagikan:</span>
                        <button @click="shareOnFacebook" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                            title="Bagikan ke Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </button>

                        <button @click="shareOnTwitter" class="p-2 text-gray-400 hover:text-blue-400 transition-colors"
                            title="Bagikan ke Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </button>

                        <button @click="shareOnWhatsApp"
                            class="p-2 text-gray-400 hover:text-green-600 transition-colors"
                            title="Bagikan ke WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.89 3.488" />
                            </svg>
                        </button>

                        <button @click="copyLink" class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                            title="Salin link">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Additional Article Info -->
                <div class="mt-6 text-sm text-gray-500">
                    <p>Artikel ini terakhir diperbarui pada {{ formatDate(article.updated_at) }}</p>
                </div>
            </footer>
        </div>
    </LandingLayout>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3';
import LandingLayout from '@/Layouts/LandingLayout.vue';


// Receive props dari controller
const props = defineProps({
    article: Object,
    metaData: Object
});

const { article, metaData } = props;


console.log('MetaData received:', metaData);

// Helper functions
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getThumbnailUrl = (path) => {
    if (!path) return '';
    return path.startsWith('/') ? `/storage${path}` : `/storage/${path}`;
};

const handleImageError = (event) => {
    console.warn('Image failed to load:', event.target.src);
    event.target.style.display = 'none';
};

const statusClass = (status) => {
    const classes = {
        'published': 'bg-green-100 text-green-800',
        'draft': 'bg-yellow-100 text-yellow-800',
        'archived': 'bg-gray-100 text-gray-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const statusText = (status) => {
    const texts = {
        'published': 'Dipublikasi',
        'draft': 'Draft',
        'archived': 'Diarsipkan'
    };
    return texts[status] || status;
};

// Share functions
const shareOnFacebook = () => {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
};

const shareOnTwitter = () => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
};

const shareOnWhatsApp = () => {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Baca artikel menarik ini: ${document.title}`);
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
};

const copyLink = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        alert('Link berhasil disalin!');
    } catch (err) {
        console.error('Gagal menyalin link:', err);
        // Fallback untuk browser lama
        const textArea = document.createElement('textarea');
        textArea.value = window.location.href;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Link berhasil disalin!');
    }
};
</script>

<style scoped>
/* Custom styles untuk konten artikel */
.article-content {
    line-height: 1.8;
}

/* Ensure images in content are responsive */
.article-content :deep(img) {
    max-width: 100%;
    height: auto;
    margin: 1rem auto;
    display: block;
}

/* Style untuk embedded content */
.article-content :deep(iframe) {
    max-width: 100%;
    margin: 1rem auto;
    display: block;
}

/* Style untuk code blocks */
.article-content :deep(pre) {
    overflow-x: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* Responsive table */
.article-content :deep(table) {
    overflow-x: auto;
    display: block;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .article-content :deep(table) {
        font-size: 0.875rem;
    }
}
</style>
