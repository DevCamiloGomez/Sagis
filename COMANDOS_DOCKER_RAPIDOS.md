# ⚡ Comandos Docker Rápidos para SAGIS

## 🚀 DESARROLLO LOCAL (Docker Compose)

### Inicio Rápido

```bash
# 1. Crear .env
cp .env.example .env

# 2. Levantar contenedores
docker-compose up -d

# 3. Instalar dependencias
docker-compose exec laravel.test composer install

# 4. Generar APP_KEY
docker-compose exec laravel.test php artisan key:generate

# 5. Migraciones y seeders
docker-compose exec laravel.test php artisan migrate --seed

# 6. Crear enlace de storage
docker-compose exec laravel.test php artisan storage:link

# 7. Acceder
# http://localhost
```

### Comandos Útiles

```bash
# Ver logs
docker-compose logs -f

# Detener
docker-compose down

# Reiniciar
docker-compose restart

# Acceder al contenedor
docker-compose exec laravel.test bash

# Ejecutar Artisan
docker-compose exec laravel.test php artisan [comando]
```

---

## 🌐 PRODUCCIÓN (Render)

### Ya está Desplegado

Tu aplicación ya está en: **https://sagisufps.onrender.com**

### Comandos desde Render Shell

```bash
# Generar APP_KEY
php artisan key:generate

# Migraciones
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar cache
php artisan cache:clear
```

---

## 📋 CONFIGURACIÓN .env LOCAL

```env
APP_NAME=SAGIS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sail
DB_PASSWORD=password

FILESYSTEM_DRIVER=local
```

---

**Guía completa:** Ver `GUIA_DOCKER.md`
