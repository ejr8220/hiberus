# Proyecto Symfony + React + PostgreSQL

Este proyecto implementa un backend en **Symfony** y un frontend en **React**, conectados a una base de datos **PostgreSQL**.  
Incluye endpoints para gestionar
 productos(`/products`) get consulta de productos, post crear productos
 órdenes (`/orders`) get {Id} consultar una orden, post crear una orden y checkout simula el pago 

---

## 🚀 Requisitos

- Docker y Docker Compose instalados
- Node.js (>= 18) y npm/yarn (solo si quieres correr el frontend fuera de Docker)

---

## 📦 Instalación

1. **Copiar los archivos en una carpeta de preferencia hiberus o clonar el repositorio **
   
## Backend
desde la terminal nos ubicamos en la carpeta raiz del proyecto y digitamos

cd backend

levantamos los contenedores backend y db

docker compose up -d

ejecutamos pruebas unitarias

docker compose exec backend vendor/bin/phpunit --testdox --colors=always

## Frontend
desde la terminal nos ubicamos en la carpeta raiz del proyecto y digitamos

cd frontend

instalamos dependencias

npm install --legacy-peer-deps

ejecutamos la aplicación

npm start


## Usuario
Para ingresar al sistema se tienen 2 usuarios Admin clave Admin y Cliente clave Cliente

##Admin 
Catálogo, permite crear productos

##Cliente
Catálogo, en el listado del productos aparece la opción de agregar al carrito

Carrito, puede eliminar los items seleccionado o generar la orden

Pedidos, permite obtener un pedido por el id y si esta en estado PENDING se puede hacer el pago

##Postman
El proyecto consta con una collection postman para probar la api se llama collection.json en la raiz del proyecto




