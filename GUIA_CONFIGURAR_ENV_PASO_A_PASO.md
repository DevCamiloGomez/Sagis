# Guía Paso a Paso: Configurar archivo .env para SAGIS

## 📋 ÍNDICE

1. [Variables de Aplicación](#1-variables-de-aplicación)
2. [Variables de Base de Datos](#2-variables-de-base-de-datos)
3. [Variables de AWS S3](#3-variables-de-aws-s3)
4. [Variables de Correo Electrónico](#4-variables-de-correo-electrónico)
5. [Variables Opcionales](#5-variables-opcionales)
6. [Configuración en Render](#6-configuración-en-render)

---

## 1. VARIABLES DE APLICACIÓN

### `APP_NAME`
**¿Qué es?** Nombre de tu aplicación  
**¿Dónde obtenerlo?** Tú lo defines  
**Valor sugerido:**
```env
APP_NAME=SAGIS
```
o
```env
APP_NAME="Sistema de Administración y Gestión de Información de Seguimiento"
```

### `APP_ENV`
**¿Qué es?** Entorno de ejecución  
**¿Dónde obtenerlo?** 
- **Local/Desarrollo:** `local`
- **Producción:** `production`

**Valor para producción:**
```env
APP_ENV=production
```

### `APP_KEY`
**¿Qué es?** Clave de encriptación de Laravel  
**¿Dónde obtenerlo?** Se genera automáticamente  
**Cómo generarlo:**
```bash
php artisan key:generate
```
**Ejemplo:**
```env
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### `APP_DEBUG`
**¿Qué es?** Modo de depuración (muestra errores detallados)  
**¿Dónde obtenerlo?** Tú lo defines  
**Valores:**
- **Local:** `true` (muestra errores)
- **Producción:** `false` (oculta errores por seguridad)

**Valor para producción:**
```env
APP_DEBUG=false
```

### `APP_URL`
**¿Qué es?** URL pública de tu aplicación  
**¿Dónde obtenerlo?** 
- **Local:** `http://localhost` o `http://sagis.test`
- **Producción:** La URL que te da Render (ej: `https://sagis.onrender.com`)

**Ejemplo para Render:**
```env
APP_URL=https://sagis.onrender.com
```

---

## 2. VARIABLES DE BASE DE DATOS

### `DB_CONNECTION`
**¿Qué es?** Tipo de base de datos  
**Valor fijo:**
```env
DB_CONNECTION=mysql
```

### `DB_HOST`
**¿Qué es?** Dirección del servidor de base de datos  
**¿Dónde obtenerlo?** 
- **Render:** Te lo proporciona cuando creas la base de datos MySQL
- **Local:** `127.0.0.1` o `localhost`

**Ejemplo Render:**
```env
DB_HOST=dpg-xxxxxxxxxxxxx-a.oregon-postgres.render.com
```
O si es MySQL:
```env
DB_HOST=mysql.xxxxx.render.com
```

### `DB_PORT`
**¿Qué es?** Puerto de la base de datos  
**Valor estándar MySQL:**
```env
DB_PORT=3306
```

### `DB_DATABASE`
**¿Qué es?** Nombre de la base de datos  
**¿Dónde obtenerlo?** 
- **Render:** Lo defines al crear la base de datos MySQL
- **Local:** El nombre que le diste (ej: `sagis`)

**Ejemplo:**
```env
DB_DATABASE=sagis
```

### `DB_USERNAME`
**¿Qué es?** Usuario de la base de datos  
**¿Dónde obtenerlo?** 
- **Render:** Te lo proporciona al crear la BD
- **Local:** Generalmente `root`

**Ejemplo Render:**
```env
DB_USERNAME=sagis_user
```

### `DB_PASSWORD`
**¿Qué es?** Contraseña de la base de datos  
**¿Dónde obtenerlo?** 
- **Render:** Te la proporciona al crear la BD (copia exacta)
- **Local:** Generalmente vacía o la que configuraste

**Ejemplo Render:**
```env
DB_PASSWORD=tu_contraseña_aqui_123456
```

**⚠️ IMPORTANTE:** Copia exactamente la contraseña que te da Render, incluyendo caracteres especiales.

---

## 3. VARIABLES DE AWS S3

**¿Para qué sirven?** Almacenar archivos (imágenes, documentos) en AWS S3 en lugar del servidor local.

### `AWS_ACCESS_KEY_ID`
**¿Qué es?** ID de acceso de AWS  
**¿Dónde obtenerlo?**
1. Ve a [AWS Console](https://console.aws.amazon.com/)
2. Inicia sesión con tu cuenta AWS
3. Ve a **IAM** (Identity and Access Management)
4. Clic en **Users** → Tu usuario → **Security credentials**
5. Clic en **Create access key**
6. Copia el **Access key ID**

**Ejemplo:**
```env
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
```

### `AWS_SECRET_ACCESS_KEY`
**¿Qué es?** Clave secreta de AWS  
**¿Dónde obtenerlo?**
1. En el mismo lugar donde obtuviste el Access Key ID
2. Al crear el access key, te muestra el **Secret access key**
3. **⚠️ IMPORTANTE:** Solo se muestra una vez, cópialo inmediatamente

**Ejemplo:**
```env
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
```

### `AWS_DEFAULT_REGION`
**¿Qué es?** Región donde está tu bucket S3  
**¿Dónde obtenerlo?**
1. Ve a [AWS S3 Console](https://s3.console.aws.amazon.com/)
2. Selecciona tu bucket
3. Ve a **Properties** → **Region**
4. Copia el código de región (ej: `us-east-1`, `us-east-2`)

**Ejemplo:**
```env
AWS_DEFAULT_REGION=us-east-1
```

### `AWS_BUCKET`
**¿Qué es?** Nombre de tu bucket S3  
**¿Dónde obtenerlo?**
1. Ve a [AWS S3 Console](https://s3.console.aws.amazon.com/)
2. Si ya tienes un bucket, copia su nombre
3. Si no tienes, créalo:
   - Clic en **Create bucket**
   - Nombre: `sagis-ufps` (o el que prefieras)
   - Región: La misma que `AWS_DEFAULT_REGION`
   - Deja las demás opciones por defecto
   - Clic en **Create bucket**

**Ejemplo:**
```env
AWS_BUCKET=sagis-ufps
```

### `AWS_URL`
**¿Qué es?** URL base de tu bucket S3  
**¿Dónde obtenerlo?** Se forma automáticamente con:
```
https://{bucket}.s3.{region}.amazonaws.com
```

**Ejemplo:**
```env
AWS_URL=https://sagis-ufps.s3.us-east-1.amazonaws.com
```

### `FILESYSTEM_DRIVER`
**¿Qué es?** Dónde almacenar archivos  
**Valores:**
- `local` = Almacenar en el servidor (no recomendado para producción)
- `s3` = Almacenar en AWS S3 (recomendado)

**Valor para producción:**
```env
FILESYSTEM_DRIVER=s3
```

---

## 4. VARIABLES DE CORREO ELECTRÓNICO

El proyecto puede usar **SendGrid** o **SMTP** (Gmail, etc.)

### OPCIÓN A: Usar SendGrid (Recomendado para producción)

#### `MAIL_MAILER`
```env
MAIL_MAILER=sendgrid
```

#### `SENDGRID_API_KEY`
**¿Qué es?** Clave API de SendGrid  
**¿Dónde obtenerlo?**
1. Ve a [SendGrid](https://sendgrid.com/)
2. Crea una cuenta (gratis hasta 100 emails/día)
3. Ve a **Settings** → **API Keys**
4. Clic en **Create API Key**
5. Nombre: `SAGIS Production`
6. Permisos: **Full Access** o **Mail Send**
7. Clic en **Create & View**
8. **⚠️ IMPORTANTE:** Copia la clave inmediatamente (solo se muestra una vez)

**Ejemplo:**
```env
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

#### `MAIL_FROM_ADDRESS`
**¿Qué es?** Email desde el cual se envían los correos  
**Valor sugerido:**
```env
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
```
O si no tienes dominio:
```env
MAIL_FROM_ADDRESS=sagisufps@gmail.com
```

#### `MAIL_FROM_NAME`
**¿Qué es?** Nombre que aparece como remitente  
**Valor sugerido:**
```env
MAIL_FROM_NAME="SAGIS - UFPS"
```

### OPCIÓN B: Usar SMTP (Gmail u otro)

#### `MAIL_MAILER`
```env
MAIL_MAILER=smtp
```

#### `MAIL_HOST`
**¿Qué es?** Servidor SMTP  
**Valores comunes:**
- Gmail: `smtp.gmail.com`
- Outlook: `smtp-mail.outlook.com`
- Otros: Consulta la documentación de tu proveedor

**Ejemplo Gmail:**
```env
MAIL_HOST=smtp.gmail.com
```

#### `MAIL_PORT`
**¿Qué es?** Puerto SMTP  
**Valores comunes:**
- TLS: `587`
- SSL: `465`

**Ejemplo:**
```env
MAIL_PORT=587
```

#### `MAIL_USERNAME`
**¿Qué es?** Email del remitente  
**Ejemplo:**
```env
MAIL_USERNAME=sagisufps@gmail.com
```

#### `MAIL_PASSWORD`
**¿Qué es?** Contraseña del email (o contraseña de aplicación)  
**Para Gmail:**
1. Ve a tu cuenta de Google
2. **Seguridad** → **Verificación en 2 pasos** (debe estar activada)
3. **Contraseñas de aplicaciones**
4. Genera una nueva contraseña para "Correo"
5. Usa esa contraseña (16 caracteres)

**Ejemplo:**
```env
MAIL_PASSWORD=xxxxxxxxxxxxxxxx
```

#### `MAIL_ENCRYPTION`
**¿Qué es?** Tipo de encriptación  
**Valores:**
- `tls` (puerto 587)
- `ssl` (puerto 465)

**Ejemplo:**
```env
MAIL_ENCRYPTION=tls
```

---

## 5. VARIABLES OPCIONALES

### `GEONAMES_USERNAME`
**¿Qué es?** Usuario de Geonames (para búsqueda de ciudades)  
**¿Dónde obtenerlo?**
1. Ve a [Geonames](https://www.geonames.org/login)
2. Crea una cuenta gratuita
3. Ve a **Account** → Tu nombre de usuario aparece ahí

**Ejemplo:**
```env
GEONAMES_USERNAME=camilogomez666
```

**Nota:** Si no lo configuras, el sistema usa el valor por defecto del código.

### `CACHE_DRIVER`
**¿Qué es?** Dónde almacenar la caché  
**Valor recomendado:**
```env
CACHE_DRIVER=file
```

### `SESSION_DRIVER`
**¿Qué es?** Dónde almacenar las sesiones  
**Valor recomendado:**
```env
SESSION_DRIVER=file
```

### `QUEUE_CONNECTION`
**¿Qué es?** Cómo procesar trabajos en cola  
**Valor recomendado:**
```env
QUEUE_CONNECTION=database
```

---

## 6. CONFIGURACIÓN EN RENDER

### Paso 1: Crear Base de Datos MySQL en Render

1. Ve a [Render Dashboard](https://dashboard.render.com/)
2. Clic en **New +** → **PostgreSQL** o **MySQL**
3. Selecciona **MySQL**
4. Configura:
   - **Name:** `sagis-db`
   - **Database:** `sagis`
   - **User:** Se genera automáticamente
   - **Region:** Elige la más cercana
5. Clic en **Create Database**
6. **Espera a que se cree** (puede tardar unos minutos)
7. Una vez creada, verás:
   - **Internal Database URL:** `mysql://user:password@host:port/database`
   - **External Connection String:** Para conexiones externas

**Copia estos valores:**
- `DB_HOST` = El host de la URL (ej: `mysql.xxxxx.render.com`)
- `DB_PORT` = `3306` (generalmente)
- `DB_DATABASE` = El nombre de la base de datos
- `DB_USERNAME` = El usuario
- `DB_PASSWORD` = La contraseña

### Paso 2: Configurar Variables de Entorno en Render

1. Ve a tu servicio web en Render
2. Clic en **Environment**
3. Agrega cada variable:

#### Variables Básicas:
```
APP_NAME=SAGIS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com
```

#### Variables de Base de Datos:
```
DB_CONNECTION=mysql
DB_HOST=mysql.xxxxx.render.com
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=usuario_de_render
DB_PASSWORD=contraseña_de_render
```

#### Variables de AWS (si usas S3):
```
AWS_ACCESS_KEY_ID=tu_access_key
AWS_SECRET_ACCESS_KEY=tu_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=nombre-del-bucket
AWS_URL=https://bucket.s3.region.amazonaws.com
FILESYSTEM_DRIVER=s3
```

#### Variables de Correo:
**Si usas SendGrid:**
```
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.xxxxx
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"
```

**Si usas SMTP:**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="SAGIS - UFPS"
```

### Paso 3: Generar APP_KEY

**Opción A: Desde Render (SSH)**
1. Conéctate por SSH a tu servicio
2. Ejecuta: `php artisan key:generate`

**Opción B: Localmente y copiar**
1. En tu máquina local: `php artisan key:generate`
2. Copia el valor de `APP_KEY` del `.env`
3. Pégalo en Render

---

## 📝 EJEMPLO COMPLETO DE .env PARA PRODUCCIÓN

```env
APP_NAME=SAGIS
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://sagis.onrender.com

DB_CONNECTION=mysql
DB_HOST=mysql.xxxxx.render.com
DB_PORT=3306
DB_DATABASE=sagis
DB_USERNAME=sagis_user
DB_PASSWORD=tu_contraseña_segura_aqui

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

## ✅ CHECKLIST DE CONFIGURACIÓN

- [ ] `APP_NAME` configurado
- [ ] `APP_ENV=production`
- [ ] `APP_KEY` generado
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` con la URL de Render
- [ ] Base de datos MySQL creada en Render
- [ ] `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` configurados
- [ ] Bucket S3 creado en AWS (si usas S3)
- [ ] Credenciales AWS configuradas
- [ ] SendGrid o SMTP configurado
- [ ] Variables de correo configuradas
- [ ] Todas las variables agregadas en Render Dashboard

---

## 🆘 ¿DÓNDE OBTENER CADA COSA?

| Variable | Dónde Obtenerla |
|----------|----------------|
| `APP_KEY` | Generar con `php artisan key:generate` |
| `APP_URL` | Render Dashboard → Tu servicio → URL |
| `DB_HOST` | Render Dashboard → Base de datos → Internal Database URL |
| `DB_DATABASE` | Lo defines al crear la BD en Render |
| `DB_USERNAME` | Render Dashboard → Base de datos → User |
| `DB_PASSWORD` | Render Dashboard → Base de datos → Password |
| `AWS_ACCESS_KEY_ID` | AWS Console → IAM → Create Access Key |
| `AWS_SECRET_ACCESS_KEY` | AWS Console → IAM → Create Access Key |
| `AWS_BUCKET` | AWS S3 Console → Create Bucket |
| `SENDGRID_API_KEY` | SendGrid Dashboard → Settings → API Keys |
| `MAIL_USERNAME` | Tu email (Gmail, etc.) |
| `MAIL_PASSWORD` | Contraseña de aplicación (Gmail) |

---

**¿Necesitas ayuda con alguna variable específica?** Indica cuál y te ayudo a obtenerla paso a paso.
