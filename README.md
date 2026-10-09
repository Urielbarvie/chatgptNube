# chatgptNube

La tienda Multiventas Barvie está en la carpeta `pagina/`.

## Esta computadora

- Tienda: http://localhost:8080/chatgptNube/pagina/
- Catálogo: http://localhost:8080/chatgptNube/pagina/src/views/catalogo.php
- Panel de Strapi: http://127.0.0.1:1337/admin
- Iniciar servicios: ejecutar `pagina/iniciar-tienda.ps1` con PowerShell.

La configuración y los datos se importaron localmente. El respaldo anterior se conserva en la carpeta de trabajo de Codex.

## Trabajar desde otra computadora

1. Clonar este repositorio en `htdocs` de XAMPP: `git clone https://github.com/Urielbarvie/chatgptNube.git`.
2. Instalar Node.js 24 y habilitar PHP cURL, mbstring y pdo_sqlite.
3. Copiar `pagina/.env.example` a `pagina/.env` y `pagina/mi-proyecto-strapi/.env.example` a `pagina/mi-proyecto-strapi/.env`.
4. Configurar MySQL local y generar secretos propios para Strapi. Si se necesita el contenido existente, importar por un canal privado el respaldo `pagina/storage/strapi-database.sql`; ese respaldo no está en GitHub.
5. En `pagina/mi-proyecto-strapi`, ejecutar `npm ci` y `npm run build`.
6. Iniciar Apache y MySQL y ejecutar `npm run develop`, o usar `pagina/iniciar-tienda.ps1`.
7. Abrir `http://localhost:PUERTO/chatgptNube/pagina/`, usando el puerto de Apache de esa PC.

El código y las imágenes del catálogo se comparten en GitHub. Las credenciales, la base MySQL y el historial privado del chat no se publican. GitHub sincroniza archivos del proyecto; los cambios de productos, cuentas y pedidos se guardan en la base de Strapi. Para compartir esos cambios entre todas las computadoras hace falta un servidor Strapi y una base de datos centrales; se configura su URL en `pagina/.env`.

Para guardar cambios de código, desde la raíz del repositorio:

```powershell
git pull --ff-only
git add pagina README.md
git commit -m "Actualizar tienda"
git push origin main
```

Ver `pagina/README.md` para los detalles de la aplicación.