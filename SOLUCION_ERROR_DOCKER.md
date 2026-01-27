# 🔧 Solución: Error de Docker con Node.js

## ❌ Problema

El `docker-compose.yml` usa Laravel Sail que intenta instalar Node.js 16, pero npm 11 requiere Node.js 20+.

## ✅ SOLUCIÓN: Usar docker-compose.local.yml

He creado un archivo `docker-compose.local.yml` simplificado que usa tu `Dockerfile` principal.

### Comando Único:

```bash
docker-compose -f docker-compose.local.yml up -d
```

---

## Después de levantar:

### 1. Instalar dependencias:
```bash
docker-compose -f docker-compose.local.yml exec app composer install
```

### 2. Generar APP_KEY:
```bash
docker-compose -f docker-compose.local.yml exec app php artisan key:generate
```

### 3. Migraciones:
```bash
docker-compose -f docker-compose.local.yml exec app php artisan migrate --seed
```

### 4. Storage link:
```bash
docker-compose -f docker-compose.local.yml exec app php artisan storage:link
```

### 5. Acceder:
```
http://localhost:8000
```

---

## ⚠️ IMPORTANTE: Configurar .env

Asegúrate de que tu `.env` tenga:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## 🚀 Comando TODO-EN-UNO (Primera vez):

```bash
docker-compose -f docker-compose.local.yml up -d && docker-compose -f docker-compose.local.yml exec app composer install && docker-compose -f docker-compose.local.yml exec app php artisan key:generate && docker-compose -f docker-compose.local.yml exec app php artisan migrate --seed && docker-compose -f docker-compose.local.yml exec app php artisan storage:link
```

---

## 📝 Para las siguientes veces:

Solo:
```bash
docker-compose -f docker-compose.local.yml up -d
```

---

**Listo!** Accede a **http://localhost:8000**
