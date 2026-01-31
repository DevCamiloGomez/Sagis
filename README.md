<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# SAGIS - Sistema de Administración y Gestión de Información de Seguimiento

**SAGIS** es un sistema web integral desarrollado para la Universidad Francisco de Paula Santander (UFPS) con el objetivo de gestionar y fortalecer el vínculo entre la institución, sus graduados y el sector empresarial.

## 📋 Descripción General
La plataforma facilita el seguimiento de la trayectoria profesional de los egresados, administra información académica y gestiona ofertas laborales y convenios empresariales. Su diseño modular y escalable permite adaptarse a las necesidades cambiantes de la gestión universitaria.

### Módulos Principales
1.  **Gestión de Graduados**: Hoja de vida, historial académico y laboral.
2.  **Módulo Administrativo**: Control total de usuarios, roles, reportes y configuraciones.
3.  **Gestión Empresarial**: Registro de empresas y vinculación laboral.
4.  **Sistema de Publicaciones**: Noticias, eventos y comunicados oficiales.
5.  **Reportes y Estadísticas**: Tableros interactivos (Dashboard) y exportación de datos (PDF/Excel) para la toma de decisiones.

---

## 🛠️ Stack Tecnológico

El proyecto está construido sobre una arquitectura **MVC** sólida, utilizando tecnologías modernas y estándares de la industria:

*   **Backend**: 
    *   [Laravel 8](https://laravel.com/) (PHP Framework)
    *   MySQL 8.0 (Base de datos relacional)
*   **Frontend**: 
    *   Blade Templates
    *   Bootstrap 4 + AdminLTE 3 (Interfaz administrativa)
    *   Chart.js (Visualización de datos)
*   **Infraestructura**: 
    *   Docker & Docker Compose (Contenerización)
    *   Nginx (Servidor Web)

---

## 🚀 Despliegue con Docker (Recomendado)

El proyecto ha sido optimizado para un despliegue rápido y consistente mediante **Docker**. Se incluye una configuración de "Un único Docker" que empaqueta la aplicación (Nginx + PHP-FPM) lista para producción.

### Requisitos Previos
*   Docker y Docker Compose instalados en el servidor o máquina local.

### Pasos para Desplegar

1.  **Clonar el Repositorio:**
    ```bash
    git clone https://github.com/JarlinFonseca/SAGIS.git
    cd SAGIS
    ```

2.  **Configurar Variables de Entorno:**
    Crea el archivo `.env` basado en el ejemplo:
    ```bash
    cp .env.example .env
    ```
    *Asegúrate de configurar las credenciales de base de datos en el `.env` para que coincidan con el `docker-compose.yml` (por defecto user: `root`, pass: `secret`).*

3.  **Construir y Levantar Contenedores:**
    Ejecuta el siguiente comando para iniciar la aplicación y la base de datos:
    ```bash
    docker-compose up -d --build
    ```
    *Este comando construirá la imagen personalizada de SAGIS (optimizada con Nginx y PHP) y levantará un contenedor de MySQL.*

4.  **Inicialización Automática:**
    El contenedor de la aplicación incluye un script (`start.sh`) que automáticamente:
    *   Instala dependencias y optimiza caché.
    *   Ejecuta migraciones de base de datos.
    *   Genera las llaves de seguridad.
    *   Inicia los servicios web.

5.  **Acceder al Sistema:**
    Una vez iniciados los contenedores, accede a través de tu navegador:
    *   **URL**: `http://localhost`

---

## 👥 Autores
Proyecto desarrollado por estudiantes de Ingeniería de Sistemas de la **Universidad Francisco de Paula Santander**:

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


