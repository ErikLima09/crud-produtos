<template>
    <v-text-field 
        v-model="search" 
        class="ma-2" 
        density="compact" 
        placeholder="Pesquisar"
        hide-details>
    </v-text-field>
    <v-data-table-server 
        v-model:items-per-page="itemsPerPage" 
        :headers="headers" 
        :items="produtos"
        :items-length="total" 
        :loading="loading" 
        :search="search" 
        item-value="id" 
        @update:options="handleUpdateOptions">

        <template #item.preco="{ item }">
            R$ {{ Number(item.preco).toFixed(2) }}
        </template>

        <template #item.validade="{ item }">
            {{ formatarDataBr(item.validade) }}
        </template>

        <template #item.categoria="{ item }">
            {{ item.categoria?.nome || '-' }}
        </template>

        
        <template #item.imagem="{ item }">
            <div v-if="item.imagem">
                <v-img :src="`http://localhost:8000/storage/${item.imagem}`" alt="Imagem" max-width="50" max-height="50" cover  />
            </div>
        </template>

        <template #item.acoes="{ item }">
            <v-icon class="me-2" @click="emit('edit', item)">mdi-pencil</v-icon>
            <v-icon color="red" @click="emit('delete', item.id)">mdi-delete</v-icon>
        </template>
    </v-data-table-server>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import axios from 'axios'
import type { Produto } from '../store/produtoStore'

const produtos = ref<Produto[]>([])
const total = ref(0)
const itemsPerPage = ref(10)
const loading = ref(false)
const search = ref('')
const tableOptions = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    search: ''
})

const emit = defineEmits<{
    (e: 'edit', item:Produto): void
    (e: 'delete', id: number): void
}>()

const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Nome', key: 'nome' },
    { title: 'Preço', key: 'preco' },
    { title: 'Descrição', key: 'descricao' },
    { title: 'Validade', key: 'validade' },
    { title: 'Categoria', key: 'categoria' },
    { title: 'Imagem', key: 'imagem' },
    { title: 'Ações', key: 'acoes', sortable: false },
]

function refreshProdutos() {
    fetchProdutos(tableOptions.value)
}

defineExpose({
    refreshProdutos,
})

function formatarDataBr(dataIso: string): string {
    const data = new Date(dataIso)
    return new Intl.DateTimeFormat('pt-BR').format(data)
}

let debounceTimeout: ReturnType<typeof setTimeout> | null = null

async function fetchProdutos(options: {
    page: number
    search: string
    itemsPerPage: number
    sortBy: Array<{ key: string; order: 'asc' | 'desc' }>
}) {
    loading.value = true
    try {
        const { page, itemsPerPage, sortBy, search } = options

        const params: any = {
            page,
            search,
            per_page: itemsPerPage,
        }

        if (sortBy.length > 0) {
            params.sort = sortBy[0].key
            params.order = sortBy[0].order
        }

        const response = await axios.get('http://localhost:8000/api/produtos', {
            params,
        })

        produtos.value = response.data.data || response.data
        total.value = response.data.total || response.data.length
    } catch (error) {
        console.error('Erro ao buscar produtos:', error)
    } finally {
        loading.value = false
    }
}

// Função debounce que chama fetchProdutos depois de um tempo parado.
function debouncedFetch(options: Parameters<typeof fetchProdutos>[0]) {
    if (debounceTimeout) clearTimeout(debounceTimeout)
    debounceTimeout = setTimeout(() => {
        fetchProdutos(options)
    }, 500)
}

// Observe a variável search para disparar debounce
watch(search, (newSearch) => {
    // Sempre começa da página 1 ao pesquisar
    debouncedFetch({
        page: 1,
        search: newSearch,
        itemsPerPage: itemsPerPage.value,
        sortBy: [],
    })

})

// Atualize fetchProdutos para chamar debouncedFetch nos eventos do datatable.
function handleUpdateOptions(options: any) {
    tableOptions.value = options
    debouncedFetch(options)
}

</script>

