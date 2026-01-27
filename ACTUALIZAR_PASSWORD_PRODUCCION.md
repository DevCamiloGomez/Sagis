# Actualizar Contraseña en Producción

## Hash Actual en la Base de Datos

```
$2y$10$mSZ9lIZgdecn/3LZuZ1zDeV99IoPIE7lW1Clup975NfxMcTnzh8fG
```

## Solución: Actualizar a "admin123"

Como no se puede descifrar un hash bcrypt, la mejor opción es **actualizar la contraseña directamente** a `admin123`.

### Opción 1: Usar el Comando Artisan (Si tienes acceso SSH)

```bash
php artisan admin:update-password camiloalonsogoca@ufps.edu.co admin123
```

### Opción 2: SQL Directo (Si tienes acceso a la BD)

Ejecuta este SQL en tu base de datos de producción:

```sql
UPDATE admins 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    updated_at = NOW()
WHERE email = 'camiloalonsogoca@ufps.edu.co';
```

**Nota:** El hash `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` corresponde a la contraseña `admin123`.

### Opción 3: Generar tu propio hash

Si quieres generar un hash nuevo para "admin123", ejecuta:

```php
<?php
echo password_hash('admin123', PASSWORD_BCRYPT);
?>
```

Y luego actualiza en la BD con ese hash.

---

## Credenciales Finales

- **Email:** `camiloalonsogoca@ufps.edu.co`
- **Contraseña:** `admin123`

---

## Verificar Contraseña Actual (Opcional)

Si quieres intentar descubrir la contraseña actual, puedes probar estas comunes:

- admin123
- password
- admin
- 123456
- password123
- camilo123
- ufps123

Pero **no es posible descifrar** un hash bcrypt, solo verificar si una contraseña coincide.
