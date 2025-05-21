<template>
    <v-dialog v-model="visible" max-width="500">
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                {{ tituloDialog }}
                <v-icon @click="visible = false">mdi-close</v-icon>
            </v-card-title>

            <v-form ref="formRef" v-model="formValido">
              <v-card-text>
                  <v-text-field 
                    v-model="form.nome"
                    label="Nome"
                    type="text" 
                    :rules="[rules.required, rules.isOnlyLetters, rules.maxLength(100)]" />
              </v-card-text>
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
import type { Categoria } from '../store/categoriaStore';
import { useCategoriaStore } from '../store/categoriaStore';
import api from '../services/api';
import { onMounted } from 'vue';

const formRef = ref()
const formValido = ref(false)
const categorias = ref<{ id: number; nome: string }[]>([])

const tituloDialog = computed(() => {
  return form.value.id ? 'Editar Categoria' : 'Nova Categoria'
})

onMounted(async () => {
  try {
    const response = await api.get('categorias')
    categorias.value = response.data
  } catch (error) {
    console.error('Erro ao buscar categorias', error)
  }
})

const form = ref<Categoria>({
  id: 0,
  nome: ''
})

const rules = {
  required: (v: any) => !!v || 'Campo obrigatório.',
  maxLength: (max: number) => (v: string)  => (v?.length <= max) || `Valor máximo de caracteres ${max}`, 
  isOnlyLetters: (v: string) => {
    if (!v) return true
    return /^[A-Za-zÀ-ÿ\s]+$/.test(v) || 'O nome deve conter apenas letras'
  },
}

const submitForm = async () => {

  const formData = new FormData();
  const valido = await formRef.value?.validate();
  
    if (!valido) {
      console.warn('Formulário inválido. Corrija os campos antes de continuar.');
      return;
    }

    formData.append('nome', form.value.nome);

    try {
      if (form.value.id) {
        // Editar categoria
        await api.post(`/categorias/${form.value.id}?_method=PUT`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        // Nova categoria
        await api.post('/categorias', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
        });
      }

      emit('save');
      visible.value = false;

      //await store.fetchCategorias()
      
    } catch (error) {
      console.error('Erro ao salvar', error);
    }  
}

const props = defineProps<{ visible: boolean; categoria: Categoria | null}>()
const emit = defineEmits(['update:visible', 'save'])

const store = useCategoriaStore()

watch(() => props.categoria, (newVal) => {
  form.value = newVal ? { ...newVal } : { id: 0, nome: ''}
}, { immediate: true })

watch(() => props.visible, (val) => {
  if (!val) form.value = { id: 0, nome: ''}
})

const visible = computed({
  get: () => props.visible,
  set: (v) => emit('update:visible', v)
})

</script>