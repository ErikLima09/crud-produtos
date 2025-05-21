# Utiliza imagem oficial do Node
FROM node:20-alpine

# Define diretório de trabalho dentro do container
WORKDIR /app

# Copia apenas os arquivos necessários para instalar dependências
COPY package*.json ./

# Instala dependências
RUN npm ci

# Copia o restante dos arquivos
COPY . .

# Expõe a porta padrão do Vue
EXPOSE 8080

# Comando para rodar a aplicação
CMD ["npm", "run", "dev", "--", "--host"]
