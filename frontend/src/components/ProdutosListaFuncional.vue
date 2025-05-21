<template>
      <v-data-table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Descrição</th>
                <th>Validade</th>
                <th>Categoria</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="produto in produtos" :key="produto.id">
                <td>{{  produto.id }}</td>
                <td>{{  produto.nome }}</td>
                <td>R$ {{ produto.preco.toFixed(2) }}</td>
                <td>{{  produto.descricao }}</td>
                <td>{{  formatarDataBr(produto.validade) }}</td>
                <td>{{  produto.categoria?.nome }}</td>
                <td>
                    <v-img
                        :src="`/storage/${produto.imagem}`"
                        alt="Imagem"
                        max-width="80"
                        max-height="80"
                        cover
                        class="rounded"
                    />
                </td>
                <td>
                    <v-icon class="me-2" @click="$emit('edit', produto)">mdi-pencil</v-icon>
                    <v-icon color="red" @click="$emit('delete', produto.id)">mdi-delete</v-icon>
                </td>
            
            </tr>
        </tbody>
    </v-data-table>
</template>

<script setup lang="ts">

import { computed, onMounted } from 'vue';
import { useProdutoStore } from '../store/produtoStore';
import axios from 'axios';

const store = useProdutoStore()
const produtos = computed(() => store.produtos)

function formatarDataBr(dataIso: string): string {
    const data = new Date(dataIso)
    return new Intl.DateTimeFormat('pt-BR').format(data)
}

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