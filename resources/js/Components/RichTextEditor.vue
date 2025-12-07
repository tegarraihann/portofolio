<template>
  <div class="w-full max-w-none">
    <div class="flex flex-wrap items-center gap-2 p-3 border border-gray-200 border-b-0 rounded-t-lg bg-white">
      <button v-for="item in toolbar" :key="item.key" :title="item.label" type="button"
              class="px-2 py-1 rounded-md hover:bg-gray-100 transition-colors text-sm"
              :class="{ 'bg-indigo-50 text-indigo-600': item.isActive && item.isActive() }"
              @click="item.action">
        {{ item.label }}
      </button>

      <div class="w-px h-6 bg-gray-300" />

      <input type="color" v-model="color" class="h-8 w-10 rounded border-gray-300" title="Teks color" @input="setColor" />

      <button type="button" class="px-2 py-1 rounded-md hover:bg-gray-100 text-sm"
              @click="insertTable">Table</button>

      <button type="button" class="px-2 py-1 rounded-md hover:bg-gray-100 text-sm"
              @click="addLink">Link</button>

      <button type="button" class="px-2 py-1 rounded-md hover:bg-gray-100 text-sm"
              @click="triggerImageUpload">Image</button>
      <input ref="imageInput" type="file" accept="image/*" class="hidden" @change="handleImageUpload" />
    </div>

    <div class="border border-gray-200 rounded-b-lg bg-white">
      <EditorContent :editor="editor" class="min-h-[420px] px-4 py-4 w-full text-base leading-relaxed" />
      <div class="flex items-center justify-between px-4 py-2 text-xs text-gray-500 border-t">
        <span>{{ characterCount }}/{{ maxLength }} karakter</span>
        <span class="italic text-gray-400">Autosave via form submit</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount, computed, watch, onMounted } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import TextAlign from '@tiptap/extension-text-align'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import TaskList from '@tiptap/extension-task-list'
import TaskItem from '@tiptap/extension-task-item'
import Placeholder from '@tiptap/extension-placeholder'
import Color from '@tiptap/extension-color'
import TextStyle from '@tiptap/extension-text-style'
import Highlight from '@tiptap/extension-highlight'
import CharacterCount from '@tiptap/extension-character-count'
import Table from '@tiptap/extension-table'
import TableRow from '@tiptap/extension-table-row'
import TableCell from '@tiptap/extension-table-cell'
import TableHeader from '@tiptap/extension-table-header'

const props = defineProps({
  modelValue: { type: String, default: '' },
  maxLength: { type: Number, default: 8000 },
  placeholder: { type: String, default: 'Tulis artikel di sini...' },
})
const emit = defineEmits(['update:modelValue'])

const imageInput = ref(null)
const color = ref('#111827')

const editor = ref(null)

const initEditor = () => {
  editor.value = new Editor({
    content: props.modelValue || '',
    extensions: [
      StarterKit.configure({
        bulletList: { keepMarks: true },
        orderedList: { keepMarks: true },
      }),
      Underline,
      TextAlign.configure({ types: ['heading', 'paragraph'] }),
      Image.configure({ inline: false }),
      Link.configure({ openOnClick: false, autolink: true }),
      TaskList,
      TaskItem.configure({ nested: true }),
      Placeholder.configure({ placeholder: props.placeholder }),
      Color.configure({ types: ['textStyle'] }),
      TextStyle,
      Highlight,
      CharacterCount.configure({ limit: props.maxLength }),
      Table.configure({ resizable: true, lastColumnResizable: true }),
      TableRow,
      TableCell,
      TableHeader,
    ],
    onUpdate: ({ editor }) => {
      emit('update:modelValue', editor.getHTML())
    },
  })
}

onMounted(initEditor)
onBeforeUnmount(() => editor.value?.destroy())

watch(() => props.modelValue, (val) => {
  if (!editor.value) return
  if (val !== editor.value.getHTML()) editor.value.commands.setContent(val || '', false)
})

const characterCount = computed(() => editor.value?.storage.characterCount.characters() || 0)

const setColor = () => {
  editor.value?.chain().focus().setColor(color.value).run()
}

const addLink = () => {
  const url = prompt('Masukkan URL', 'https://')
  if (url) editor.value?.chain().focus().setLink({ href: url, target: '_blank' }).run()
}

const triggerImageUpload = () => imageInput.value?.click()

