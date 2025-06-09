<template>
  <component
    :is="tag"
    :href="href"
    :to="to"
    :class="linkClasses"
    :target="target"
    :rel="rel"
    @click="handleClick"
  >
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  // For external links
  href: {
    type: String,
    default: null
  },
  // For Vue Router links
  to: {
    type: [String, Object],
    default: null
  },
  // Link target
  target: {
    type: String,
    default: null
  },
  // Link rel attribute
  rel: {
    type: String,
    default: null
  },
  // Custom classes
  class: {
    type: [String, Array, Object],
    default: ''
  },
  // Variants for different styles
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'secondary', 'danger', 'ghost'].includes(value)
  },
  // Size variants
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  // Disabled state
  disabled: {
    type: Boolean,
    default: false
  },
  // External link indicator
  external: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

const router = useRouter()

// Determine which component to render
const tag = computed(() => {
  if (props.disabled) return 'span'
  if (props.href || props.external) return 'a'
  if (props.to) return 'router-link'
  return 'button'
})

// Base classes for all variants
const baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed'

// Variant classes
const variantClasses = computed(() => {
  const variants = {
    default: 'text-gray-700 hover:text-gray-900 focus:ring-gray-500',
    primary: 'text-indigo-600 hover:text-indigo-800 focus:ring-indigo-500',
    secondary: 'text-gray-500 hover:text-gray-700 focus:ring-gray-400',
    danger: 'text-red-600 hover:text-red-800 focus:ring-red-500',
    ghost: 'text-gray-400 hover:text-gray-600 focus:ring-gray-300'
  }
  return variants[props.variant] || variants.default
})

// Size classes
const sizeClasses = computed(() => {
  const sizes = {
    sm: 'text-sm px-2 py-1',
    md: 'text-base px-3 py-2',
    lg: 'text-lg px-4 py-3'
  }
  return sizes[props.size] || sizes.md
})

// Combine all classes
const linkClasses = computed(() => {
  const classes = [baseClasses, variantClasses.value, sizeClasses.value]

  if (props.disabled) {
    classes.push('cursor-not-allowed opacity-50')
  }

  if (props.class) {
    if (typeof props.class === 'string') {
      classes.push(props.class)
    } else if (Array.isArray(props.class)) {
      classes.push(...props.class)
    } else {
      // Handle object syntax
      Object.keys(props.class).forEach(key => {
        if (props.class[key]) {
          classes.push(key)
        }
      })
    }
  }

  return classes.join(' ')
})

// Handle click events
const handleClick = (event) => {
  if (props.disabled) {
    event.preventDefault()
    return
  }

  emit('click', event)

  // Handle programmatic navigation for router links
  if (props.to && !event.defaultPrevented) {
    router.push(props.to)
  }
}

// Auto-set target and rel for external links
const target = computed(() => {
  if (props.external || (props.href && props.href.startsWith('http'))) {
    return props.target || '_blank'
  }
  return props.target
})

const rel = computed(() => {
  if (props.external || (props.href && props.href.startsWith('http'))) {
    return props.rel || 'noopener noreferrer'
  }
  return props.rel
})
</script>

<style scoped>
/* Additional custom styles if needed */
.router-link-active {
  @apply text-indigo-600 font-semibold;
}

.router-link-exact-active {
  @apply text-indigo-800 font-bold;
}

/* Hover effects */
a:hover {
  text-decoration: none;
}

/* Focus styles */
a:focus,
button:focus {
  outline: none;
}

/* Animation for smooth transitions */
.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>
