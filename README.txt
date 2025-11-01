Código general del sistema HORNIPAN (explicado de forma textual)
Conexión a la base de datos (db.php)
Existe un archivo que se conecta a MySQL usando las credenciales (localhost, root, etc.).
Esta conexión se reutiliza en todos los demás archivos para consultar o guardar datos.

Login de usuarios (login.php y autenticar.php)
Hay un formulario de login donde el usuario escribe su nombre y contraseña.
Cuando el usuario envía el formulario, otro archivo (autenticar.php) revisa en la base de datos si el usuario existe y si la contraseña es correcta.
Si es correcto, se crea una sesión ($_SESSION) para que el usuario pueda navegar por el sistema.

Agregar Técnico (registrar_tecnico.php)
Hay un formulario donde se ingresan tres datos: nombre del técnico, correo electrónico y número de celular.
Al enviarlo, el sistema guarda esta información en la base de datos en una tabla de técnicos.

Agregar Orden (registrar_orden.php)
Se llena un formulario más grande, donde se asigna un técnico, se selecciona el tipo de orden (mantenimiento, instalación), se pone fecha, hora de inicio, hora de fin, dirección, y se puede subir un archivo (foto o PDF).
Esta información se guarda en la tabla de órdenes.

Registrar Usuario (agregar_usuario.php)
Hay un formulario simple donde se pide el nombre de usuario, contraseña y confirmar contraseña.
La contraseña probablemente se guarda de manera segura usando funciones de hash de PHP.

Historiales (historial_ordenes.php, historial_tecnicos.php, historial_usuarios.php)
Hay páginas donde se listan las órdenes, técnicos y usuarios.
Se puede buscar por nombre o número, filtrar por fecha y también eliminar o editar registros.
En el historial de órdenes, además, se puede ver un enlace para abrir el archivo (si se subió un archivo) y se puede generar un PDF del historial.

Dashboard Principal (index.php)
El inicio muestra gráficos de cuántas órdenes están pendientes, procesando y finalizadas.
También muestra una tabla de horario semanal, donde por cada hora y cada día de la semana se ve qué técnico está asignado.

Cerrar Sesión (logout.php)
El usuario puede salir del sistema. Esto destruye la sesión y lo lleva de nuevo a la página de login.

Utilitarios (hash.php, obtener_ordenes.php, etc.)
Hay archivos especiales que ayudan a encriptar contraseñas o traer datos filtrados para mostrar en las páginas.

Interfaz del sistema (explicado de forma textual)
Diseño General

Tienes una barra lateral a la izquierda (de color claro) con el logo arriba y los menús de navegación.

Arriba tienes una barra roja que muestra el nombre del usuario logueado (por ejemplo, "admin") y el botón de "Salir".

Menú Lateral

Botón para ir a Inicio.

Sección Administración: Ingresar Técnico, Ingresar Orden, Registrar Usuario.

Sección Historiales: ver historial de órdenes, técnicos y usuarios.

Dashboard Principal

Título: "Dashboard Hornipan".

Tres gráficos circulares:

Órdenes Pendientes.

Órdenes Procesando.

Órdenes Finalizadas.

Filtro de fechas: puedes elegir desde qué fecha hasta qué fecha quieres ver las órdenes.

Horario Semanal: una tabla donde por cada hora (de 06:00 a 20:00) y cada día de la semana se muestran los técnicos programados.

Registrar Técnico

Un formulario sencillo en el centro de la pantalla.

Campos: nombre del técnico, correo, celular.

Botón rojo para guardar.

Registrar Orden

Un formulario más grande:

Código de Pedido.

Selección de Técnico.

Tipo de Orden.

Fecha.

Hora de Inicio.

Hora de Fin.

Dirección (campo de texto).

Subir archivo (imagen o PDF).

Registrar Usuario

Formulario pequeño:

Nombre de usuario.

Contraseña.

Confirmar contraseña.

Botón rojo para registrar.

Historial de Órdenes

Tabla que muestra:

Pedido, fecha, hora inicio, hora fin, técnico, celular, tipo de orden, dirección, archivo adjunto, estado y acciones.

Botones "Editar" y "Eliminar" en cada fila.

Búsqueda por pedido o técnico.

Botón para generar PDF.

Historial de Técnicos

Tabla que muestra:

ID, nombre, correo, celular.

Búsqueda por nombre o celular.

Botón para generar PDF.

Historial de Usuarios

Tabla que muestra:

ID y nombre de usuario.

Botones para editar o eliminar.

Tu sistema HORNIPAN está compuesto por:

Un backend en PHP que conecta a MySQL para gestionar usuarios, técnicos y órdenes.

Una interfaz web limpia y ordenada con formularios de registro y tablas de visualización.

Funcionalidades de:

Autenticación de usuarios.

Registro de técnicos.

Registro de órdenes.

Listados de técnicos, órdenes y usuarios.

Búsqueda, edición y eliminación.

Exportación a PDF.

Visualización semanal de las órdenes.