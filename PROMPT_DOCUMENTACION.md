# Prompt para ChatGPT - Documentación PapuShop

Actúa como documentador técnico profesional. Necesito documentación completa y profesional para mi proyecto de tienda online PHP llamado **PapuShop**.

## Información del Proyecto

**Nombre:** PapuShop
**Tipo:** Tienda online de ropa
**Tecnología:** PHP 8+, MySQL, PDO, Sesiones PHP, Encriptación OpenSSL
**Versión:** 1.0.0

## Estructura de Base de Datos

### Tablas:
1. **usuarios** (ID, NombreUsuario, Password, IDrol)
2. **roles** (ID, NombreRol)
3. **categorias** (ID, NombreCategoria, DescripcionCategoria)
4. **marcas** (ID, NombreMarca, DescripcionMarca)
5. **productos** (ID, NombreProducto, Precio, DescripcionProducto, Imagen, IDmarca, IDcategoria)
6. **ventas** (ID, IDusuario, Fecha, Total, Estado)
7. **ventas_items** (ID, IDventa, IDproducto, Cantidad, PrecioUnitario, Subtotal)

### Relaciones:
- usuarios → roles (muchos a uno)
- productos → categorias (muchos a uno)
- productos → marcas (muchos a uno)
- ventas → usuarios (muchos a uno)
- ventas_items → ventas (muchos a uno)
- ventas_items → productos (muchos a uno)

## Estructura de Archivos

```
papushop/
├── Global/
│   ├── Config.php (constantes de BD y encriptación)
│   ├── Conexion.php (conexión PDO)
│   └── Session.php (gestión centralizada de sesiones)
├── Templates/
│   ├── Header.php (encabezado con navegación)
│   └── Footer.php (pie de página)
├── Assets/
│   ├── Css/
│   │   └── Style.css (estilos del proyecto)
│   └── Img/
│       ├── Abrigos/
│       ├── Bermudas/
│       └── Medias/
├── Index.php (página de inicio - redirecciona según rol)
├── Login.php (formulario de autenticación)
├── Registro.php (formulario de registro)
├── Tienda.php (listado de productos)
├── VerCarrito.php (carrito de compras)
├── VaciarCarrito.php (vacía el carrito)
├── Carrito.php (lógica de agregar productos)
├── Logout.php (cierre de sesión)
├── Dashboard.php (panel administrativo)
├── GestionProductos.php (listar productos para admin)
├── CrearProducto.php (crear nuevo producto)
├── EditarProducto.php (editar producto)
├── EliminarProducto.php (eliminar producto)
├── papushop.sql (dump de base de datos inicial)
├── ventas.sql (estructura de tablas de ventas)
├── usuarios_ejemplo.sql (usuarios de prueba)
└── estilos.json (configuración de clases CSS)
```

## Funcionalidades Principales

1. **Autenticación:**
   - Registro de usuarios con hash de contraseña (password_hash)
   - Login con validación PDO preparada
   - Cierre de sesión
   - Control de acceso por roles (Usuario/Administrador)

2. **Tienda:**
   - Listado de productos con JOIN a categorias y marcas
   - Filtrado por categoría (puede extenderse)
   - Imágenes responsivas
   - Vista de precio formateado

3. **Carrito:**
   - Agregar productos (datos encriptados con OpenSSL AES-128-ECB)
   - Incremento de cantidad si producto ya existe
   - Ver listado de artículos en carrito
   - Vaciar carrito
   - Botón de "Proceder con compra" (simulación)

4. **Administración:**
   - CRUD de productos (crear, leer, editar, eliminar)
   - Selección de imágenes existentes desde Assets/Img
   - Gestión de categorías y marcas
   - Panel de control centralizado

5. **Seguridad:**
   - Sesiones centralizadas (evita múltiples session_start)
   - Constantes protegidas con guard
   - Consultas preparadas (PDO)
   - Encriptación de datos sensibles en formularios
   - Validación de roles en cada página

## Flujos de Usuario

### Flujo 1: Usuario Normal
1. Accede a Index.php
2. Redirigido a Login.php (sin sesión)
3. Ingresa credenciales
4. Redirigido a Tienda.php
5. Navega productos, agrega al carrito
6. Ve carrito en VerCarrito.php
7. Puede vaciar o proceder con compra (simulación)
8. Cierra sesión en Logout.php

### Flujo 2: Administrador
1. Accede a Index.php
2. Redirigido a Login.php
3. Ingresa credenciales admin
4. Redirigido a Dashboard.php
5. Accede a GestionProductos.php
6. Puede crear, editar o eliminar productos
7. Puede ver la tienda como cliente
8. Cierra sesión

### Flujo 3: Nuevo Usuario
1. En Login.php, hace clic en "Regístrate"
2. Accede a Registro.php
3. Completa formulario (usuario, contraseña, confirmación)
4. Se valida contraseña (mínimo 6 caracteres)
5. Se crea usuario en BD con rol "Usuario"
6. Redirigido a Login.php para ingresar

## Rutas de Acceso (URLs)

