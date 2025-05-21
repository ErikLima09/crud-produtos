import { defineStore } from 'pinia'
import axios from 'axios'

export interface Produto {
    id: number
    nome: string
    preco: number
    descricao: string
    validade: string
    imagem: File | null
    categoria_id: number
    categoria?: {
        id: number
        nome: string
    }
}

export const useProdutoStore = defineStore('produtoStore', {
    state: () => ({
        produtos: [] as Produto[],
        nextId: 1,
    }),
    actions: {
        addProduto(produto: Omit<Produto, 'id'>) {
            this.produtos.push({ ...produto, id: this.nextId++})
        },
        
        updateProduto(produto: Produto) {
            const index = this.produtos.findIndex(p => p.id === produto.id)
            if (index !== -1) this.produtos[index] = produto
        },

        deleteProduto(id: number) {
            this.produtos = this.produtos.filter(p => p.id !== id)
        },

        async fetchProdutos() {
            const response = await axios.get('http://localhost:8000/api/produtos')
            this.produtos = response.data
        }
    },
})