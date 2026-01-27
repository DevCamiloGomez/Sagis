# 🚀 Configuración Final: Render + Railway

## 📋 Tu Configuración Actual

- **Aplicación Web:** Render (`https://sagisufps.onrender.com`)
- **Base de Datos:** Railway (MySQL)
- **Conexión:** Externa (desde Render hacia Railway)

---

## ✅ CONFIGURACIÓN COMPLETA PARA RENDER

### Variables de Entorno en Render Dashboard

Ve a tu servicio en [Render Dashboard](https://dashboard.render.com/) → **Environment** y agrega:

```env
# ============================================
# APLICACIÓN
# ============================================
APP_NAME=SAGIS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sagisufps.onrender.com

# ============================================
# BASE DE DATOS (Railway - Conexión Externa)
# ============================================
DB_CONNECTION=mysql
DB_HOST=hopper.proxy.rlwy.net
DB_PORT=29406
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh

# ============================================
# ALMACENAMIENTO (AWS S3)
# ============================================
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=tu_access_key_aqui
AWS_SECRET_ACCESS_KEY=tu_secret_key_aqui
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=nombre-del-bucket
AWS_URL=https://bucket.s3.region.amazonaws.com

# ============================================
# CORREO (SendGrid o SMTP)
# ============================================
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=tu_api_key_aqui
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"

# ============================================
# CACHE Y SESIONES
# ============================================
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# ============================================
# OPCIONALES
# ============================================
GEONAMES_USERNAME=camilogomez666
```

---

## 🔑 VARIABLES CRÍTICAS

### 1. APP_URL
```
APP_URL=https://sagisufps.onrender.com
```
✅ **Ya confirmado**

### 2. Base de Datos (Railway - Externa)
```
DB_CONNECTION=mysql
DB_HOST=hopper.proxy.rlwy.net
DB_PORT=29406
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```
✅ **Usa conexión EXTERNA porque la app está en Render**

### 3. APP_KEY
**⚠️ IMPORTANTE:** Debes generar una clave única:

**Opción A: Desde Render (SSH)**
1. Render Dashboard → Tu servicio → **Shell**
2. Ejecuta: `php artisan key:generate`
3. La clave se agregará automáticamente

**Opción B: Localmente**
1. En tu máquina local: `php artisan key:generate`
2. Copia el valor de `APP_KEY` del `.env` local
3. Pégalo en Render Dashboard → Environment

---

## 📝 PASO A PASO EN RENDER

### Paso 1: Ir a Environment Variables

1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Selecciona tu servicio `sagis` (o el nombre que tenga)
3. Clic en **Environment** (menú lateral izquierdo)

### Paso 2: Agregar Variables

Para cada variable:
1. Clic en **Add Environment Variable**
2. **Key:** `APP_NAME`
3. **Value:** `SAGIS`
4. Clic en **Save Changes**
5. Repite para cada variable

### Paso 3: Variables de Base de Datos

Agrega estas 6 variables:

```
DB_CONNECTION = mysql
DB_HOST = hopper.proxy.rlwy.net
DB_PORT = 29406
DB_DATABASE = railway
DB_USERNAME = root
DB_PASSWORD = pPeXTqVRuhmoxIBlqxinHIYbTJdXbxbh
```

### Paso 4: Generar APP_KEY

1. Clic en **Shell** (en el menú de tu servicio)
2. Ejecuta: `php artisan key:generate`
3. Verás: `Application key set successfully.`
4. La clave se agregará automáticamente a las variables de entorno

---

## 🔍 VERIFICAR CONFIGURACIÓN

### 1. Verificar Variables en Render

Render Dashboard → Tu servicio → **Environment**

Debes ver todas las variables listadas.

### 2. Verificar Conexión a Base de Datos

Render Dashboard → Tu servicio → **Shell**

```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

Si funciona, verás información del PDO.

### 3. Verificar APP_KEY

```bash
php artisan config:show app.key
```

Debe mostrar una clave (no vacía).

---

## ⚠️ IMPORTANTE

### Conexión Externa de Railway

Como tu aplicación está en **Render** y tu base de datos en **Railway**, debes usar:

- ✅ **Host:** `hopper.proxy.rlwy.net` (conexión externa)
- ✅ **Port:** `29406` (puerto externo)
- ❌ **NO uses:** `mysql.railway.internal:3306` (solo funciona dentro de Railway)

### Seguridad

- Las contraseñas en Render se encriptan automáticamente
- No compartas las credenciales públicamente
- Railway puede rotar las contraseñas, verifica periódicamente

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Causa:** Host o puerto incorrectos  
**Solución:** Verifica que uses `hopper.proxy.rlwy.net:29406`

### Error: "Access denied for user"

**Causa:** Usuario o contraseña incorrectos  
**Solución:** Verifica las credenciales en Railway Dashboard

### Error: "Unknown database 'railway'"

**Causa:** La base de datos no existe o el nombre es incorrecto  
**Solución:** Verifica en Railway que la BD se llama `railway`

### APP_KEY vacío o error de encriptación

**Solución:** Ejecuta `php artisan key:generate` desde Render Shell

---

## ✅ CHECKLIST FINAL

- [ ] `APP_URL=https://sagisufps.onrender.com` configurado
- [ ] `APP_KEY` generado (`php artisan key:generate`)
- [ ] Variables de base de datos configuradas (conexión externa)
- [ ] `DB_HOST=hopper.proxy.rlwy.net` (no el interno)
- [ ] `DB_PORT=29406` (no 3306)
- [ ] `DB_PASSWORD` copiada correctamente
- [ ] Variables de AWS configuradas (si usas S3)
- [ ] Variables de correo configuradas
- [ ] Aplicación desplegada y funcionando

---

## 📞 ENLACES ÚTILES

- **Render Dashboard:** https://dashboard.render.com/
- **Tu Aplicación:** https://sagisufps.onrender.com
- **Railway Dashboard:** https://railway.app/

---

**¡Listo!** Con esta configuración, tu aplicación en Render se conectará correctamente a la base de datos en Railway.