- `/papushop/` → Index.php
- `/papushop/Login.php` → Formulario de login
- `/papushop/Registro.php` → Formulario de registro
- `/papushop/Tienda.php` → Listado de productos (requiere autenticación)
- `/papushop/VerCarrito.php` → Ver carrito
- `/papushop/Dashboard.php` → Panel admin (requiere rol admin)
- `/papushop/GestionProductos.php` → Gestionar productos
- `/papushop/CrearProducto.php` → Crear producto
- `/papushop/EditarProducto.php?id=X` → Editar producto X
- `/papushop/EliminarProducto.php?id=X` → Eliminar producto X
- `/papushop/Logout.php` → Cerrar sesión

## Diagramas UML Requeridos

Por favor genera los siguientes diagramas UML en formato Mermaid o descripción ASCII:

### 1. Diagrama de Casos de Uso
- Actor: Usuario Anónimo
- Actor: Usuario Autenticado
- Actor: Administrador
- Casos: Registrarse, Iniciar Sesión, Ver Productos, Agregar Carrito, Procesar Compra, Gestionar Productos, Cerrar Sesión

### 2. Diagrama de Clases (Entidades)
- Clase Usuario (ID, NombreUsuario, Password, IDrol)
- Clase Rol (ID, NombreRol)
- Clase Producto (ID, NombreProducto, Precio, Descripcion, Imagen, IDmarca, IDcategoria)
- Clase Categoria (ID, NombreCategoria, Descripcion)
- Clase Marca (ID, NombreMarca, Descripcion)
- Clase Venta (ID, IDusuario, Fecha, Total, Estado)
- Clase VentaItem (ID, IDventa, IDproducto, Cantidad, PrecioUnitario, Subtotal)
- Relaciones entre clases

### 3. Diagrama de Secuencia
- Proceso: Usuario se registra y compra un producto
- Secuencia: Registro → Login → Ver Tienda → Agregar Carrito → Ver Carrito → Procesar Compra

### 4. Diagrama de Actividad
- Flujo: Compra de un producto (desde registro hasta confirmación)

### 5. Diagrama Entidad-Relación (ER)
- Todas las tablas de BD
- Relaciones primarias y foráneas
- Cardinalidades

## Contenido Solicitado

Estructura de la documentación:

1. **Portada**
   - Título: PapuShop - Documentación Técnica
   - Versión: 1.0.0
   - Fecha: [fecha actual]
   - Autor: [Nombre del desarrollador]

2. **Índice**
   - Lista completa de secciones y subsecciones con números de página

3. **Introducción**
   - Descripción general del proyecto
   - Objetivos
   - Público objetivo
   - Requisitos previos (PHP 8+, MySQL, navegador moderno)

4. **Descripción General**
   - Visión del proyecto
   - Características principales
   - Tecnologías utilizadas

5. **Requisitos del Sistema**
   - Requisitos de hardware mínimos
   - Requisitos de software (PHP, MySQL, navegadores soportados)
   - Dependencias

6. **Instalación y Configuración**
   - Paso a paso de instalación
   - Configuración de BD
   - Configuración de credenciales (Config.php)
   - Cómo importar SQL inicial

7. **Estructura del Proyecto**
   - Árbol de directorios comentado
   - Descripción de cada carpeta y archivo principal

8. **Guía de Uso**
   - Cómo registrarse
   - Cómo iniciar sesión
   - Cómo navegar la tienda
   - Cómo usar el carrito
   - Cómo administrar productos (para admin)

9. **Modelos de Datos**
   - Descripción de cada tabla
   - Campos, tipos, restricciones
   - Relaciones entre tablas

10. **Diagramas UML**
    - Incluye todos los diagramas mencionados arriba

11. **Arquitectura del Sistema**
    - Arquitectura en capas
    - Flujo de datos
    - Componentes principales

12. **Seguridad**
    - Medidas de seguridad implementadas
    - Buenas prácticas aplicadas
    - Posibles mejoras futuras

13. **API/Funciones Principales**
    - Descripción de funciones críticas
    - Parámetros
    - Valores de retorno

14. **Errores Comunes y Solución**
    - Problemas conocidos
    - Pasos para resolverlos

15. **Mejoras Futuras**
    - Funcionalidades por agregar
    - Optimizaciones propuestas

16. **Glosario**
    - Términos técnicos explicados

17. **Apéndices**
    - Código de ejemplo
    - Scripts SQL completos
    - Configuración de servidor recomendada

## Formato y Estilo

- Documento en Markdown o HTML
- Incluir capturas de pantalla/mockups cuando sea posible
- Código en bloques coloreados
- Diagramas en formato Mermaid (compatible con GitHub, GitLab, etc.)
- Lenguaje técnico pero accesible
- Máximo profesionalismo

## Entregables

Por favor proporciona:
1. Documentación completa en Markdown
2. Todos los diagramas UML en formato Mermaid
3. Tabla de contenidos con links internos
4. Índice de figuras y tablas

---

**Nota:** Si necesitas aclaraciones sobre cualquier aspecto técnico o funcional del proyecto, por favor pregunta. La documentación debe ser lo suficientemente detallada para que un nuevo desarrollador pueda entender y mantener el código.
