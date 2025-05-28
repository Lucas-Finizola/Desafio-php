<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Cabeçalho -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detalhes do Chamado</h1>
        <Link 
          :href="route('chamados.index')" 
          class="text-sm text-blue-600 hover:text-blue-800"
        >
          Voltar para lista
        </Link>
      </div>

      <!-- Card principal -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Cabeçalho do chamado -->
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex justify-between items-start">
            <div>
              <h2 class="text-xl font-semibold text-gray-800">{{ chamado.titulo }}</h2>
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <span class="mr-4">Criado por: {{ chamado.user.name }}</span>
                <span>Criado em: {{ formatDate(chamado.created_at) }}</span>
              </div>
            </div>
            <div class="flex space-x-2">
              <span 
                :class="statusClasses(chamado.status)"
                class="px-3 py-1 rounded-full text-xs font-medium"
              >
                {{ chamado.status }}
              </span>
              <span 
                :class="prioridadeClasses(chamado.prioridade)"
                class="px-3 py-1 rounded-full text-xs font-medium"
              >
                {{ chamado.prioridade }}
              </span>
            </div>
          </div>
        </div>

        <!-- Corpo do chamado -->
        <div class="px-6 py-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <h3 class="text-sm font-medium text-gray-500">Categoria</h3>
              <p class="mt-1 text-sm text-gray-900">{{ chamado.categoria }}</p>
            </div>
            <div>
              <h3 class="text-sm font-medium text-gray-500">Técnico Responsável</h3>
              <p class="mt-1 text-sm text-gray-900">
                {{ chamado.tecnico ? chamado.tecnico.name : 'Aguardando atribuição' }}
              </p>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500">Descrição</h3>
            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ chamado.descricao }}</p>
          </div>

          <!-- Anexo -->
          <div v-if="chamado.anexo_url" class="mb-6">
            <h3 class="text-sm font-medium text-gray-500">Anexo</h3>
            <a 
              :href="chamado.anexo_url" 
              target="_blank"
              class="mt-1 inline-flex items-center text-sm text-blue-600 hover:text-blue-800"
            >
              <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
              </svg>
              Visualizar anexo
            </a>
          </div>
        </div>

        <!-- Respostas -->
        <div class="border-t border-gray-200 px-6 py-4">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Respostas</h3>
          
          <div v-if="chamado.respostas.length > 0" class="space-y-4">
            <div 
              v-for="resposta in chamado.respostas" 
              :key="resposta.id"
              class="bg-gray-50 p-4 rounded-lg"
            >
              <div class="flex justify-between items-start">
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ resposta.mensagem }}</p>
                <span class="text-xs text-gray-500">
                  {{ resposta.user.name }} em {{ formatDate(resposta.created_at) }}
                </span>
              </div>
            </div>
          </div>
          <div v-else class="text-center text-gray-500 py-4">
            Nenhuma resposta ainda
          </div>

          <!-- Formulário de resposta (apenas para técnicos) -->
          <div v-if="can.responder" class="mt-6">
            <form @submit.prevent="submitResposta">
              <div class="mb-4">
                <label for="mensagem" class="block text-sm font-medium text-gray-700">Nova Resposta</label>
                <textarea
                  id="mensagem"
                  v-model="form.mensagem"
                  rows="3"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  required
                ></textarea>
              </div>
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Enviar Resposta
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  chamado: Object,
  can: Object,
});

const form = useForm({
  mensagem: '',
});

const submitResposta = () => {
  form.post(route('tecnico.chamados.responder', props.chamado.id), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};

const formatDate = (dateString) => {
  const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('pt-BR', options);
};

const statusClasses = (status) => {
  return {
    'Aberto': 'bg-blue-100 text-blue-800',
    'Em atendimento': 'bg-yellow-100 text-yellow-800',
    'Resolvido': 'bg-green-100 text-green-800',
    'Fechado': 'bg-gray-100 text-gray-800',
  }[status] || 'bg-gray-100 text-gray-800';
};

const prioridadeClasses = (prioridade) => {
  return {
    'Baixa': 'bg-green-100 text-green-800',
    'Média': 'bg-yellow-100 text-yellow-800',
    'Alta': 'bg-red-100 text-red-800',
  }[prioridade] || 'bg-gray-100 text-gray-800';
};
</script>