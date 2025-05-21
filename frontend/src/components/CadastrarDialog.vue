<template>
    <v-dialog :model-value="modelValue" @update:model-value="emit('update:modelValue', $event)" max-width="500">
        <v-card>
            <v-card-title class="text-h5">Cadastro</v-card-title>
            
            <v-card-text>
                <v-text-field v-model="username" label="Nome" />
                <v-text-field v-model="password" label="Senha" type="password" />
            </v-card-text>

            <v-card-actions class="d-flex justify-end">
                <v-btn text @click="$emit('update:modelValue', false)">Cancelar</v-btn>
                <v-btn color="success" @click="cadastrar">Cadastrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">

import { ref, defineProps, defineEmits } from 'vue'
import axios from 'axios';

const props = defineProps({
    modelValue: Boolean
})

const emit = defineEmits(['update:modelValue'])

const username = ref('')
const password = ref('')

const cadastrar = async () => {
    try {
        await axios.post('http://localhost:8000/api/cadastrar', {
            username: username.value,
            password: password.value
        })

        alert('Cadastro realizado com sucesso!')
        emit('update:modelValue', false)
    } catch (error) {
        alert('Erro ao cadastrar usuário!')
        console.error(error)
    }
}

</script>