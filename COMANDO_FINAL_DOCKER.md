# ✅ Comando Final para Docker

## 🚀 UN SOLO COMANDO:

```bash
docker-compose -f docker-compose.local.yml up -d
```

---

## ⚠️ IMPORTANTE: Puerto MySQL Cambiado

El puerto MySQL ahora es **3307** (no 3306) para evitar conflictos con Laragon.

### Actualiza tu `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sail
DB_PASSWORD=password
```

**Nota:** El `DB_PORT` en `.env` sigue siendo `3306` porque es el puerto interno del contenedor. El puerto externo (3307) solo se usa si quieres conectarte desde fuera de Docker.

---

## 📋 Después de levantar (Primera vez):

```bash
# 1. Instalar dependencias
docker-compose -f docker-compose.local.yml exec app composer install

# 2. Generar APP_KEY
docker-compose -f docker-compose.local.yml exec app php artisan key:generate

# 3. Migraciones
docker-compose -f docker-compose.local.yml exec app php artisan migrate --seed

# 4. Storage link
docker-compose -f docker-compose.local.yml exec app php artisan storage:link
```

---

## 🌐 Acceder:

```
http://localhost:8000
```

---

## 🛑 Detener:

```bash
docker-compose -f docker-compose.local.yml down
```

---

**¡Listo!** El puerto 3307 evita conflictos con Laragon.
