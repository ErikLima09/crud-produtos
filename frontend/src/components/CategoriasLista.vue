<template>
      <v-table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome da categoria</th>
                <th>Data de criação</th>
                <th>Data da última alteração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="categoria in categorias" :key="categoria.id">
                <td>{{  categoria.id }}</td>
                <td>{{  categoria.nome }}</td>
                <td>{{  formatarDataBr(categoria.created_at) }}</td>
                <td>{{  formatarDataBr(categoria.updated_at) }}</td>

                <td>
                    <v-icon class="me-2" @click="$emit('edit', categoria)">mdi-pencil</v-icon>
                    <v-icon color="red" @click="$emit('delete', categoria.id)">mdi-delete</v-icon>
                </td>
            </tr>
        </tbody>
    </v-table>
</template>

<script setup lang="ts">

import { computed, onMounted } from 'vue';
import { useCategoriaStore } from '../store/categoriaStore';
import axios from 'axios'

const store = useCategoriaStore()
const categorias = computed(() => store.categorias)

function formatarDataBr(dataString: string | Date): string {
  const data = new Date(dataString)
  return new Intl.DateTimeFormat('pt-BR').format(data)
}

const fetchCategorias = async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/categorias')
        store.categorias = response.data
    } catch (error) {
        console.error('Erro ao buscar categorias:', error)
    }
}

onMounted(() => {
    fetchCategorias()
})

</script>