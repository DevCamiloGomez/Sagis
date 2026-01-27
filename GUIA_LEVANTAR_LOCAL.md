# 💻 Guía: Levantar SAGIS en tu Máquina Local (Windows)

## 🎯 OPCIÓN 1: Con Laragon (Recomendado - Más Fácil)

### Requisitos

- ✅ Laragon instalado
- ✅ Composer instalado (o usar el de Laragon)
- ✅ Git instalado

### Paso 1: Abrir Laragon

1. Abre **Laragon**
2. Asegúrate de que esté corriendo (botón **Start All**)

### Paso 2: Navegar al Proyecto

```bash
# Abre Cmder (terminal de Laragon) o PowerShell
cd C:\Users\SrLob\OneDrive\Desktop\Sagis
```

### Paso 3: Instalar Dependencias

```bash
# Si Composer está en el PATH
composer install

# O usar el de Laragon
C:\laragon\bin\composer\composer.bat install
```

### Paso 4: Crear Archivo .env

```bash
# Si no existe, copia el ejemplo
copy .env.example .env
```

### Paso 5: Configurar .env

Edita el archivo `.env` con estas configuraciones:

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

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Para desarrollo local, usa 'local' en lugar de S3
FILESYSTEM_DRIVER=local

# Correo (opcional para desarrollo)
MAIL_MAILER=log
```

### Paso 6: Crear Base de Datos

**Opción A: Desde HeidiSQL (Laragon)**
1. Abre **HeidiSQL** desde Laragon
2. Clic derecho en la conexión → **Crear nueva** → **Base de datos**
3. Nombre: `sagis`
4. Clic en **Aceptar**

**Opción B: Desde la Terminal**
```bash
# Conectar a MySQL
mysql -u root -p

# Crear base de datos
CREATE DATABASE sagis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Salir
exit;
```

### Paso 7: Generar APP_KEY

```bash
# Si PHP está en el PATH
php artisan key:generate

# O usar el de Laragon
C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan key:generate
```

### Paso 8: Ejecutar Migraciones y Seeders

```bash
php artisan migrate --seed
```

### Paso 9: Crear Enlace Simbólico de Storage

```bash
php artisan storage:link
```

### Paso 10: Configurar Virtual Host (Opcional)

1. En Laragon, clic derecho en el proyecto → **Menu** → **Quick add** → **Virtual Host**
2. O manualmente:
   - Abre `C:\laragon\etc\apache2\sites-enabled\`
   - Crea archivo `sagis.test.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName sagis.test
       DocumentRoot "C:/Users/SrLob/OneDrive/Desktop/Sagis/public"
       <Directory "C:/Users/SrLob/OneDrive/Desktop/Sagis/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
3. Reinicia Laragon

### Paso 11: Acceder a la Aplicación

Abre tu navegador en:
```
http://sagis.test
```

O si no configuraste virtual host:
```
http://localhost/Sagis/public
```

---

## 🐳 OPCIÓN 2: Con Docker (Docker Desktop)

### Requisitos

- ✅ Docker Desktop instalado y corriendo
- ✅ Git instalado

### Paso 1: Abrir Terminal

Abre **PowerShell** o **CMD** en la carpeta del proyecto:
```bash
cd C:\Users\SrLob\OneDrive\Desktop\Sagis
```

### Paso 2: Crear Archivo .env

```bash
copy .env.example .env
```

### Paso 3: Configurar .env para Docker

Edita el archivo `.env`:

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

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

FILESYSTEM_DRIVER=local
MAIL_MAILER=log
```

### Paso 4: Levantar Contenedores

```bash
docker-compose up -d
```

### Paso 5: Instalar Dependencias

```bash
docker-compose exec laravel.test composer install
```

### Paso 6: Generar APP_KEY

```bash
docker-compose exec laravel.test php artisan key:generate
```

### Paso 7: Ejecutar Migraciones

```bash
docker-compose exec laravel.test php artisan migrate --seed
```

### Paso 8: Crear Enlace de Storage

```bash
docker-compose exec laravel.test php artisan storage:link
```

### Paso 9: Acceder a la Aplicación

Abre tu navegador en:
```
http://localhost
```

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Error: "PHP no se reconoce como comando"

**Solución Laragon:**
```bash
# Usar la ruta completa
C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan [comando]
```

**O agregar PHP al PATH:**
1. Busca "Variables de entorno" en Windows
2. Edita la variable `Path`
3. Agrega: `C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64`

### Error: "Composer no se reconoce como comando"

**Solución Laragon:**
```bash
# Usar la ruta completa
C:\laragon\bin\composer\composer.bat install
```

### Error: "Unknown database 'sagis'"

**Solución:**
1. Crea la base de datos desde HeidiSQL o MySQL
2. Verifica el nombre en `.env`

### Error: "Access denied for user 'root'"

**Solución:**
1. Verifica la contraseña en `.env`
2. En Laragon, generalmente la contraseña está vacía: `DB_PASSWORD=`

### Error: "Port 80 is already in use" (Docker)

**Solución:**
1. Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - '8080:80'
```
2. Accede a `http://localhost:8080`

### Error: "Storage link already exists"

**Solución:**
```bash
# Eliminar y recrear
php artisan storage:link --force
```

---

## 📋 CHECKLIST RÁPIDO

### Con Laragon:
- [ ] Laragon corriendo
- [ ] Base de datos `sagis` creada
- [ ] Archivo `.env` configurado
- [ ] `composer install` ejecutado
- [ ] `php artisan key:generate` ejecutado
- [ ] `php artisan migrate --seed` ejecutado
- [ ] `php artisan storage:link` ejecutado
- [ ] Acceso a `http://sagis.test` o `http://localhost/Sagis/public`

### Con Docker:
- [ ] Docker Desktop corriendo
- [ ] Archivo `.env` configurado
- [ ] `docker-compose up -d` ejecutado
- [ ] `composer install` ejecutado
- [ ] `php artisan key:generate` ejecutado
- [ ] `php artisan migrate --seed` ejecutado
- [ ] `php artisan storage:link` ejecutado
- [ ] Acceso a `http://localhost`

---

## 🚀 COMANDOS RÁPIDOS (Laragon)

```bash
# Todo en uno (desde la carpeta del proyecto)
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Acceder
# http://sagis.test
```

---

## 📝 CONFIGURACIÓN .env MÍNIMA (Laragon)

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

---

**¿Prefieres usar Laragon o Docker?** Te recomiendo Laragon si ya lo tienes instalado, es más rápido para desarrollo local.
