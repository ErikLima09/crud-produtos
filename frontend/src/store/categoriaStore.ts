import { defineStore } from 'pinia'
import axios from 'axios'

export interface Categoria {
    id: number
    nome: string
    created_at: Date
    updated_at: Date
}

export const useCategoriaStore = defineStore('categoriaStore', {
    state: () => ({
        categorias: [] as Categoria[],
        nextId: 1,
    }),
    actions: {
        addCategoria(categoria: Omit<Categoria, 'id'>) {
            this.categorias.push({ ...categoria, id: this.nextId++})
        },
        
        updateCategoria(categoria: Categoria) {
            const index = this.categorias.findIndex(p => p.id === categoria.id)
            if (index !== -1) this.categorias[index] = categoria
        },

        deleteCategoria(id: number) {
            this.categorias = this.categorias.filter(p => p.id !== id)
        },

        async fetchCategorias() {
            const response = await axios.get('http://localhost:8000/api/categorias')
            this.categorias = response.data
        }
    },
})