# CHECKLIST DE SEGURIDAD
## Sistema Manos de Chichi

El presente checklist permite verificar las medidas de seguridad implementadas en el sistema para proteger la información, controlar el acceso de los usuarios y garantizar el procesamiento seguro de las operaciones.

| No| Medida de seguridad| Descripción | Estado |

| 1 | Protección de contraseñas | Las contraseñas se almacenan utilizando `password_hash()`. | Implementado |
| 2 | Verificación de contraseñas | Se utiliza `password_verify()` durante el inicio de sesión. | Implementado |
| 3 | Consultas preparadas | Se utilizan consultas preparadas para prevenir inyecciones SQL en las operaciones correspondientes. | Implementado |
| 4 | Codificación de caracteres | La conexión con la base de datos utiliza UTF-8 (`utf8mb4`). | Implementado |
| 5 | Protección de sesiones | Se regenera el identificador de sesión al iniciar sesión. | Implementado |
| 6 | Control de acceso | El sistema restringe el acceso a usuarios no autenticados. | Implementado |
| 7 | Control de roles | Las operaciones administrativas están restringidas al rol administrador. | Implementado |
| 8 | Validación de datos | Se validan los datos recibidos antes de procesarlos. | Implementado parcialmente |
| 9 | Protección de eliminación | La eliminación de productos requiere el método POST. | Implementado |
| 10 | Manejo de errores | Se evita mostrar información interna de los errores al usuario. | Implementado |
| 11 | Protección CSRF | Protección contra solicitudes falsificadas entre sitios. | Implementado |

### Conclusión

El sistema Manos de Chichi incorpora medidas de seguridad orientadas a proteger las credenciales de los usuarios, controlar los permisos de acceso y reducir riesgos asociados al manejo de información. Se identificó como mejora pendiente la implementación de protección CSRF en los formularios que modifican información.