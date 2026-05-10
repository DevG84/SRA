# Sistema de Regularización Académica
Sistema de Regularización Académica es un sistema diseñado para los alumnos de la Escuela Superior de Cómputo (ESCOM - IPN) para consultar información relacionada a los Exámenes a Título de Suficiencia (ETS).

## Instrucciones

¡Hola! Aquí voy a definir como vamos a trabajar sobre este repositorio para evitar conflictos de versiones o cosas por el estilo. Es importante que todos nos mantengamos en el mismo canal para no entrar en desesperación más tarde.

Principalmente usaremos **git**, aquí hay unos comandos clave que usaremos diariamente:
* `git add [archivos]` se usa para preparar los cambios que quieres guardar (puedes usar `git add .` para incluir todo).
* `git commit -m "[mensaje]"` se usa para confirmar tus cambios con una descripción breve. Usaremos el estándar de redacción de commits:
    * **Redacción:** Usaremos verbos en presente o infinitivo (ej. "Agregar", "Corregir").
    * **Formato:** Se redactará de la forma `tipo: descripción en minúsculas (en inglés o español)`.
    * **Nomenclaturas comunes:**
        * `feat`: (feature) Para nuevas funcionalidades o páginas.
        * `fix`: Para corrección de errores o bugs.
        * `docs`: Para cambios en documentación o el README.
        * `style`: Para ajustes visuales, CSS o formato de código.
        * `refactor`: Para mejorar código existente sin cambiar su función.
* `git push origin nombre_rama` se usa para subir tus avances al repositorio en la nube.
* `git checkout` se usa para navegar entre ramas:
    * `git checkout [rama]`: Entrar a una rama que ya existe.
    * `git checkout -b [nueva_rama]`: Crear una rama nueva y entrar en ella.

### Sobre las ramas

Para que no generar conflictos entre nosotros, manejaremos este flujo de trabajo:

1. **`main`**: Es la rama principal. Solo contiene el código estable. **Nadie trabajará directamente aquí.**
2. **`dev`**: Es nuestra rama de integración. Aquí uniremos lo que cada quien termine para probar que todo funcione bien.
3. **Ramas de tarea**: Cada vez que hagas algo nuevo crea una rama nueva desde `dev` describiendo brevemente en que consiste tu tarea:
    * Comando: `git checkout -b nombre-de-tu-tarea`

### Flujo de trabajo (diario)

1. **Actualizar:** Antes de empezar a programar, trae a tu rama local lo que los demás subieron:  
   `git checkout dev` + `git pull origin dev`
2. **Trabajar:** Crea tu rama y agrega tu nueva función.
3. **Subir:** Cuando termines, sube tu rama:  
   `git push origin nombre-de-tu-rama`
4. **Pull Request:** En GitHub, pide unir tu rama a `dev`. Nos organizaremos sobre la revisión del código entre todos.

### Reglas

* **Configuración de XAMPP:** Estamos usando un archivo `.htaccess` para tener URLs limpias (sin la extensión `.php` ni `.html`). Si te sale error 404, ve a tu panel de XAMPP -> **Apache (httpd.conf)** y cambia `AllowOverride None` por `AllowOverride All` y reinicia el servidor.
* **Rutas:** Nunca usen rutas absolutas (ej: `C:/xampp/...`). Usen siempre **rutas relativas** para que el proyecto funcione igual en todas nuestras computadoras.
* **Archivos basura:** No suban las carpetas de configuración `.vscode/` o `.idea/` ni archivos temporales. Para eso está configurado el archivo `.gitignore`, procuren usar siempre los comandos en la terminal de git (**git bash**).

---
*Cualquier duda, aclaración u objeción respecto a este flujo de trabajo lo podemos discutir en equipo, por el momento esto es solo mi propuesta.*