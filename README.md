# Instalación

1. Se debe crear un archivo `.env` en la raíz del proyecto y agregar las siguientes variables de entorno:

```
APP_PORT = <PUERTO_DEL_SERVIDOR_PHP>

# Database credentials
DB_HOST = mysql
DB_NAME = <NOMBRE_DE_LA_BASE_DE_DATOS>
DB_USER = <USUARIO_PARA_LA_BASE_DE_DATOS>
DB_PASSWORD = <CONTRASEÑA_PARA_LA_BASE_DE_DATOS>
DB_PORT = <PUERTO_PARA_EXPONER_LA_BD>

# PhpMyAdmin credentials
PHPMYADMIN_PORT = <PUERTO_PARA_EXPONER_PHPMYADMIN>
```

2. En la raíz del proyecto, ejecutar el comando:

```
docker compose up
```

3. Esperar a que el contenedor `mysql` arroje el siguiente log:

```
[Server] /usr/sbin/mysqld: ready for connections. Version: '8.0.32'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server - GPL.
```

4. Abrir el navegador preferido y acceder al link `localhost:<PUERTO_DEL_SERVIDOR_PHP>`.

5. Para iniciar sesión como gerente, se debe ingresar el email `gerente@gerente.com` con contraseña `gerente`.
