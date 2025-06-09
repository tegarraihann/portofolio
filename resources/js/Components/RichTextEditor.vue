<template>
  <div class="w-full max-w-none">
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-1 p-2 border border-gray-200 border-b-0 rounded-t-lg bg-white">
      <!-- Text Formatting -->
      <button
        @click="execCommand('bold')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Bold"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M6 4v16h5.5c2.8 0 5.5-2.2 5.5-5 0-1.9-1.1-3.6-2.7-4.3C15.4 10.1 16 9.1 16 8c0-2.8-2.2-5-5-5H6zm3 2h2c1.1 0 2 .9 2 2s-.9 2-2 2H9V6zm0 6h2.5c1.4 0 2.5 1.1 2.5 2.5S12.9 17 11.5 17H9v-5z"/>
        </svg>
      </button>

      <button
        @click="execCommand('italic')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Italic"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4h-8z"/>
        </svg>
      </button>

      <button
        @click="execCommand('underline')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Underline"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-gray-300 mx-1"></div>

      <!-- Headings -->
      <button
        @click="execCommand('formatBlock', '<h1>')"
        class="px-3 py-2 text-sm font-bold rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Heading 1"
      >
        H1
      </button>

      <button
        @click="execCommand('formatBlock', '<h2>')"
        class="px-3 py-2 text-sm font-bold rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Heading 2"
      >
        H2
      </button>

      <button
        @click="execCommand('formatBlock', '<h3>')"
        class="px-3 py-2 text-sm font-bold rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Heading 3"
      >
        H3
      </button>

      <button
        @click="execCommand('formatBlock', '<p>')"
        class="px-3 py-2 text-sm rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Paragraph"
      >
        P
      </button>

      <div class="w-px h-6 bg-gray-300 mx-1"></div>

      <!-- Lists -->
      <button
        @click="execCommand('insertUnorderedList')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Bullet List"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M4 6h2v2H4V6zm4 0h12v2H8V6zM4 11h2v2H4v-2zm4 0h12v2H8v-2zM4 16h2v2H4v-2zm4 0h12v2H8v-2z"/>
        </svg>
      </button>

      <button
        @click="execCommand('insertOrderedList')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Numbered List"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V6H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-gray-300 mx-1"></div>

      <!-- Alignment -->
      <button
        @click="execCommand('justifyLeft')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Align Left"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M15 15H3v2h12v-2zm0-8H3v2h12V7zM3 13h18v-2H3v2zm0 8h18v-2H3v2zM3 3v2h18V3H3z"/>
        </svg>
      </button>

      <button
        @click="execCommand('justifyCenter')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Align Center"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M7 15v2h10v-2H7zm-4 6h18v-2H3v2zm0-8h18v-2H3v2zm4-6v2h10V7H7zM3 3v2h18V3H3z"/>
        </svg>
      </button>

      <button
        @click="execCommand('justifyRight')"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Align Right"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M3 21h18v-2H3v2zm6-4h12v-2H9v2zm-6-4h18v-2H3v2zm6-4h12V7H9v2zM3 3v2h18V3H3z"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-gray-300 mx-1"></div>

      <!-- Image Upload -->
      <button
        @click="triggerImageUpload"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Insert Image"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
        </svg>
      </button>
      <input
        ref="imageInput"
        type="file"
        accept="image/*"
        @change="handleImageUpload"
        class="hidden"
      />

      <!-- Link -->
      <button
        @click="toggleLink"
        class="p-2 rounded-md hover:bg-gray-100 transition-colors"
        type="button"
        title="Add Link"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
        </svg>
      </button>
    </div>

    <!-- Editor Content -->
    <div
      ref="editor"
      contenteditable="true"
      @input="onInput"
      @paste="onPaste"
      class="border border-gray-200 rounded-b-lg bg-white min-h-[400px] p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
      style="max-width: none;"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'

// Props dan Emits
const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

// Refs
const editor = ref(null)
const imageInput = ref(null)

// Methods
const execCommand = (command, value = null) => {
  if (!editor.value) return

  document.execCommand(command, false, value)
  editor.value.focus()
  emitContent()
}

const onInput = () => {
  emitContent()
}

const onPaste = (event) => {
  // Allow paste and then emit content
  nextTick(() => {
    emitContent()
  })
}

const emitContent = () => {
  if (editor.value) {
    emit('update:modelValue', editor.value.innerHTML)
  }
}

const triggerImageUpload = () => {
  if (imageInput.value) {
    imageInput.value.click()
  }
}

