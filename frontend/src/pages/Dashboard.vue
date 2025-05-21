<template>
  <v-navigation-drawer app permanent>
    <v-list>
      <v-list-item
        link
        to="/dashboard"
        prepend-icon="mdi-cart"
        title="Produtos"
      />

      <v-list-item
        link
        to="/categorias"
        prepend-icon="mdi-tag"
        title="Categorias"
      />

      
    </v-list>

    <template #append>
      <v-divider></v-divider>
      <v-list-item
        link
        @click="logout"
        prepend-icon="mdi-logout"
        title="Sair"
      />
    </template>

  </v-navigation-drawer>
  <v-container>
    <v-row class="d-flex justify-space-between align-center mb-4">
      <h2 style="margin-left: 10px;">Produtos</h2>
      <v-btn class="mr-5 mt-3" color="primary" @click="dialog = true">Novo Produto</v-btn>
    </v-row>

    <ProdutosLista @edit="updateProduto" @delete="deleteProduto" ref="listaRef"/>
    <ProdutosDialog v-model:visible="dialog" :produto="selected" @save="saveProduto"/>

  </v-container>
</template>

<script setup lang="ts">

import ProdutosLista from '../components/ProdutosLista.vue'
import ProdutosDialog from '../components/ProdutosDialog.vue'
import { ref } from 'vue'
import { useProdutoStore, type Produto } from '../store/produtoStore'
import axios from 'axios'
import { useRouter } from 'vue-router'


const dialog = ref(false)
const selected = ref<Produto | null>(null)
const produtoStore = useProdutoStore()
const store = useProdutoStore()
const listaRef = ref()
const router = useRouter()

const logout = () => {
  router.push('/login')
}


const updateProduto = (produto: Produto) => {
  selected.value = { ...produto }
  dialog.value = true
  listaRef.value?.refreshProdutos()
}

const deleteProduto = async (id: number) => {
  if (confirm('Deseja realmente excluir este produto?')) {
    try {
      await axios.delete(`http://localhost:8000/api/produtos/${id}`)
      listaRef.value?.refreshProdutos()
    } catch (erro) {
      console.error('Erro ao deletar produto', erro)
    }
  }
}

const saveProduto = () => {
  dialog.value = false
  selected.value = null
  listaRef.value?.refreshProdutos()
}

</script>
