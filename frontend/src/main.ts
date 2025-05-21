import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import vuetify from './plugins/vuetify.ts'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import router from './router'
import pinia from './store/index.ts'


const app = createApp(App)
app.use(vuetify)
app.use(router)
app.mount('#app')
app.use(pinia)


