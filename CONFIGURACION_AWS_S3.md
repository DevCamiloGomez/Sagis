# ☁️ Configuración AWS S3 para SAGIS

## 📋 Información de tu Bucket

- **Nombre del Bucket:** `sagisufpsproyectofinalbuckets3`
- **Access Key ID:** `AKIA5TRAOGG6YH5QKJPD`
- **Región:** `us-east-2`
- **Estado:** ✅ Activo

---

## ✅ VARIABLES PARA RENDER

Agrega estas variables en **Render Dashboard** → Tu servicio → **Environment**:

```env
# ============================================
# ALMACENAMIENTO (AWS S3)
# ============================================
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=AKIA5TRAOGG6YH5QKJPD
AWS_SECRET_ACCESS_KEY=TU_SECRET_ACCESS_KEY_AQUI
AWS_DEFAULT_REGION=us-east-2
AWS_BUCKET=sagisufpsproyectofinalbuckets3
AWS_URL=https://sagisufpsproyectofinalbuckets3.s3.us-east-2.amazonaws.com
```

---

## 🔑 OBTENER SECRET ACCESS KEY

**⚠️ IMPORTANTE:** Necesitas el **Secret Access Key** que corresponde a `AKIA5TRAOGG6YH5QKJPD.

### Opción 1: Si ya lo tienes guardado
- Búscalo en tu gestor de contraseñas o notas
- Es una cadena larga (40 caracteres aproximadamente)

### Opción 2: Si no lo tienes
**No puedes recuperarlo**, pero puedes crear uno nuevo:

1. Ve a [AWS IAM Console](https://console.aws.amazon.com/iam/)
2. **Users** → Tu usuario → **Security credentials**
3. En la sección **Access keys**, busca la clave `AKIA5TRAOGG6YH5QKJPD`
4. Si no puedes ver el secret, haz clic en **Create access key**
5. **⚠️ IMPORTANTE:** Copia el **Secret access key** inmediatamente (solo se muestra una vez)
6. Si creaste una nueva, puedes desactivar la antigua después de verificar que funciona

---

## 📝 CONFIGURACIÓN COMPLETA

### Variables en Render Dashboard:

```
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=AKIA5TRAOGG6YH5QKJPD
AWS_SECRET_ACCESS_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
AWS_DEFAULT_REGION=us-east-2
AWS_BUCKET=sagisufpsproyectofinalbuckets3
AWS_URL=https://sagisufpsproyectofinalbuckets3.s3.us-east-2.amazonaws.com
```

---

## 🔍 VERIFICAR CONFIGURACIÓN

### 1. Verificar Bucket en AWS

1. Ve a [AWS S3 Console](https://s3.console.aws.amazon.com/)
2. Busca el bucket `sagisufpsproyectofinalbuckets3`
3. Verifica que esté en la región `us-east-2`
4. Verifica los permisos (debe permitir lectura pública si es necesario)

### 2. Verificar Permisos del Bucket

El bucket debe tener estos permisos para que Laravel funcione:

**Bucket Policy (si necesitas acceso público):**
```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::sagisufpsproyectofinalbuckets3/*"
        }
    ]
}
```

**CORS Configuration (si subes archivos desde el navegador):**
```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "PUT", "POST", "DELETE", "HEAD"],
        "AllowedOrigins": ["https://sagisufps.onrender.com"],
        "ExposeHeaders": []
    }
]
```

### 3. Verificar desde Laravel

En Render Shell:
```bash
php artisan tinker
>>> Storage::disk('s3')->put('test.txt', 'Hello World');
>>> Storage::disk('s3')->exists('test.txt');
```

Si devuelve `true`, la configuración está correcta.

---

## ⚠️ IMPORTANTE

1. **Secret Access Key:**
   - Es sensible, no la compartas
   - Render la encripta automáticamente
   - Si la pierdes, crea una nueva

2. **Región:**
   - Asegúrate de usar `us-east-2` (no `us-east-1`)
   - La URL del bucket depende de la región

3. **Permisos IAM:**
   - Tu usuario IAM debe tener permisos para S3
   - Mínimo: `AmazonS3FullAccess` o permisos personalizados

---

## ✅ CHECKLIST

- [ ] `AWS_ACCESS_KEY_ID` configurado: `AKIA5TRAOGG6YH5QKJPD`
- [ ] `AWS_SECRET_ACCESS_KEY` configurado (obtener de AWS)
- [ ] `AWS_DEFAULT_REGION` configurado: `us-east-2`
- [ ] `AWS_BUCKET` configurado: `sagisufpsproyectofinalbuckets3`
- [ ] `AWS_URL` configurado: `https://sagisufpsproyectofinalbuckets3.s3.us-east-2.amazonaws.com`
- [ ] `FILESYSTEM_DRIVER` configurado: `s3`
- [ ] Permisos del bucket verificados
- [ ] Prueba de conexión exitosa

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Error: "Access Denied"
**Causa:** Secret Access Key incorrecto o permisos insuficientes  
**Solución:** Verifica el Secret Key y los permisos IAM

### Error: "Bucket not found"
**Causa:** Nombre del bucket incorrecto o región incorrecta  
**Solución:** Verifica `AWS_BUCKET` y `AWS_DEFAULT_REGION`

### Error: "Invalid region"
**Causa:** Región incorrecta  
**Solución:** Usa `us-east-2` (no `us-east-1`)

---

**¿Necesitas ayuda para obtener el Secret Access Key?** Te puedo guiar paso a paso.
