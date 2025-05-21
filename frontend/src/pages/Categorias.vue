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
  </v-navigation-drawer>
  <v-container>
    <v-row class="d-flex justify-space-between align-center mb-4">
      <h2 style="margin-left: 10px;">Categorias</h2>
      <v-btn class="mr-5 mt-3" color="primary" @click="dialog = true">Nova Categoria</v-btn>
    </v-row>

    <CategoriasLista @edit="updateCategoria" @delete="deleteCategoria" />
    <CategoriasDialog v-model:visible="dialog" :categoria="selected" @save="saveCategoria" />

  </v-container>
</template>

<script setup lang="ts">

import CategoriasLista from '../components/CategoriasLista.vue'
import CategoriasDialog from '../components/CategoriasDialog.vue'
import { ref } from 'vue'
import { useCategoriaStore, type Categoria } from '../store/categoriaStore'
import axios from 'axios'


const dialog = ref(false)
const selected = ref<Categoria | null>(null)
const categoriaStore = useCategoriaStore()
const store = useCategoriaStore()


const updateCategoria = (categoria: Categoria) => {
  selected.value = { ...categoria }
  dialog.value = true
}

const deleteCategoria = async (id: number) => {
  if (confirm('Deseja realmente excluir esta categoria?')) {
    try {
      await axios.delete(`http://localhost:8000/api/categorias/${id}`)
      await store.fetchCategorias()
    } catch (erro) {
      console.error('Erro ao deletar categoria', erro)
    }
  }
}

const saveCategoria = () => {
  dialog.value = false
  selected.value = null
}
</script>