const handleImageUpload = async (event) => {
  const file = event?.target?.files?.[0]
  if (!file) return
  const formData = new FormData()
  formData.append('image', file)

  const csrf = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content')
  const uploadUrl = typeof route === 'function' ? route('admin.articles.uploadImage') : '/admin/articles/upload-image'

  const res = await fetch(uploadUrl, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
    body: formData,
  })

  if (!res.ok) {
    alert('Upload gambar gagal')
    return
  }
  const data = await res.json()
  if (data?.url) editor.value?.chain().focus().setImage({ src: data.url, alt: file.name }).run()
  event.target.value = ''
}

const insertTable = () => {
  editor.value?.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()
}

const toolbar = computed(() => [
  { key: 'bold', label: 'B', action: () => editor.value?.chain().focus().toggleBold().run(), isActive: () => editor.value?.isActive('bold') },
  { key: 'italic', label: 'I', action: () => editor.value?.chain().focus().toggleItalic().run(), isActive: () => editor.value?.isActive('italic') },
  { key: 'underline', label: 'U', action: () => editor.value?.chain().focus().toggleUnderline().run(), isActive: () => editor.value?.isActive('underline') },
  { key: 'strike', label: 'S', action: () => editor.value?.chain().focus().toggleStrike().run(), isActive: () => editor.value?.isActive('strike') },
  { key: 'h1', label: 'H1', action: () => editor.value?.chain().focus().toggleHeading({ level: 1 }).run(), isActive: () => editor.value?.isActive('heading', { level: 1 }) },
  { key: 'h2', label: 'H2', action: () => editor.value?.chain().focus().toggleHeading({ level: 2 }).run(), isActive: () => editor.value?.isActive('heading', { level: 2 }) },
  { key: 'h3', label: 'H3', action: () => editor.value?.chain().focus().toggleHeading({ level: 3 }).run(), isActive: () => editor.value?.isActive('heading', { level: 3 }) },
  { key: 'bullet', label: '•', action: () => editor.value?.chain().focus().toggleBulletList().run(), isActive: () => editor.value?.isActive('bulletList') },
  { key: 'ordered', label: '1.', action: () => editor.value?.chain().focus().toggleOrderedList().run(), isActive: () => editor.value?.isActive('orderedList') },
  { key: 'task', label: '☑', action: () => editor.value?.chain().focus().toggleTaskList().run(), isActive: () => editor.value?.isActive('taskList') },
  { key: 'align-left', label: 'L', action: () => editor.value?.chain().focus().setTextAlign('left').run(), isActive: () => editor.value?.isActive({ textAlign: 'left' }) },
  { key: 'align-center', label: 'C', action: () => editor.value?.chain().focus().setTextAlign('center').run(), isActive: () => editor.value?.isActive({ textAlign: 'center' }) },
  { key: 'align-right', label: 'R', action: () => editor.value?.chain().focus().setTextAlign('right').run(), isActive: () => editor.value?.isActive({ textAlign: 'right' }) },
  { key: 'highlight', label: 'HL', action: () => editor.value?.chain().focus().toggleHighlight().run(), isActive: () => editor.value?.isActive('highlight') },
])
</script>

<style scoped>
:deep(.ProseMirror) {
  outline: none;
  min-height: 360px;
}
:deep(.ProseMirror table) {
  border-collapse: collapse;
  width: 100%;
  margin: 0.5rem 0 1rem;
  table-layout: auto;
}
:deep(.ProseMirror table td),
:deep(.ProseMirror table th) {
  border: 1px solid #9ca3af;
  padding: 8px 10px;
  min-width: 90px;
  vertical-align: top;
}
:deep(.ProseMirror table th) {
  background: #f3f4f6;
  font-weight: 600;
  color: #111827;
}
:deep(.ProseMirror table .column-resize-handle) {
  position: absolute;
  right: -2px;
  top: 0;
  bottom: -2px;
  width: 4px;
  background: #3b82f6;
  opacity: 0.8;
  cursor: col-resize;
}
:deep(.ProseMirror) .resize-cursor {
  cursor: col-resize;
}
:deep(.ProseMirror img) {
  max-width: 100%;
  border-radius: 0.5rem;
}
:deep(.ProseMirror p) {
  margin: 0.6rem 0;
}
:deep(.ProseMirror ul),
:deep(.ProseMirror ol) {
  padding-left: 1.5rem;
  margin: 0.6rem 0;
}
:deep(.ProseMirror li) {
  margin: 0.25rem 0;
}
:deep(.ProseMirror blockquote) {
  border-left: 3px solid #e5e7eb;
  padding-left: 0.75rem;
  color: #4b5563;
}
:deep(.ProseMirror .is-editor-empty:first-child::before) {
  color: #9ca3af;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}
</style>





