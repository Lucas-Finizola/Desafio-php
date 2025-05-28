<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h1 class="text-2xl font-bold mb-4">{{ chamado.titulo }}</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <p class="text-gray-600">Categoria: <span class="font-medium">{{ chamado.categoria }}</span></p>
          <p class="text-gray-600">Prioridade: <span class="font-medium">{{ chamado.prioridade }}</span></p>
        </div>
        <div>
          <p class="text-gray-600">Status: <span class="font-medium">{{ chamado.status }}</span></p>
          <p class="text-gray-600">Criado em: <span class="font-medium">{{ formatDate(chamado.created_at) }}</span></p>
        </div>
      </div>

      <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Descrição</h2>
        <p class="text-gray-700">{{ chamado.descricao }}</p>
      </div>

      <!-- Respostas -->
      <div v-if="chamado.respostas.length > 0">
        <h2 class="text-lg font-semibold mb-4">Respostas</h2>
        <div v-for="resposta in chamado.respostas" :key="resposta.id" class="mb-4 border-l-4 border-blue-500 pl-4">
          <p class="text-gray-700">{{ resposta.mensagem }}</p>
          <p class="text-sm text-gray-500 mt-1">Por {{ resposta.user.name }} em {{ formatDate(resposta.created_at) }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { format } from 'date-fns';

defineProps({
  chamado: Object
})

const formatDate = (date) => {
  return format(new Date(date), 'dd/MM/yyyy HH:mm');
}
</script>