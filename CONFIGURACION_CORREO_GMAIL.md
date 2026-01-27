# 📧 Configuración de Correo Gmail para SAGIS

## ✅ Contraseña de Aplicación

- **Contraseña de Aplicación:** `skpnzeae nzlvhuoi` (sin espacios: `skpnzeaenzlvhuoi`)

---

## 📋 CONFIGURACIÓN PARA RENDER

Agrega estas variables en **Render Dashboard** → Tu servicio → **Environment**:

```env
# ============================================
# CORREO (Gmail SMTP)
# ============================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=TU_EMAIL_GMAIL_AQUI@gmail.com
MAIL_PASSWORD=skpnzeaenzlvhuoi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=TU_EMAIL_GMAIL_AQUI@gmail.com
MAIL_FROM_NAME="SAGIS - UFPS"
```

---

## ⚠️ IMPORTANTE

### 1. Email de Gmail
**Necesitas proporcionar el email de Gmail** que corresponde a esta contraseña de aplicación.

Ejemplo:
- Si tu email es `sagisufps@gmail.com`, entonces:
  ```
  MAIL_USERNAME=sagisufps@gmail.com
  MAIL_FROM_ADDRESS=sagisufps@gmail.com
  ```

### 2. Contraseña sin espacios
La contraseña de aplicación debe ir **sin espacios**:
- ✅ Correcto: `skpnzeaenzlvhuoi`
- ❌ Incorrecto: `skpn zeae nzlv huoi`

### 3. Verificación en 2 pasos
Esta contraseña solo funciona si:
- ✅ Tienes la verificación en 2 pasos activada en tu cuenta de Google
- ✅ La contraseña de aplicación fue generada para "Correo"

---

## 🔧 CONFIGURACIÓN COMPLETA

### Variables en Render Dashboard:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=skpnzeaenzlvhuoi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="SAGIS - UFPS"
```

**Reemplaza `tu-email@gmail.com` con tu email real de Gmail.**

---

## 🧪 PROBAR CONFIGURACIÓN

### Opción 1: Desde Render Shell

```bash
php artisan tinker
>>> Mail::raw('Test email', function($message) {
    $message->to('tu-email-de-prueba@gmail.com')
            ->subject('Test SAGIS');
});
```

### Opción 2: Desde la Aplicación

1. Inicia sesión como administrador
2. Ve a la sección de envío de correos masivos
3. Envía un correo de prueba

---

## ⚠️ LÍMITES DE GMAIL

- **Límite diario:** 500 emails/día (cuenta gratuita)
- **Límite por minuto:** ~100 emails/minuto
- **Para más volumen:** Considera usar SendGrid

---

## 🔄 ALTERNATIVA: SendGrid

Si necesitas enviar más correos, puedes usar SendGrid:

```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=tu_api_key
MAIL_FROM_ADDRESS=noreply@sagis.ufps.edu.co
MAIL_FROM_NAME="SAGIS - UFPS"
```

---

## ✅ CHECKLIST

- [ ] `MAIL_MAILER=smtp` configurado
- [ ] `MAIL_HOST=smtp.gmail.com` configurado
- [ ] `MAIL_PORT=587` configurado
- [ ] `MAIL_USERNAME` con tu email de Gmail
- [ ] `MAIL_PASSWORD=skpnzeaenzlvhuoi` (sin espacios)
- [ ] `MAIL_ENCRYPTION=tls` configurado
- [ ] `MAIL_FROM_ADDRESS` con tu email de Gmail
- [ ] `MAIL_FROM_NAME` configurado
- [ ] Verificación en 2 pasos activada en Google
- [ ] Prueba de envío exitosa

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Error: "Authentication failed"
**Causa:** Email o contraseña incorrectos  
**Solución:** Verifica el email y que la contraseña no tenga espacios

### Error: "Connection timeout"
**Causa:** Puerto o host incorrectos  
**Solución:** Verifica `MAIL_HOST=smtp.gmail.com` y `MAIL_PORT=587`

### Error: "Less secure app access"
**Causa:** Google bloqueó el acceso  
**Solución:** Usa contraseñas de aplicación (ya lo estás haciendo ✅)

---

**¿Cuál es el email de Gmail que corresponde a esta contraseña?** Necesito saberlo para completar la configuración.
