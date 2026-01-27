# 🐳 Guía Completa: Levantar SAGIS con Docker

## 📋 ÍNDICE

1. [Desarrollo Local con Docker Compose](#1-desarrollo-local-con-docker-compose)
2. [Producción con Docker (Render)](#2-producción-con-docker-render)
3. [Comandos Útiles](#3-comandos-útiles)
4. [Solución de Problemas](#4-solución-de-problemas)

---

## 1. DESARROLLO LOCAL CON DOCKER COMPOSE

### Requisitos Previos

- ✅ Docker Desktop instalado ([Descargar](https://www.docker.com/products/docker-desktop))
- ✅ Docker Compose instalado (viene con Docker Desktop)
- ✅ Git instalado

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/tu-repo/SAGIS.git
cd SAGIS
```

### Paso 2: Crear Archivo .env

```bash
# Copiar el archivo de ejemplo
cp .env.example .env

# Editar el .env con tus configuraciones locales
```

### Paso 3: Configurar .env para Desarrollo Local

Edita el archivo `.env` con estas configuraciones:

```env
APP_NAME=SAGIS
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sail
DB_PASSWORD=password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Para desarrollo local, puedes usar 'local' en lugar de S3
FILESYSTEM_DRIVER=local

# Correo (opcional para desarrollo)
MAIL_MAILER=log
```

### Paso 4: Levantar los Contenedores

```bash
# Opción A: Con Laravel Sail (recomendado)
./vendor/bin/sail up -d

# Opción B: Con Docker Compose directamente
docker-compose up -d
```

### Paso 5: Instalar Dependencias

```bash
# Con Sail
./vendor/bin/sail composer install

# O con Docker Compose
docker-compose exec laravel.test composer install
```

### Paso 6: Generar APP_KEY

```bash
# Con Sail
./vendor/bin/sail artisan key:generate

# O con Docker Compose
docker-compose exec laravel.test php artisan key:generate
```

### Paso 7: Ejecutar Migraciones y Seeders

```bash
# Con Sail
./vendor/bin/sail artisan migrate --seed

# O con Docker Compose
docker-compose exec laravel.test php artisan migrate --seed
```

### Paso 8: Crear Enlace Simbólico de Storage

```bash
# Con Sail
./vendor/bin/sail artisan storage:link

# O con Docker Compose
docker-compose exec laravel.test php artisan storage:link
```

### Paso 9: Acceder a la Aplicación

Abre tu navegador en:
```
http://localhost
```

---

## 2. PRODUCCIÓN CON DOCKER (RENDER)

### Tu Configuración Actual

- ✅ **Dockerfile** configurado
- ✅ **render.yaml** configurado
- ✅ **start.sh** configurado
- ✅ **nginx.conf** configurado

### Render ya está Configurado

Tu proyecto ya está desplegado en Render usando Docker. Solo necesitas:

### Paso 1: Verificar Variables de Entorno en Render

1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Selecciona tu servicio `sagis`
3. Clic en **Environment**
4. Verifica que todas las variables estén configuradas (ver `RESUMEN_COMPLETO_VARIABLES.md`)

### Paso 2: Generar APP_KEY (si no lo has hecho)

1. Render Dashboard → Tu servicio → **Shell**
2. Ejecuta:
```bash
php artisan key:generate
```

### Paso 3: Ejecutar Migraciones (si es necesario)

```bash
php artisan migrate --force
```

### Paso 4: Verificar Despliegue

Tu aplicación está disponible en:
```
https://sagisufps.onrender.com
```

---

## 3. COMANDOS ÚTILES

### Desarrollo Local con Sail

```bash
# Levantar contenedores
./vendor/bin/sail up -d

# Detener contenedores
./vendor/bin/sail down

# Ver logs
./vendor/bin/sail logs

# Ejecutar comandos Artisan
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan tinker
./vendor/bin/sail artisan cache:clear

# Acceder al contenedor
./vendor/bin/sail shell

# Instalar dependencias
./vendor/bin/sail composer install
./vendor/bin/sail composer update

# Ejecutar tests
./vendor/bin/sail artisan test
```

### Desarrollo Local con Docker Compose

```bash
# Levantar contenedores
docker-compose up -d

# Detener contenedores
docker-compose down

# Ver logs
docker-compose logs -f

# Ejecutar comandos Artisan
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan tinker

# Acceder al contenedor
docker-compose exec laravel.test bash

# Reconstruir contenedores
docker-compose build
docker-compose up -d
```

### Producción en Render

```bash
# Desde Render Shell
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
php artisan storage:link
```

---

## 4. SOLUCIÓN DE PROBLEMAS

### Error: "Docker daemon is not running"

**Solución:**
1. Abre Docker Desktop
2. Espera a que inicie completamente
3. Vuelve a intentar

### Error: "Port 80 is already in use"

**Solución:**
1. Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - '8080:80'  # Usa el puerto 8080 en lugar de 80
```
2. Accede a `http://localhost:8080`

### Error: "Connection refused" a la base de datos

**Solución:**
1. Verifica que el contenedor MySQL esté corriendo:
```bash
docker-compose ps
```
2. Verifica las variables de entorno en `.env`
3. Espera unos segundos a que MySQL inicie completamente

### Error: "Permission denied" en storage

**Solución:**
```bash
# Con Sail
./vendor/bin/sail artisan storage:link
./vendor/bin/sail exec laravel.test chmod -R 775 storage bootstrap/cache

# O con Docker Compose
docker-compose exec laravel.test php artisan storage:link
docker-compose exec laravel.test chmod -R 775 storage bootstrap/cache
```

### Error: "APP_KEY is not set"

**Solución:**
```bash
# Con Sail
./vendor/bin/sail artisan key:generate

# O con Docker Compose
docker-compose exec laravel.test php artisan key:generate
```

### Limpiar Todo y Empezar de Nuevo

```bash
# Detener y eliminar contenedores, volúmenes y redes
docker-compose down -v

# Eliminar imágenes
docker-compose down --rmi all

# Limpiar todo Docker (cuidado: elimina todo)
docker system prune -a --volumes
```

---

## 📝 ESTRUCTURA DE ARCHIVOS DOCKER

```
SAGIS/
├── Dockerfile              # Imagen Docker para producción
├── docker-compose.yml      # Configuración para desarrollo local
├── start.sh                # Script de inicio para producción
├── nginx.conf              # Configuración de Nginx
└── render.yaml            # Configuración para Render
```

---

## ✅ CHECKLIST DE DESARROLLO LOCAL

- [ ] Docker Desktop instalado y corriendo
- [ ] Repositorio clonado
- [ ] Archivo `.env` creado y configurado
- [ ] Contenedores levantados (`docker-compose up -d`)
- [ ] Dependencias instaladas (`composer install`)
- [ ] `APP_KEY` generado
- [ ] Migraciones ejecutadas
- [ ] Enlace simbólico de storage creado
- [ ] Aplicación accesible en `http://localhost`

---

## ✅ CHECKLIST DE PRODUCCIÓN (RENDER)

- [ ] Variables de entorno configuradas en Render
- [ ] `APP_KEY` generado
- [ ] Migraciones ejecutadas
- [ ] Aplicación desplegada y funcionando
- [ ] URL accesible: `https://sagisufps.onrender.com`

---

## 🚀 COMANDOS RÁPIDOS

### Desarrollo Local (Todo en Uno)

```bash
# 1. Crear .env
cp .env.example .env

# 2. Levantar contenedores
docker-compose up -d

# 3. Instalar dependencias
docker-compose exec laravel.test composer install

# 4. Generar key
docker-compose exec laravel.test php artisan key:generate

# 5. Migraciones
docker-compose exec laravel.test php artisan migrate --seed

# 6. Storage link
docker-compose exec laravel.test php artisan storage:link

# 7. Acceder
# http://localhost
```

### Producción (Render)

```bash
# Desde Render Shell
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📚 DOCUMENTOS RELACIONADOS

- `RESUMEN_COMPLETO_VARIABLES.md` - Variables de entorno
- `CONFIGURACION_FINAL_RENDER_RAILWAY.md` - Configuración Render + Railway
- `CONFIGURACION_AWS_S3.md` - Configuración AWS S3
- `CONFIGURACION_CORREO_GMAIL.md` - Configuración de correo

---

**¿Necesitas ayuda con algo específico?** Indica el error o el paso donde te quedaste.
