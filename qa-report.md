# 🧪 QA Report – Proyecto en InfinityFree

## 📋 Pruebas realizadas

### 1. Validación de formularios

- Caso: Campo email vacío
- Resultado esperado: mensaje de error
- Resultado obtenido: Se muestra el mensaje "El correo electrónico no es válido." y no se permite el registro.

- Caso: Email inválido (ej: "abc123")
- Resultado esperado: no se guarda en BD
- Resultado obtenido: Se muestra el mensaje "El correo electrónico no es válido." y no se guarda el usuario.

### 2. Seguridad

- Caso: Intento de inyección SQL (`' OR 1=1 --`)
- Resultado esperado: no insertar ni romper consulta
- Resultado obtenido: El sistema utiliza consultas preparadas, por lo que la inyección no tiene efecto y no se altera la
  consulta.

- Caso: Contraseña almacenada en BD
- Resultado esperado: hash en vez de texto plano
- Resultado obtenido: Las contraseñas se almacenan como hash seguro (password_hash), no en texto plano.

### 3. Funcionalidad

- Caso: Insertar registro válido
- Resultado esperado: se guarda en la BD y aparece en `listar.php`
- Resultado obtenido: El registro se guarda correctamente y es visible en el listado de reservaciones.

- Caso: Exportar CSV
- Resultado esperado: descarga archivo con registros
- Resultado obtenido: Funcionalidad no implementada en la versión actual.

### 4. Login (si aplica)

- Caso: Usuario/contraseña correcta
- Resultado esperado: acceso permitido
- Resultado obtenido: El usuario accede correctamente al sistema y puede ver sus reservaciones.

- Caso: Usuario/contraseña incorrecta
- Resultado esperado: acceso denegado
- Resultado obtenido: Se muestra el mensaje "Correo o contraseña incorrectos" y no se permite el acceso.

## ✅ Conclusiones QA

- El sistema cumple con los requisitos básicos de validación, seguridad y funcionalidad para el registro, login y
  gestión de reservaciones. Se utilizan buenas prácticas como consultas preparadas y almacenamiento seguro de
  contraseñas. No se detectaron vulnerabilidades críticas en las pruebas realizadas. Como mejora, se recomienda
  implementar la exportación a CSV y ampliar los mensajes de error para mayor claridad al usuario. En general, la
  aplicación es estable y adecuada para su propósito académico.
