# 📋 Resumen Rápido: Variables .env para SAGIS

## 🎯 VARIABLES OBLIGATORIAS (Mínimas para que funcione)

### 1️⃣ APLICACIÓN (Laravel)
```
APP_NAME=SAGIS
APP_ENV=production
APP_KEY=                    ← Generar con: php artisan key:generate
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com
```

**¿Dónde obtener?**
- `APP_KEY`: Ejecuta `php artisan key:generate` (local o en Render)
- `APP_URL`: Render Dashboard → Tu servicio → URL pública

---

### 2️⃣ BASE DE DATOS (MySQL en Render)

```
DB_CONNECTION=mysql
DB_HOST=                    ← Render Dashboard → BD → Internal Database URL
DB_PORT=3306
DB_DATABASE=sagis          ← Lo defines al crear la BD
DB_USERNAME=               ← Render Dashboard → BD → User
DB_PASSWORD=               ← Render Dashboard → BD → Password
```

**¿Dónde obtener? (Render)**
1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Clic en tu base de datos MySQL
3. En la sección **Connections** verás:
   - **Internal Database URL:** `mysql://user:password@host:port/database`
   - Extrae cada parte:
     - `user` = `DB_USERNAME`
     - `password` = `DB_PASSWORD`
     - `host` = `DB_HOST`
     - `port` = `DB_PORT` (generalmente 3306)
     - `database` = `DB_DATABASE`

---

### 3️⃣ ALMACENAMIENTO (AWS S3 - OPCIONAL pero recomendado)

```
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=         ← AWS Console → IAM → Create Access Key
AWS_SECRET_ACCESS_KEY=     ← AWS Console → IAM → Create Access Key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=                ← AWS S3 → Create Bucket
AWS_URL=                   ← https://bucket.s3.region.amazonaws.com
```

**¿Dónde obtener? (AWS)**
1. **Access Keys:**
   - [AWS Console](https://console.aws.amazon.com/) → IAM → Users → Tu usuario
   - Security credentials → Create access key
   - Copia Access Key ID y Secret Access Key

2. **Bucket:**
   - [AWS S3 Console](https://s3.console.aws.amazon.com/)
   - Create bucket → Nombre: `sagis-ufps` → Create
   - Copia el nombre del bucket

3. **Region:**
   - En la lista de buckets, ve la columna "Region"
   - Copia el código (ej: `us-east-1`)

4. **URL:**
   - Formato: `https://{bucket}.s3.{region}.amazonaws.com`
   - Ejemplo: `https://sagis-ufps.s3.us-east-1.amazonaws.com`

**⚠️ Si NO usas S3:**
```
FILESYSTEM_DRIVER=local
```
(No necesitas las variables AWS)

---

### 4️⃣ CORREO ELECTRÓNICO

#### OPCIÓN A: SendGrid (Recomendado)
```
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=          ← SendGrid Dashboard → Settings → API Keys
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"
```

**¿Dónde obtener SendGrid?**
1. [SendGrid](https://sendgrid.com/) → Crear cuenta (gratis)
2. Settings → API Keys → Create API Key
3. Nombre: `SAGIS Production`
4. Permisos: **Mail Send** o **Full Access**
5. **⚠️ Copia la clave inmediatamente** (solo se muestra una vez)

#### OPCIÓN B: SMTP (Gmail)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=            ← Contraseña de aplicación (no la normal)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="SAGIS - UFPS"
```

**¿Dónde obtener contraseña de Gmail?**
1. Google Account → Seguridad
2. Verificación en 2 pasos (debe estar activada)
3. Contraseñas de aplicaciones
4. Generar nueva → "Correo"
5. Usa esa contraseña de 16 caracteres

---

### 5️⃣ OPCIONALES (Tienen valores por defecto)

```
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
GEONAMES_USERNAME=camilogomez666
```

---

## 🚀 CONFIGURACIÓN EN RENDER (Paso a Paso)

### Paso 1: Crear Base de Datos

1. [Render Dashboard](https://dashboard.render.com/) → **New +** → **MySQL**
2. Configura:
   - **Name:** `sagis-db`
   - **Database:** `sagis`
   - **Region:** Elige la más cercana
3. **Create Database**
4. Espera a que se cree (2-5 minutos)
5. Una vez creada, verás la **Internal Database URL**

### Paso 2: Agregar Variables en Render

1. Ve a tu servicio web en Render
2. Clic en **Environment** (menú lateral)
3. Clic en **Add Environment Variable**
4. Agrega cada variable una por una:

**Ejemplo:**
- Key: `APP_NAME`
- Value: `SAGIS`
- Clic en **Save Changes**

Repite para cada variable.

### Paso 3: Variables Sensibles

Para variables como contraseñas y keys:
1. Marca como **Secret** (Render lo oculta en la UI)
2. Render las encripta automáticamente

---

## 📝 PLANTILLA COMPLETA .env

```env
# ============================================
# APLICACIÓN
# ============================================
APP_NAME=SAGIS
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://sagis.onrender.com

# ============================================
# BASE DE DATOS
# ============================================
DB_CONNECTION=mysql
DB_HOST=mysql.xxxxx.render.com
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sagis_user_xxxxx
DB_PASSWORD=contraseña_de_render

# ============================================
# ALMACENAMIENTO (AWS S3)
# ============================================
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=sagis-ufps
AWS_URL=https://sagis-ufps.s3.us-east-1.amazonaws.com

# ============================================
# CORREO (SendGrid)
# ============================================
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"

# ============================================
# CORREO (SMTP - Alternativa)
# ============================================
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=tu-email@gmail.com
# MAIL_PASSWORD=contraseña-de-aplicacion
# MAIL_ENCRYPTION=tls

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

## ✅ ORDEN DE CONFIGURACIÓN RECOMENDADO

1. ✅ **Base de datos primero** (sin esto no funciona nada)
2. ✅ **APP_KEY** (necesario para encriptación)
3. ✅ **APP_URL** (URL de tu aplicación)
4. ✅ **AWS S3** (si vas a subir archivos)
5. ✅ **Correo** (si vas a enviar emails)

---

## 🆘 ¿NO TIENES ALGO?

### No tienes AWS S3
- Usa `FILESYSTEM_DRIVER=local`
- Los archivos se guardarán en el servidor (menos recomendado)

### No tienes SendGrid
- Usa SMTP con Gmail (gratis)
- O deja el correo sin configurar (no podrás enviar emails)

### No tienes base de datos aún
- Créala primero en Render
- Sin base de datos, la aplicación no funcionará

---

## 📞 ENLACES ÚTILES

- **Render Dashboard:** https://dashboard.render.com/
- **AWS Console:** https://console.aws.amazon.com/
- **SendGrid:** https://sendgrid.com/
- **Geonames:** https://www.geonames.org/

---

**¿Necesitas ayuda con alguna variable específica?** Revisa la guía completa en `GUIA_CONFIGURAR_ENV_PASO_A_PASO.md`
