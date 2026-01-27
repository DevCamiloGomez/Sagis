# Requisitos Funcionales del Sistema SAGIS

## RF1. Gestión de Usuarios y Autenticación

### RF1.1: Autenticación de Usuarios
El sistema debe permitir la autenticación de dos tipos de usuarios: administradores y graduados.

### RF1.2: Registro de Usuarios
El sistema debe permitir el registro de nuevos graduados con validación de datos.

### RF1.3: Recuperación de Contraseña
El sistema debe permitir a los usuarios recuperar su contraseña mediante correo electrónico.

### RF1.4: Gestión de Perfiles
El sistema debe permitir a los usuarios ver y actualizar su información personal.

---

## RF2. Control de Acceso y Permisos

### RF2.1: Roles de Usuario
El sistema debe implementar roles diferenciados (administrador, graduado).

### RF2.2: Permisos por Rol
El sistema debe asignar permisos específicos según el rol del usuario.

### RF2.3: Middleware de Autorización
El sistema debe verificar permisos antes de permitir acceso a funcionalidades.

---

## RF3. Gestión de Graduados

### RF3.1: Registro de Graduados
El sistema debe permitir al administrador registrar nuevos graduados con información personal, académica y profesional.

### RF3.2: Actualización de Información
El sistema debe permitir a los graduados actualizar su información personal, académica y laboral.

### RF3.3: Consulta de Graduados
El sistema debe permitir al administrador consultar y filtrar la información de graduados.

### RF3.4: Importación Masiva
El sistema debe permitir importar graduados desde archivos Excel.

### RF3.5: Información Académica
El sistema debe registrar programas académicos, facultades, universidades y niveles académicos de los graduados.

### RF3.6: Información Laboral
El sistema debe registrar la información laboral actual y pasada de los graduados.

---

## RF4. Gestión de Publicaciones

### RF4.1: Creación de Publicaciones
El sistema debe permitir al administrador crear publicaciones (noticias, eventos, cursos, videos).

### RF4.2: Categorización
El sistema debe permitir categorizar las publicaciones por tipo.

### RF4.3: Contenido Multimedia
El sistema debe permitir adjuntar imágenes, videos y documentos a las publicaciones.

### RF4.4: Visualización Pública
El sistema debe permitir a los usuarios y visitantes ver las publicaciones sin autenticación.

### RF4.5: Gestión de Carrusel
El sistema debe permitir gestionar imágenes del carrusel principal de la página de inicio.

---

## RF5. Gestión de Empresas

### RF5.1: Registro de Empresas
El sistema debe permitir registrar empresas empleadoras.

### RF5.2: Vinculación Graduados-Empresas
El sistema debe permitir vincular graduados con empresas donde han trabajado o trabajan.

### RF5.3: Consulta de Empresas
El sistema debe permitir consultar información de empresas registradas.

---

## RF6. Gestión Académica

### RF6.1: Gestión de Universidades
El sistema debe permitir registrar y gestionar universidades.

### RF6.2: Gestión de Facultades
El sistema debe permitir registrar facultades asociadas a universidades.

### RF6.3: Gestión de Programas
El sistema debe permitir registrar programas académicos asociados a facultades.

### RF6.4: Niveles Académicos
El sistema debe gestionar niveles académicos (pregrado, especialización, maestría, doctorado).

---

## RF7. Gestión Geográfica

### RF7.1: Gestión de Países
El sistema debe permitir registrar y gestionar países.

### RF7.2: Gestión de Estados/Departamentos
El sistema debe permitir registrar estados o departamentos asociados a países.

### RF7.3: Gestión de Ciudades
El sistema debe permitir registrar ciudades asociadas a estados.

---

## RF8. Generación de Reportes

### RF8.1: Reporte de Graduados
El sistema debe generar reportes de graduados con filtros personalizables.

### RF8.2: Exportación a PDF
El sistema debe permitir exportar reportes en formato PDF.

### RF8.3: Exportación a Excel
El sistema debe permitir exportar reportes en formato Excel.

### RF8.4: Estadísticas de Empleabilidad
El sistema debe generar estadísticas sobre la situación laboral de los graduados.

### RF8.5: Gráficos Estadísticos
El sistema debe mostrar gráficos estadísticos sobre programas, empresas y ubicaciones.

---

## RF9. Comunicación

### RF9.1: Envío de Correos Individuales
El sistema debe permitir enviar correos electrónicos a graduados individuales.

### RF9.2: Envío de Correos Masivos
El sistema debe permitir enviar correos electrónicos masivos a grupos de graduados.

### RF9.3: Notificaciones del Sistema
El sistema debe enviar notificaciones automáticas (bienvenida, actualización de información).

---

## RF10. Almacenamiento de Archivos

### RF10.1: Subida de Imágenes
El sistema debe permitir subir imágenes de perfil de graduados.

### RF10.2: Almacenamiento en la Nube
El sistema debe almacenar archivos en AWS S3.

### RF10.3: Validación de Archivos
El sistema debe validar tipo, tamaño y formato de archivos subidos.

---

## Resumen de Requisitos Funcionales

| ID | Módulo | Cantidad de RF |
|----|--------|----------------|
| RF1 | Gestión de Usuarios | 4 |
| RF2 | Control de Acceso | 3 |
| RF3 | Gestión de Graduados | 6 |
| RF4 | Gestión de Publicaciones | 5 |
| RF5 | Gestión de Empresas | 3 |
| RF6 | Gestión Académica | 4 |
| RF7 | Gestión Geográfica | 3 |
| RF8 | Generación de Reportes | 5 |
| RF9 | Comunicación | 3 |
| RF10 | Almacenamiento | 3 |
| **TOTAL** | | **39 RF** |
