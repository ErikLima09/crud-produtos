<template>
    <v-dialog v-model="visible" max-width="500">
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                {{ tituloDialog }}
                <v-icon @click="visible = false">mdi-close</v-icon>
            </v-card-title>

            <v-form ref="formRef" v-model="formValido">
              <v-card-text>
                  <v-text-field v-model="form.nome"         label="Nome"      type="text" :rules="[rules.required, rules.isOnlyLetters, rules.maxLength(50)]" />
                  <v-text-field v-model="form.preco"        label="Preço"                 :rules="[rules.required, rules.isNumber, rules.minValue]" />
                  <v-text-field v-model="form.descricao"    label="Descrição"             :rules="[rules.maxLength(200)]" />
                  <v-text-field v-model="form.validade"     label="Validade"  type="date" :rules="[rules.required, rules.futureDate]" />
                  <v-select     
                    v-model="form.categoria_id"
                    :items="categorias"
                    item-title="nome"
                    item-value="id"
                    label="Categoria"
                    :rules="[rules.required]" 
                  />
              </v-card-text>
              <v-file-input 
                class="mr-4 ml-3"
                v-model="form.imagem" 
                label="Imagem do produto" 
                accept="image/*"
                :multiple="false"
                append-inner-icon="mdi-camera"
                prepend-icon=""
                show-size
              />
              <v-card-actions>
                  <v-spacer />
                  <v-btn color="primary" @click="submitForm" :rules="[rules]">Salvar</v-btn>
              </v-card-actions>
            </v-form>

        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">

import { ref, watch, defineProps, defineEmits, computed } from 'vue';
import type { Produto } from '../store/produtoStore';
import { useProdutoStore } from '../store/produtoStore';
import api from '../services/api';
import { onMounted } from 'vue';

const formRef = ref()
const formValido = ref(false)
const categorias = ref<{ id: number; nome: string }[]>([])

const tituloDialog = computed(() => {
  return form.value.id ? 'Editar Produto' : 'Novo Produto'
})

onMounted(async () => {
  try {
    const response = await api.get('categorias')
    categorias.value = response.data
  } catch (error) {
    console.error('Erro ao buscar categorias', error)
  }
})

const form = ref<Produto>({
  id: 0,
  nome: '', 
  preco: 0, 
  descricao: '', 
  imagem: null, 
  validade: '', 
  categoria_id: 0
})

const rules = {
  required: (v: any) => !!v || 'Campo obrigatório.',
  maxLength: (max: number) => (v: string)  => (v?.length <= max) || `Valor máximo de caracteres ${max}`, 
  minValue: (v: number) => v > 0 || "Valor precisa ser maior que 0.",
  isOnlyLetters: (v: string) => {
    if (!v) return true
    return /^[A-Za-zÀ-ÿ\s]+$/.test(v) || 'O nome deve conter apenas letras'
  },
  isNumber: (v: any) => {
    
    return /^\d+(\.\d{1,2})?$/.test(v.toString()) || 'Insira um número válido'
  },
  futureDate: (v: string) => {
    if (!v) return true 
    const hoje = new Date()
    const dataInformada = new Date(v)

    hoje.setHours(0, 0, 0, 0)
    dataInformada.setHours(0, 0, 0, 0)

    return dataInformada >= hoje || 'A data deve ser hoje ou no futuro'
  }
}


const submitForm = async () => {
  const formData = new FormData();

  const valido = await formRef.value?.validate();
  
    if (!valido) {
      console.warn('Formulário inválido. Corrija os campos antes de continuar.');
      return;
    }

    formData.append('nome', form.value.nome);
    formData.append('descricao', form.value.descricao);
    formData.append('preco', form.value.preco.toString());
    formData.append('validade', String(form.value.validade));
    formData.append('categoria_id', form.value.categoria_id.toString());

    if (form.value.imagem instanceof File) {
      formData.append('imagem', form.value.imagem);
    }

    try {
      if (form.value.id) {
        // Editar Produto
        await api.post(`/produtos/${form.value.id}?_method=PUT`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        // Novo produto
        await api.post('/produtos', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
        });
      }

      emit('save');
      visible.value = false;
      
    } catch (error) {
      console.error('Erro ao salvar', error);
    }  
}

const props = defineProps<{ visible: boolean; produto: Produto | null}>()
const emit = defineEmits(['update:visible', 'save'])

const store = useProdutoStore()

watch(() => props.produto, (newVal) => {
  form.value = newVal ? { ...newVal } : { id: 0, nome: '', preco: 0, descricao: '', validade: '', categoria_id: 0, imagem: null }
}, { immediate: true })

watch(() => props.visible, (val) => {
  if (!val) form.value = { id: 0, nome: '', preco: 0, descricao: '', validade: '', categoria_id: 0, imagem: null }
})

const visible = computed({
  get: () => props.visible,
  set: (v) => emit('update:visible', v)
})

</script>