const handleImageUpload = async (event) => {
  // Validate event and file
  if (!event || !event.target || !event.target.files) {
    console.warn('Invalid event object')
    return
  }

  const file = event.target.files[0]
  if (!file) {
    console.warn('No file selected')
    return
  }

  // Validate editor element
  if (!editor.value) {
    console.error('Editor element not found')
    alert('Editor not ready. Please try again.')
    return
  }

  try {
    const formData = new FormData()
    formData.append('image', file)

    // Get CSRF token dengan beberapa cara
    let csrfToken = null

    // Method 1: dari meta tag
    const csrfMeta = document.querySelector('meta[name="csrf-token"]')
    if (csrfMeta && csrfMeta.getAttribute('content')) {
      csrfToken = csrfMeta.getAttribute('content')
    }

    // Method 2: dari meta tag dengan name _token
    if (!csrfToken) {
      const tokenMeta = document.querySelector('meta[name="_token"]')
      if (tokenMeta && tokenMeta.getAttribute('content')) {
        csrfToken = tokenMeta.getAttribute('content')
      }
    }

    // Method 3: dari input hidden (jika ada form)
    if (!csrfToken) {
      const tokenInput = document.querySelector('input[name="_token"]')
      if (tokenInput && tokenInput.value) {
        csrfToken = tokenInput.value
      }
    }

    // Method 4: dari window object (jika di-set secara global)
    if (!csrfToken && window.Laravel && window.Laravel.csrfToken) {
      csrfToken = window.Laravel.csrfToken
    }

    // Method 5: dari Inertia page props (jika menggunakan Inertia)
    if (!csrfToken && window.page && window.page.props && window.page.props.csrf_token) {
      csrfToken = window.page.props.csrf_token
    }

    console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No')

    if (!csrfToken) {
      throw new Error('CSRF token not found. Please refresh the page and try again.')
    }

    // Check if route function exists (Laravel/Inertia)
    let uploadUrl
    if (typeof route === 'function') {
      uploadUrl = route('admin.articles.uploadImage')
    } else {
      // Fallback URL - adjust according to your route
      uploadUrl = '/admin/articles/upload-image'
    }

    console.log('Upload URL:', uploadUrl)

    const response = await fetch(uploadUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: formData,
    })

    console.log('Response status:', response.status)

    if (response.ok) {
      const data = await response.json()
      console.log('Response data:', data)

      if (data && data.url) {
        const img = `<img src="${data.url}" alt="Uploaded image" style="max-width: 100%; height: auto; margin: 10px 0;" />`

        // Insert image at cursor position
        editor.value.focus()
        document.execCommand('insertHTML', false, img)
        emitContent()

        alert('Image uploaded successfully!')
      } else {
        throw new Error('Invalid response format: missing URL')
      }
    } else {
      const errorText = await response.text()
      console.error('Error response:', errorText)

      let errorMessage = `Upload failed with status: ${response.status}`
      try {
        const errorData = JSON.parse(errorText)
        if (errorData.message) {
          errorMessage = errorData.message
        }
      } catch (e) {
        // If not JSON, use the raw text
        if (errorText) {
          errorMessage = errorText
        }
      }

      throw new Error(errorMessage)
    }
  } catch (error) {
    console.error('Error uploading image:', error)
    alert(`Error uploading image: ${error.message}`)
  } finally {
    // Clear input
    if (event.target) {
      event.target.value = ''
    }
  }
}

const toggleLink = () => {
  const selection = window.getSelection()
  if (!selection || selection.rangeCount === 0) {
    alert('Please select text first')
    return
  }

  const range = selection.getRangeAt(0)
  const selectedText = range.toString()

  if (!selectedText) {
    alert('Please select text first')
    return
  }

  const url = prompt('Enter URL:', 'https://')
  if (url && url !== 'https://') {
    const link = `<a href="${url}" target="_blank">${selectedText}</a>`
    document.execCommand('insertHTML', false, link)
    emitContent()
  }
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (editor.value && editor.value.innerHTML !== newValue) {
    editor.value.innerHTML = newValue || ''
  }
})

// Initialize content
onMounted(() => {
  nextTick(() => {
    if (editor.value) {
      editor.value.innerHTML = props.modelValue || ''
    }
  })
})
</script>

<style scoped>
/* Editor Styles */
:deep([contenteditable="true"]) {
  outline: none;
}

:deep([contenteditable="true"] h1) {
  font-size: 2rem;
  font-weight: 700;
  margin: 1rem 0 0.5rem 0;
  line-height: 1.2;
}

:deep([contenteditable="true"] h2) {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 1rem 0 0.5rem 0;
  line-height: 1.3;
}

:deep([contenteditable="true"] h3) {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0.75rem 0 0.5rem 0;
  line-height: 1.4;
}

:deep([contenteditable="true"] p) {
  margin: 0.5rem 0;
  line-height: 1.6;
}

:deep([contenteditable="true"] ul) {
  margin: 0.5rem 0;
  padding-left: 1.5rem;
}

:deep([contenteditable="true"] ol) {
  margin: 0.5rem 0;
  padding-left: 1.5rem;
}

:deep([contenteditable="true"] li) {
  margin: 0.25rem 0;
  line-height: 1.5;
}

:deep([contenteditable="true"] a) {
  color: #3b82f6;
  text-decoration: underline;
}

:deep([contenteditable="true"] a:hover) {
  color: #1d4ed8;
}

:deep([contenteditable="true"] img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

:deep([contenteditable="true"] strong) {
  font-weight: 700;
}

:deep([contenteditable="true"] em) {
  font-style: italic;
}

:deep([contenteditable="true"] u) {
  text-decoration: underline;
}
</style>
