<template>
  <v-app>
    <v-app-bar app color="primary" dark> 
      <h4 class="ml-5"> Sistema de gerenciamento de produtos </h4> 
    </v-app-bar>  
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
</script>



<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
