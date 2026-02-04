<script setup>
defineProps({
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  message: {
    type: String,
    required: true
  },
  dismissible: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['dismiss'])

const typeClasses = {
  success: 'bg-green-50 border-green-200 text-green-800',
  error: 'bg-red-50 border-red-200 text-red-800',
  warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
  info: 'bg-blue-50 border-blue-200 text-blue-800'
}
</script>

<template>
  <div :class="['p-4 rounded-lg border', typeClasses[type]]" class="flex justify-between items-center">
    <p>{{ message }}</p>
    <button v-if="dismissible" @click="emit('dismiss')" class="ml-4 text-lg font-bold hover:opacity-70">
      ×
    </button>
  </div>
</template>
