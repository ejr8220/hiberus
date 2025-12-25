# Etapa de build: compila el frontend
FROM node:20 AS build
WORKDIR /app
COPY package*.json ./
RUN npm install --legacy-peer-deps
COPY . .
RUN npm run build

# Etapa de runtime: usa Node para servir el build
FROM node:20-alpine
WORKDIR /app

# Instala un servidor estático ligero
RUN npm install -g serve

# Copia el build generado
COPY --from=build /app/dist ./dist

# Expone el puerto
EXPOSE 3000

# Comando de arranque
CMD ["serve", "-s", "dist", "-l", "3000"]