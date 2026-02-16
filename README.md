<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400">
  </a>
</p>

# SAGIS - Sistema de Administración y Gestión de Información de Seguimiento

[![Laravel Version](https://img.shields.io/badge/Laravel-v8.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v7.4%2F8.x-777bb4.svg)](https://www.php.net/)
[![PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL-336791.svg)](https://www.postgresql.org/)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ed.svg)](https://www.docker.com/)

**SAGIS** es una plataforma web integral diseñada para la **Universidad Francisco de Paula Santander (UFPS)**. Su propósito es optimizar la gestión y el seguimiento de los graduados, fortaleciendo el vínculo institucional con sus egresados y el sector empresarial.

---

## 🚀 Características Principales

- **🎓 Gestión de Graduados**: Perfiles detallados, historial académico y trayectorias laborales con cumplimiento de normativa **Habeas Data**.
- **💼 Vinculación Empresarial**: Módulo para la gestión de convenios con empresas y seguimiento laboral.
- **📊 Dashboard Estadístico**: Visualización de datos en tiempo real y generación de reportes en PDF y Excel para la toma de decisiones.
- **📧 Comunicaciones Automáticas**: Sistema de envío masivo de correos electrónicos procesados de forma **asíncrona** (Queues) para alta disponibilidad.
- **🖼️ Gestión de Contenido**: Administración dinámica de carruseles (Principal y Secciones) y noticias institucionales.

---

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 8.x (PHP 7.4/8.x)
- **Base de Datos**: PostgreSQL
- **Frontend**: Blade Templates, Bootstrap 4, AdminLTE 3 y Chart.js
- **Infraestructura**: Docker & Docker Compose, Nginx.
- **Servicios**: Almacenamiento en S3 (AWS/MinIO) para activos digitales.

---

## 📦 Instalación y Despliegue (Docker)

Esta es la forma recomendada para levantar el proyecto en entornos de desarrollo y producción de manera consistente.

### 1. Clonar el repositorio
```bash
git clone https://github.com/DevCamiloGomez/Sagis.git
cd Sagis
```

### 2. Configurar el entorno
```bash
cp .env.example .env
```
> [!IMPORTANT]
> Asegúrate de revisar las credenciales en el archivo `.env`. El sistema espera una conexión a **PostgreSQL** según lo definido en el archivo `docker-compose.yml`.

### 3. Levantar contenedores
```bash
docker-compose up -d --build
```

### 4. Inicialización de Colas (Workers)
Para que el sistema de envío masivo de correos funcione, es necesario tener activo un worker encargado de procesar las tareas en segundo plano:
```bash
docker exec -d sagis_app php artisan queue:work
```

---

## 🛡️ Seguridad y Privacidad
El sistema incorpora un módulo de **Aceptación de Política de Datos (Habeas Data)**. Todos los graduados deben aceptar los términos antes de acceder a la plataforma, cumpliendo con la legislación colombiana vigente.

---

## 👥 Equipo de Desarrollo (UFPS)

*   **Jarlin Andres Fonseca Bermón**
*   **Junior Yoel Castilla Osorio**
*   **Manuel Felipe Mora Espitia**
*   **Camilo Alonso Gomez Castellanos**
*   **Fabian Steven Reyes Gonzales**

---

<p align="center">
    <b>Universidad Francisco de Paula Santander</b><br>
    San José de Cúcuta, Colombia
</p>
