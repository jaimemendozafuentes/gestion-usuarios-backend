# Backend - Gestión de Usuarios

Este es un proyecto backend desarrollado en **PHP** que permite la gestión de usuarios mediante **JSON Web Tokens (JWT)** para autenticación. Ofrece funcionalidades como registro, inicio de sesión, listado, actualización y eliminación de usuarios.

---

## 🚀 Características

- **Registro de usuario**: Permite registrar usuarios con correo electrónico y contraseña segura.
- **Inicio de sesión con JWT**: Los usuarios pueden iniciar sesión y obtener un token para realizar peticiones autenticadas.
- **Gestión completa de usuarios**: Listar, actualizar y eliminar usuarios autenticados.
- **Protección de rutas**: Rutas protegidas mediante autenticación JWT para garantizar acceso seguro.

---

## 💻 Tecnologías Utilizadas

- **PHP 8.2+**
- **MySQL**: Base de datos relacional para almacenar información de usuarios.
- **JWT**: Autenticación basada en JSON Web Tokens.
- **Composer**: Herramienta para gestionar dependencias en PHP.
- **Dotenv**: Manejo de variables de entorno.

---

## 🔧 Requisitos Previos

1. Tener instalado **PHP 8.2 o superior**.
2. Configurar una base de datos MySQL.
3. Instalar **Composer** para gestionar las dependencias del proyecto.

---

## ⚙️ Instalación y Configuración

Sigue estos pasos para configurar el proyecto:

### 1. Clonar el repositorio
git clone https://github.com/jaimemendozafuentes/gestion-usuarios-backend.git


### 2. Instalar dependencias
cd gestion-usuarios-backend
composer install


### 3. Configurar variables de entorno
Crea un archivo `.env` en la raíz del proyecto y define las siguientes variables:
    DB_HOST=localhost
    DB_NAME=user_management
    DB_USER=root
    DB_PASS=tu_contraseña
    CORS_ORIGIN=https://tu_frontend.com
    JWT_SECRET=tu_clave_secreta


### 4. Crear la base de datos y tabla
Ejecuta los siguientes comandos SQL para configurar la base de datos:

    CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE user_management;

    CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );


### 5. Iniciar servidor de desarrollo
Ejecuta el siguiente comando para iniciar el servidor:
php -S localhost:3000


---

## 🧑‍💻 Endpoints API

### 1. **POST /register**
Registra un nuevo usuario.

#### Datos de entrada:
{
"email": "usuario@ejemplo.com",
"password": "ContraseñaSegura123!"
}


#### Respuesta:
{
"success": true,
"message": "Usuario registrado correctamente",
"token": "JWT_TOKEN"
}


---

### 2. **POST /login**
Inicia sesión y obtiene un JWT.

#### Datos de entrada:
{
"email": "usuario@ejemplo.com",
"password": "ContraseñaSegura123!"
}

#### Respuesta:
{
"success": true,
"message": "Inicio de sesión exitoso",
"token": "JWT_TOKEN"
}


---

### 3. **GET /users**
Obtiene la lista de usuarios (requiere autenticación).

#### Cabeceras:
Authorization: Bearer {JWT_TOKEN}

#### Respuesta:
{
"success": true,
"users": [
{
"id": 1,
"email": "usuario@ejemplo.com",
"created_at": "2025-04-10 12:34:56"
}
]
}


---

### 4. **POST /update**
Actualiza el correo electrónico de un usuario (requiere autenticación).

#### Datos de entrada:
{
"id": 1,
"email": "nuevo@ejemplo.com"
}


#### Respuesta:

{
"success": true,
"message": "Usuario actualizado correctamente"
}


---

### 5. **POST /delete**
Elimina un usuario (requiere autenticación).

#### Datos de entrada:
{
"id": 1
}


#### Respuesta:
{
"success": true,
"message": "Usuario eliminado correctamente"
}


---

## 📄 Notas Adicionales

- Asegúrate de configurar correctamente las variables en el archivo `.env`.
- Las rutas protegidas requieren que envíes el token JWT en las cabeceras como `Authorization: Bearer {JWT_TOKEN}`.

