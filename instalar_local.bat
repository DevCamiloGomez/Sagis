@echo off
echo ========================================
echo   INSTALACION LOCAL DE SAGIS
echo ========================================
echo.

REM Verificar si existe .env
if not exist .env (
    echo [1/6] Creando archivo .env...
    copy .env.example .env
    echo ✓ Archivo .env creado
) else (
    echo [1/6] Archivo .env ya existe
)
echo.

REM Verificar Composer
echo [2/6] Instalando dependencias de Composer...
where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo Usando Composer de Laragon...
    C:\laragon\bin\composer\composer.bat install
) else (
    composer install
)
echo.

REM Verificar PHP
echo [3/6] Generando APP_KEY...
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo Usando PHP de Laragon...
    C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan key:generate
) else (
    php artisan key:generate
)
echo.

echo [4/6] Creando base de datos...
echo Por favor, crea la base de datos 'sagis' desde HeidiSQL o MySQL
echo Presiona cualquier tecla cuando hayas creado la base de datos...
pause >nul
echo.

REM Ejecutar migraciones
echo [5/6] Ejecutando migraciones y seeders...
where php >nul 2>&1
if %errorlevel% neq 0 (
    C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan migrate --seed
) else (
    php artisan migrate --seed
)
echo.

REM Crear enlace de storage
echo [6/6] Creando enlace simbólico de storage...
where php >nul 2>&1
if %errorlevel% neq 0 (
    C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan storage:link
) else (
    php artisan storage:link
)
echo.

echo ========================================
echo   INSTALACION COMPLETADA
echo ========================================
echo.
echo Accede a la aplicacion en:
echo   http://sagis.test
echo   o
echo   http://localhost/Sagis/public
echo.
pause
