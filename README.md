# 🌐 Proyecto Web en InfinityFree – **Colombia Explora (Turismo)**

## 👥 Integrantes y Roles
- **Jesús David Garcés Díaz** – Líder de Proyecto / Coordinador  
- **Juan Sebastián Gómez Díaz** – Desarrollador Backend  
- **Edwin Velásquez García** – Desarrollador Frontend / Diseñador UI  
- **Julián Camilo Morales Agudelo** – Administrador de Base de Datos (DBA)  
- **Robin Henao Botero** – DevOps / Especialista en Deployment  
- **Darwin Minota Quinto** – QA / Tester  

## 📖 Descripción del Proyecto
**Colombia Explora** es una plataforma web enfocada en el sector **turismo**, cuyo objetivo es ofrecer a los usuarios una experiencia sencilla y confiable para:  

- Explorar **lugares y destinos turísticos** en Colombia.  
- **Registrarse** como usuarios para personalizar su experiencia.  
- **Reservar y comprar** paquetes turísticos, hoteles y actividades.  

La plataforma busca promover el turismo local, apoyar a prestadores de servicios turísticos y facilitar el acceso a experiencias únicas en distintas regiones del país.  

## 📂 Estructura del Proyecto

```bash
.
└── colombia_explora
    ├── 1_documentacion/                # Documentación técnica y diagramas
    │   ├── 1_DiagramaDeClases/         # Diagrama de clases del sistema
    │   ├── 2_Modelo_db_MySQL/          # Modelo de la base de datos en MySQL
    │   └── 3_estructura_MVC_Simple/    # Esquema MVC simplificado
    │
    ├── assets/                         # Archivos estáticos
    │   ├── css/                        # Hojas de estilo
    │   └── img/                        # Imágenes y recursos gráficos
    │
    ├── models/                         # Lógica de negocio (Modelos PHP)
    │   ├── Destino.php
    │   ├── Reservacion.php
    │   ├── Rol.php
    │   └── Usuario.php
    │
    ├── views/                          # Vistas (componentes de la interfaz)
    │   ├── footer.php
    │   ├── header.php
    │   ├── home.php
    │   ├── listar_reservaciones.php
    │   ├── login_form.php
    │   └── reserva_form.php
    │
    ├── sql/
    │   └── schema.sql                  # Script de creación de base de datos
    │
    ├── db.php                           # Conexión principal a la base de datos
    ├── db_w.php                          # Conexión alterna (escritura)
    ├── guardar.php                       # Lógica para guardar datos
    ├── index.php                          # Página principal
    ├── listar.php                          # Listado general de datos
    ├── login.php                           # Controlador de inicio de sesión
    ├── logout.php                          # Cierre de sesión
    ├── registro.php                        # Registro de nuevos usuarios
    ├── reserva.php                          # Procesamiento de reservas
    ├── session.php                          # Manejo de sesiones
    └── README.md                            # Documentación del proyecto

```

## 🚀 Instrucciones de Uso
1. Subir los archivos a la carpeta `htdocs` o `public_html` en InfinityFree.  
2. Configurar la conexión en `db_connect.php` con:
   - Host: `sqlXXX.epizy.com`
   - Usuario: `[usuario asignado]`
   - Contraseña: `[contraseña asignada]`
   - Nombre de la base de datos: `[db asignada]`
3. Ingresar al sitio desde la URL pública:  
   👉 [Pegar aquí el enlace de su sitio en InfinityFree]

## 🖼️ Evidencias de Despliegue
- URL del sitio: [enlace]
- Captura de phpMyAdmin mostrando ≥3 registros (`capturas/phpmyadmin.png`)  
- Captura del File Manager con archivos subidos (`capturas/filemanager.png`)  
- Captura del sitio funcionando (`capturas/sitio.png`)

## 📂 Archivos Entregados
- `codigo.zip` – Código completo del proyecto  
- `dump.sql` – Base de datos exportada  
- `qa-report.md` – Reporte de pruebas realizadas  
- Carpeta `capturas/` – Evidencias gráficas  

## 📝 Changelog (registro de cambios)
- **Jesús David Garcés Díaz** – Implementó validaciones y seguridad con prepared statements.  
- **Julián Camilo Morales Agudelo** – Mejoró la interfaz y organizó assets en carpeta `static/`.  
- **Edwin Velásquez García** – Configuró la base de datos y generó `dump.sql`.  
- **Robin Henao Botero** – Subió el proyecto al hosting InfinityFree.  
- **Darwin Minota Quinto** – Realizó pruebas QA y documentó resultados.  
- **Juan Sebastián Gómez Díaz** – Redactó README.md y preparó presentación.  

## ❓ Preguntas de Reflexión (Cloud)
1. ¿Qué es despliegue y cómo lo hicieron en este proyecto?  
   > Respuesta aquí  

2. ¿Qué limitaciones encontraron en InfinityFree?  
   > Respuesta aquí  

3. ¿Qué servicio equivalente usarían en AWS, Azure o GCP para:  
   - Archivos estáticos  
   - Base de datos  
   - Hosting del sitio  
   > Respuesta aquí  

4. ¿Cómo resolverían escalabilidad y alta disponibilidad en la nube?  
   > Respuesta aquí  

5. Plan de migración en 4–5 pasos desde InfinityFree hacia un servicio en la nube.  
   > Respuesta aquí  
