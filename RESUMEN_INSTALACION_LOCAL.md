# ⚡ Resumen Rápido: Levantar SAGIS Localmente

## 🚀 OPCIÓN RÁPIDO (Laragon)

### 1. Crear Base de Datos

Desde **HeidiSQL** (Laragon):
- Clic derecho → **Crear nueva** → **Base de datos**
- Nombre: `sagis`

### 2. Ejecutar Script Automático

```bash
# Doble clic en:
instalar_local.bat
```

O manualmente:

```bash
# 1. Crear .env
copy .env.example .env

# 2. Instalar dependencias
composer install

# 3. Generar key
php artisan key:generate

# 4. Migraciones
php artisan migrate --seed

# 5. Storage link
php artisan storage:link
```

### 3. Configurar .env

Edita `.env`:
```env
APP_NAME=SAGIS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://sagis.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DRIVER=local
```

### 4. Acceder

```
http://sagis.test
```

---

## 🐳 OPCIÓN DOCKER

```bash
# 1. Crear .env
copy .env.example .env

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

---

**Guía completa:** Ver `GUIA_LEVANTAR_LOCAL.md`
