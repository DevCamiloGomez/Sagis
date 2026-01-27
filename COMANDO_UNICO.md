# ⚡ UN SOLO COMANDO

## 🐳 Con Docker (Recomendado)

```bash
docker-compose up -d
```

**Eso es todo.** Esto levanta:
- ✅ PHP + Laravel
- ✅ MySQL
- ✅ Redis
- ✅ Nginx

Luego accede a: **http://localhost**

---

## ⚠️ IMPORTANTE

Antes de ejecutar, asegúrate de tener:

1. **Archivo .env configurado:**
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sail
DB_PASSWORD=password
```

2. **Docker Desktop corriendo**

---

## 📝 Después del primer `docker-compose up -d`

Ejecuta estos comandos una sola vez:

```bash
docker-compose exec laravel.test composer install
docker-compose exec laravel.test php artisan key:generate
docker-compose exec laravel.test php artisan migrate --seed
docker-compose exec laravel.test php artisan storage:link
```

---

## 🚀 Comando TODO-EN-UNO (Primera vez)

```bash
docker-compose up -d && docker-compose exec laravel.test composer install && docker-compose exec laravel.test php artisan key:generate && docker-compose exec laravel.test php artisan migrate --seed && docker-compose exec laravel.test php artisan storage:link
```

**Copia y pega todo eso en una sola línea.**

---

## ✅ Comandos Siguientes

Solo necesitas:
```bash
docker-compose up -d
```

Y listo. Todo funcionando en **http://localhost**
