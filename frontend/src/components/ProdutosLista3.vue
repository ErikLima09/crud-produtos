<template>
  <v-data-table
    :headers="headers"
    :items="produtos"
    v-model:page="pagination.page"
    v-model:items-per-page="pagination.itemsPerPage"
    class="elevation-1"
    :items-length="produtos.length"
    :items-per-page-options="[5, 10, 20]"
  >
    <template #item.preco="{ item }">
      R$ {{ item.preco.toFixed(2) }}
    </template>

    <template #item.validade="{ item }">
      {{ item.validade }}
    </template>

    <template #item.categoria="{ item }">
      {{ item.categoria?.nome ?? 'Sem categoria' }}
    </template>

    <template #item.imagem="{ item }">
      <v-img
        :src="`/storage/${item.imagem}`"
        alt="Imagem"
        max-width="80"
        max-height="80"
        cover
        class="rounded"
      />
    </template>

    <template #item.acao="{ item }">
      <v-icon class="me-2" @click="$emit('edit', item)">mdi-pencil</v-icon>
      <v-icon color="red" @click="$emit('delete', item.id)">mdi-delete</v-icon>
    </template>
  </v-data-table>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useProdutoStore } from '../store/produtoStore'
import axios from 'axios'

const store = useProdutoStore()
const produtos = computed(() => store.produtos)

const pagination = ref({
  page: 1,
  itemsPerPage: 5
})

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Nome', value: 'nome' },
  { text: 'Preço', value: 'preco' },
  { text: 'Descrição', value: 'descricao' },
  { text: 'Validade', value: 'validade' },
  { text: 'Categoria', value: 'categoria' },
  { text: 'Imagem', value: 'imagem' },
  { text: 'Ações', value: 'acao', sortable: false }
]

const fetchProdutos = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/produtos')
    store.produtos = response.data
  } catch (error) {
    console.error('Erro ao buscar produtos:', error)
  }
}

onMounted(() => {
  fetchProdutos()
})
</script>
