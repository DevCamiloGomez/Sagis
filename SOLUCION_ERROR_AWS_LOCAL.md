# 🔧 Solución: Error de Credenciales AWS en Desarrollo Local

## ❌ Problema

```
InvalidArgumentException
Credentials must be an instance of Aws\Credentials\CredentialsInterface...
```

El código intenta usar AWS S3 pero las credenciales no están configuradas en desarrollo local.

## ✅ SOLUCIÓN: Usar Almacenamiento Local

### 1. Actualiza tu `.env` local:

```env
# Cambia esto:
FILESYSTEM_DRIVER=local
```

**Elimina o comenta las variables de AWS** (no son necesarias en local):

```env
# FILESYSTEM_DRIVER=s3
# AWS_ACCESS_KEY_ID=...
# AWS_SECRET_ACCESS_KEY=...
# AWS_DEFAULT_REGION=...
# AWS_BUCKET=...
# AWS_URL=...
```

### 2. Limpia la caché de configuración:

```bash
docker-compose -f docker-compose.local.yml exec app php artisan config:clear
docker-compose -f docker-compose.local.yml exec app php artisan cache:clear
```

### 3. Reinicia el contenedor:

```bash
docker-compose -f docker-compose.local.yml restart app
```

---

## 📝 Configuración .env para Desarrollo Local

```env
APP_NAME=SAGIS
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=root
DB_PASSWORD=password

# Almacenamiento LOCAL (no S3)
FILESYSTEM_DRIVER=local

# Correo (opcional para desarrollo)
MAIL_MAILER=log

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

---

## ✅ Cambios Realizados

He actualizado `config/filesystems.php` para que el disco `people` use almacenamiento local cuando `FILESYSTEM_DRIVER=local`.

---

## 🚀 Después de Cambiar

1. Actualiza `.env` con `FILESYSTEM_DRIVER=local`
2. Limpia caché: `php artisan config:clear`
3. Reinicia: `docker-compose -f docker-compose.local.yml restart app`
4. Accede: `http://localhost:8000`

---

**¡Listo!** Ahora debería funcionar sin errores de AWS.
