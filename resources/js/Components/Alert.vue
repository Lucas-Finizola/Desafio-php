<template>
  <TransitionGroup name="alerts">
    <div
      v-for="(message, type) in filteredMessages"
      :key="type"
      :class="alertClasses(type)"
      class="fixed right-4 top-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <Icon :name="iconName(type)" :class="iconClasses(type)" />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium">
              {{ message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="close(type)"
              class="inline-flex text-gray-400 focus:outline-none"
            >
              <span class="sr-only">Fechar</span>
              <Icon name="x" class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionGroup>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
  timeout: {
    type: Number,
    default: 5000,
  },
});

const emit = defineEmits(['close']);

const messages = computed(() => usePage().props.flash);
const filteredMessages = computed(() => {
  return Object.entries(messages.value).reduce((acc, [key, value]) => {
    if (value) acc[key] = value;
    return acc;
  }, {});
});

const alertClasses = (type) => {
  return {
    success: 'bg-green-50 border-l-4 border-green-400',
    error: 'bg-red-50 border-l-4 border-red-400',
    warning: 'bg-yellow-50 border-l-4 border-yellow-400',
    info: 'bg-blue-50 border-l-4 border-blue-400',
  }[type];
};

const iconName = (type) => {
  return {
    success: 'check-circle',
    error: 'x-circle',
    warning: 'exclamation-circle',
    info: 'information-circle',
  }[type];
};

const iconClasses = (type) => {
  return {
    success: 'text-green-400',
    error: 'text-red-400',
    warning: 'text-yellow-400',
    info: 'text-blue-400',
  }[type];
};

const close = (type) => {
  emit('close', type);
};

// Fecha automaticamente após timeout
setTimeout(() => {
  emit('close');
}, props.timeout);
</script>

<style>
.alerts-enter-active,
.alerts-leave-active {
  transition: all 0.3s ease;
}

.alerts-enter-from,
.alerts-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>