# 📦 Crud Produtos

Aplicação full stack para cadastro e gerenciamento de **produtos** e **categorias**, com autenticação de usuários via **Laravel Sanctum**, interface moderna em **Vue 3 + Vuetify** e estrutura modular separada em `frontend` e `backend`.

---

## 📁 Estrutura do Projeto

Este repositório contém dois diretórios principais:

```
crud-produtos/
├── backend/    # API Laravel + MySQL (Docker)
└── frontend/   # Interface Vue 3 + Vuetify
```

---

## ✨ Funcionalidades

- 🔐 Autenticação de usuários com Laravel Sanctum
- 📦 CRUD completo de produtos
- 🗂️ CRUD completo de categorias
- ⚙️ Backend em Laravel + Docker + MySQL
- 🖥️ Frontend em Vue 3 + TypeScript + Vuetify
- 🔄 Integração via API REST

---

## 🧠 Tecnologias utilizadas

### 🔙 Backend

- PHP 8+
- Laravel
- Laravel Sanctum
- MySQL
- Composer
- Docker

### 🔜 Frontend

- Vue 3
- TypeScript
- Vuetify
- Node.js / npm
- Axios

---

## ▶️ Como executar o projeto

### 📌 Pré-requisitos

- Git
- Docker e Docker Compose
- Node.js (v16+ recomendado)
- Composer

---

### 🐳 1. Subir o backend com Docker

```bash
# Clone o repositório
git clone https://github.com/ErikLima09/crud-produtos.git
cd crud-produtos/backend

# Copie o .env de exemplo e ajuste se necessário
cp .env.example .env

# Instale as dependências do Laravel
composer install

# Gere a chave da aplicação
php artisan key:generate

# Suba os containers
docker-compose up -d

# Rode as migrations dentro do container
docker exec -it backend-1 php artisan migrate
```
---

### 🖥️ 2. Rodar o frontend

```bash
# Acesse o diretório do frontend
cd ../frontend

# Instale as dependências
npm install

# Execute o servidor de desenvolvimento
npm run dev
```

> Certifique-se de que a URL da API esteja corretamente apontada no frontend (em `.env` ou configuração base do Axios).

---

## 🔐 Autenticação

A autenticação é feita via **Laravel Sanctum**, utilizando tokens para proteger as rotas da API. O frontend armazena e utiliza esse token para realizar chamadas autenticadas ao backend.

---

## 📄 Organização das Pastas

```
backend/
├── app/
├── database/
├── routes/
├── docker-compose.yml
└── .env

frontend/
├── src/
├── public/
├── vite.config.ts
└── .env
```

---

## 📌 Contribuição

Sinta-se à vontade para abrir _issues_, sugerir melhorias ou fazer _pull requests_.

---

## 🧑‍💻 Autor

Desenvolvido por **[Erik Lima](https://github.com/ErikLima09)** 🚀
