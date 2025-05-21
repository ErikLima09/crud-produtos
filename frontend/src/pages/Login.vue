<template>
  <v-container fluid class="d-flex align-center justify-center" style="height: 100vh;">
    <v-card class="pa-6" max-width="400" width="100%" elevation="10">
      <v-card-title class="text-h5">Login</v-card-title>

      <v-card-text>
        <v-text-field v-model="username" label="Usuário" />
        <v-text-field v-model="password" label="Senha" type="password" />
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="primary" @click="login">Entrar</v-btn>
        <v-btn @click="showDialog = true">Cadastrar</v-btn>
      </v-card-actions>
    </v-card>

    <CadastrarDialog v-model="showDialog"></CadastrarDialog>
  </v-container>
</template>

<script setup lang="ts">

import { useRouter } from 'vue-router'
import { ref } from 'vue'
import axios from 'axios'
import CadastrarDialog from '../components/CadastrarDialog.vue'

const router = useRouter()
const username = ref('')
const password = ref('')
const showDialog = ref(false)

const login = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/login', {
      username: username.value,
      password: password.value
    })

    localStorage.setItem('token', response.data.token)
    router.push('/dashboard')
  } catch (error) {
    alert('Usuário ou senha inválidos')
  }

}

</script>
