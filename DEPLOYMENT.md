# Guía de Despliegue - SAGIS

Esta guía detalla los pasos para desplegar la aplicación en un servidor Linux utilizando **Podman**.

## Prerrequisitos
- Tener instalado `podman` y `podman-compose`.
- Tener acceso a la base de datos PostgreSQL externa.

## Pasos para el Despliegue

### 1. Clonar el Repositorio
En el servidor, clona el repositorio:
```bash
git clone <url-del-repositorio>
cd Sagis
```

### 2. Configuración de Entorno
Verifica que el archivo `.env` tenga las credenciales correctas (ya configuradas en este commit):
- `DB_HOST=host.containers.internal`
- `DB_DATABASE=sagis`
- `DB_USERNAME=camilo1151967`
- `DB_PASSWORD=1151967`

### 3. Levantar los Contenedores
Ejecuta el siguiente comando para construir la imagen e iniciar el contenedor:
```bash
podman-compose up -d --build
```

### 4. Ejecutar Migraciones
Una vez que el contenedor `sagis_app` esté en ejecución, inicializa la base de datos:
```bash
podman exec sagis_app php artisan migrate --force
```

### 5. Enlace Simbólico de Almacenamiento
Crea el link para que las imágenes se vean correctamente:
```bash
podman exec sagis_app php artisan storage:link
```

### 6. Optimización (Opcional)
Para mejorar el rendimiento en producción:
```bash
podman exec sagis_app php artisan config:cache
podman exec sagis_app php artisan route:cache
podman exec sagis_app php artisan view:cache
```

## Troubleshooting
- **Conexión a BD**: Si el contenedor no se conecta, asegúrate de que el firewall del host permita tráfico en el puerto 5432 y que el parámetro `extra_hosts` en `docker-compose.yml` esté presente.
- **Permisos**: El script `start.sh` se encarga de corregir los permisos de `storage` y `bootstrap/cache` al iniciar.
