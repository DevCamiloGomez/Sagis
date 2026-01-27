# 🚂 Configuración de Base de Datos Railway para SAGIS

## 📋 Credenciales de Railway

Basado en las variables que proporcionaste, aquí está la configuración correcta:

## 🔧 CONFIGURACIÓN PARA .env

### Si tu aplicación está en Railway (mismo proyecto) - Usa conexión INTERNA:

```env
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

### Si tu aplicación está en otro servicio (Render, etc.) - Usa conexión EXTERNA:

```env
DB_CONNECTION=mysql
DB_HOST=hopper.proxy.rlwy.net
DB_PORT=29406
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

---

## 📝 EXPLICACIÓN DE LAS VARIABLES

| Variable Railway | Variable Laravel (.env) | Valor |
|------------------|-------------------------|-------|
| `MYSQLHOST` (interno) | `DB_HOST` | `mysql.railway.internal` |
| `MYSQLHOST` (externo) | `DB_HOST` | `hopper.proxy.rlwy.net` |
| `MYSQLPORT` (interno) | `DB_PORT` | `3306` |
| `MYSQLPORT` (externo) | `DB_PORT` | `29406` |
| `MYSQLDATABASE` | `DB_DATABASE` | `railway` |
| `MYSQLUSER` | `DB_USERNAME` | `root` |
| `MYSQLPASSWORD` | `DB_PASSWORD` | `pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh` |

---

## ✅ CONFIGURACIÓN COMPLETA PARA RAILWAY

### Opción 1: Aplicación en Railway (Recomendado)

```env
# ============================================
# APLICACIÓN
# ============================================
APP_NAME=SAGIS
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://tu-app.railway.app

# ============================================
# BASE DE DATOS (Railway - Conexión Interna)
# ============================================
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh

# ============================================
# ALMACENAMIENTO (AWS S3)
# ============================================
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=tu_access_key
AWS_SECRET_ACCESS_KEY=tu_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=nombre-del-bucket
AWS_URL=https://bucket.s3.region.amazonaws.com

# ============================================
# CORREO
# ============================================
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=tu_api_key
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"

# ============================================
# CACHE Y SESIONES
# ============================================
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

### Opción 2: Aplicación en otro servicio (Render, etc.)

```env
# ============================================
# BASE DE DATOS (Railway - Conexión Externa)
# ============================================
DB_CONNECTION=mysql
DB_HOST=hopper.proxy.rlwy.net
DB_PORT=29406
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

---

## 🚀 CONFIGURAR EN RAILWAY

### Paso 1: Agregar Variables de Entorno en Railway

1. Ve a tu proyecto en [Railway Dashboard](https://railway.app/)
2. Selecciona tu servicio (aplicación web)
3. Clic en **Variables** (pestaña)
4. Agrega las siguientes variables:

#### Variables Básicas:
```
APP_NAME=SAGIS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app.railway.app
```

#### Variables de Base de Datos (si NO están automáticas):
```
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

**Nota:** Railway puede inyectar automáticamente las variables `MYSQL*` como `DB_*`. Verifica si ya están disponibles.

---

## 🔍 VERIFICAR CONEXIÓN

### Desde Railway (SSH):

```bash
# Conectarte al servicio
railway connect

# Probar conexión
php artisan tinker
>>> DB::connection()->getPdo();
```

### Desde tu máquina local (conexión externa):

```bash
mysql -h hopper.proxy.rlwy.net -P 29406 -u root -p
# Contraseña: pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

---

## ⚠️ IMPORTANTE

1. **Conexión Interna vs Externa:**
   - **Interna** (`mysql.railway.internal:3306`): Más rápida, solo funciona dentro de Railway
   - **Externa** (`hopper.proxy.rlwy.net:29406`): Funciona desde cualquier lugar

2. **Seguridad:**
   - La contraseña es sensible, no la compartas
   - Railway la encripta automáticamente en las variables de entorno

3. **Nombre de Base de Datos:**
   - Railway crea la BD con el nombre `railway` por defecto
   - Si quieres cambiarlo, puedes crear una nueva BD o renombrarla

---

## 📝 EJEMPLO DE .env COMPLETO PARA RAILWAY

```env
APP_NAME=SAGIS
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://sagis-production.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh

FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=sagis-ufps
AWS_URL=https://sagis-ufps.s3.us-east-1.amazonaws.com

MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

GEONAMES_USERNAME=camilogomez666
```

---

## ✅ CHECKLIST

- [ ] Variables de base de datos configuradas en Railway
- [ ] `DB_HOST` correcto (interno o externo según tu caso)
- [ ] `DB_PASSWORD` copiada correctamente
- [ ] `APP_KEY` generado (`php artisan key:generate`)
- [ ] `APP_URL` con la URL de Railway
- [ ] Variables de AWS configuradas (si usas S3)
- [ ] Variables de correo configuradas

---

**¿Necesitas ayuda con algo más?** Indica qué necesitas configurar.
