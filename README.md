<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Título del proyecto: Sistema web para el seguimiento y acompañamiento de graduados(SAGIS)

## Tabla de Contenido
1. [Descripción](#descripción)
2. [Módulos del sistema](#módulos-del-sistema)
3. [Tecnologías](#tecnologías)
4. [IDE](#ide)
5. [Instalación](#instalación)
6. [Autores](#autores)
7. [Institución Académica](#institución-académica)
8. [Prueba de Calidad - Sonarcloud](#prueba-de-calidad---sonarcloud)

___
### Descripción: 

El sistema web SAGIS  permite el seguimiento y acompañamiento de los graduados del programa de ingeniería de sistemas, en el que los graduados recibirán invitaciones al correo electrónico con las credenciales de inicio de sesión para que ingresen, actualicen datos de forma obligatoria y se enteren de todas las noticias y novedades de interés, además de estar en contacto con el programa. Los graduados pueden actualizar sus datos personales, académicos, laborales y revisar la bolsa de empleo para optar por opciones de empleo disponibles.

Por parte del administrador del sistema, es decir, el director del programa puede consultar y visualizar la información específica de cada graduado, además de cargar y actualizar la información básica del sistema, consultar informes, actualizar la sección de noticias, publicaciones y galería y lo más importante la opción de agregar los graduados al sistema. 

___
### Módulos del sistema:

El sistema web SAGIS cuenta con 2 módulos:

* Módulo Administrativo (Director del programa)

* Módulo de Graduados.

___
### Tecnologías:
#### Frontend

- [HTML5](https://developer.mozilla.org/es/docs/Glossary/HTML5 "HTML5") : es un estándar que sirve como referencia del software que conecta con la elaboración de páginas web en sus diferentes versiones, define una estructura básica y un código (denominado HTML) para la definición de contenido de una página web, como texto, imágenes, vídeos, juegos, entre otros. 
- [JavaScript](https://developer.mozilla.org/es/docs/Learn/JavaScript/First_steps/Qu%C3%A9_es_JavaScript "JavaScript"):  es un lenguaje de programación o de secuencias de comandos que te permite implementar funciones complejas en páginas web, cada vez que una página web hace algo más que sentarse allí y mostrar información estática para que la veas, muestra oportunas actualizaciones de contenido, mapas interactivos, animación de Gráficos 2D/3D, desplazamiento de máquinas reproductoras de vídeo, etc.
- [CSS3](https://desarrolloweb.com/manuales/css3.html "CSS3"): sirve para definir la estética de un sitio web en un documento externo y eso mismo permite que modificando ese documento (la hoja CSS) podemos cambiar la estética entera de un sitio web, el mismo sitio web puede variar totalmente de estética cambiando solo la CSS, sin tocar para nada los documentos HTML o jsp o asp que lo componen.
- [Bootstrap](https://getbootstrap.com/ "Bootstrap"): es un framework front-end utilizado para desarrollar aplicaciones web y sitios mobile first, o sea, con un layout que se adapta a la pantalla del dispositivo utilizado por el usuario. En este caso se usa Admin LTE que es una plantilla de panel de administración que utiliza varias tecnologías combinadas tales como HTML, Bootstrap, jQuery, etc, proporcionando una gama de componentes receptivos, reutilizables y de uso común que se utilizan para desarrollar cualquier tipo de software online que requiera de un panel de control. Se pueden usar plugins como gráficas, editores, elementos de formularios, paquetes de íconos, Table Grids, notificaciones y muchos otros que son de gran utilidad. 
- [Blade](https://laravel.com/docs/8.x/blade "Blade"): es un motor de plantillas simple y a la vez poderoso proporcionado por Laravel. A diferencia de otros motores de plantillas populares de PHP, Blade no  impide utilizar código PHP plano en tus vistas. De hecho, todas las vistas de Blade son compiladas en código PHP plano y almacenadas en caché hasta que sean modificadas, lo que significa que Blade no añade sobrecarga a la aplicación. Los archivos de las vistas de Blade tienen la extensión .blade.php y son usualmente almacenados en el directorio resources/views. Esta librería permite realizar todo tipo de operaciones con los datos, además de la sustitución de secciones de las plantillas por otro contenido, herencia entre plantillas, definición de layouts o plantillas base, etc.
- [JQuery](https://jquery.com "JQuery"): es un software libre y de código abierto (posee un doble licenciamiento bajo la Licencia MIT y la Licencia Pública General de GNU v2). Cuenta con un diseño que facilita la navegación por un documento y seleccionar elementos DOM proporcionando a los desarrolladores de aplicaciones web complementos que agilizan el desarrollo de proyectos. Esto permite a los desarrolladores centrarse en lo importante y crear abstracciones para interacción y animación de bajo nivel, efectos avanzados y widgets temáticos de alto nivel sin invertir tiempo en desarrollar complejos algoritmos y métodos que los controlen desde cero y generando menos código que las aplicaciones hechas con JS puro. Por ese motivo jQuery es muy popular y podemos verlo en muchas páginas web.

#### Backend

- [Laravel 8](https://laravel.com/docs/8.x "Laravel 8") : Laravel es un framework de PHP para ayudarnos en un tipo de desarrollo sobre aplicaciones escritas en este lenguaje de programación. Esté framework o más bien podría llamarlo compañero de ahora en adelante, nos ayuda en muchas cosas al desarrollar una aplicación, por medio de sus sistema de paquetes y de ser un framework del tipo MVC (Modelo-Vista-Controlador) da como resultado que podamos “despreocuparnos” (por así decirlo) en ciertas aspecto del desarrollo, cómo instanciar clases y métodos para usarlos en muchas partes de nuestra aplicación sin la necesidad de escribirlo y repetirlos muchas veces con lo que eso conlleva a la hora de modificar algo en el código.
- [Laragon](https://laragon.org/ "Laragon"):  es una suite de desarrollo para PHP que funciona sobre Windows diseñado especialmente para trabajar con Laravel. Similar a otras herramientas como Xampp o Wampp, Laragon nos permite crear un entorno de desarrollo con estas características:
Cmder (Consola para Windows),  Git, Node.js, npm ,SSH, Putty, PHP 7 / 5.6, Extensiones de PHP, xDebug, Composer, Apache, MariaDB/MySQL, phpMyAdmin, Soporte para Laravel y Lumen, Gestión automática de Virtualhosts.
- [MySQL 5.5](https://www.mysql.com/ "MySQL 5.5"): MySQL es un sistema de gestión de bases de datos relacional desarrollado bajo licencia dual: Licencia pública general/Licencia comercial por Oracle Corporation y está considerada como la base de datos de código abierto más popular del mundo,​ y una de las más populares en general junto a Oracle y Microsoft SQL Server, todo para entornos de desarrollo web. Permite almacenar y acceder a los datos a través de múltiples motores de almacenamiento, incluyendo InnoDB, CSV y NDB. MySQL también es capaz de replicar datos y particionar tablas para mejorar el rendimiento y la durabilidad.
- [HeidiSQL](https://www.heidisql.com/ "HeidiSQL"): inicialmente conocido como MySQL-Front, es un software libre y de código abierto que permite conectarse a MySQL (y sus derivaciones como MariaDB y Percona Server), así como Microsoft SQL Server y PostgreSQL.
MySQL-Front comenzó a ser desarrollado en Delphi por el programador alemán Ansgar Becker, quién por motivos personales dejó el proyecto sin terminar. Más tarde el desarrollador alemán Nile Hoyer contactó a Ansgar y adquirió los derechos para utilizar el nombre "MySQL-Front" en su propio proyecto, sin embargo tuvo que cancelarlo porque surgió una infracción de derechos de autor con MySQL Labs sobre el uso del nombre "MySQL". Finalmente, Ansgar y otros colaboradores retomaron el proyecto MySQL-Front renombrándolo HeidiSQL.
Para administrar las bases de datos con HeidiSQL, los usuarios deben iniciar una sesión en un servidor MySQL local o remoto. Sus características permiten realizar las operaciones de base de datos más comunes y avanzadas, sin embargo aún sigue en desarrollo a fin de integrar la máxima funcionalidad que se espera en una interfaz de base de datos de SQL.


___
### IDE:

- El proyecto se desarrolla con la utilización de Visual Studio Code.

___
### Instalación:

#### Requisitos previos
1. Es necesario tener en el sistema operativo composer de manera global
2. Tener instalado GIT
3. Contar con un entorno de desarrollo como Xamp, Wamp o Laragon

#### Pasos de instalación

##### 1. Clonar el repositorio del proyecto en Laravel
Para clonar el proyecto abre una terminal o consola de comandos y escribe la siguiente nomenclatura, esto es después de la instrucción git clone agrega tu dirección:

```sh
git clone https://github.com/JarlinFonseca/SAGIS.git
```

##### 2. Instalar dependencias del proyecto
Cuando guardas tu proyecto Laravel en un repositorio GIT, en el archivo .gitignore se excluye la carpeta vendor que es donde están las librerías que usa tu proyecto, es por eso que se debe correr en la terminal una instrucción que tome del archivo composer.json todas las referencias de las librerías que deben estar instaladas en tu proyecto.

Ingresa desde la terminal a la carpeta del proyecto SAGIS y escribe:

```sh
composer install
```
Este comando instalará todas las librerías que están declaradas para tu proyecto.

##### 3. Generar archivo .env
Por seguridad el archivo .env está excluido del repositorio, para generar uno nuevo se toma como plantilla el archivo .env.example para copiar este archivo en una nuevo escribe en tu terminal:

```sh
cp .env.example .env
```

##### 4. Generar Key
Para que el proyecto en Laravel corra sin problemas es necesario generar una key de seguirdad, para ello en tu terminal corre el siguiente comando:

```sh
php artisan key:generate
```
Esta key nueva se agregará a tu archivo .env

##### 5. Crear base de datos (Omitir este paso, si ya has creado la BD)
Sí tu proyecto en Laravel funciona haciendo consultas a una base de datos entonces tienes que crear una nueva base de datos, la forma más rápida para crearla es desde la terminal:
```sh
mysql -u root -p

CREATE DATABASE nombreDeTuDBAqui CHARACTER SET utf8 COLLATE utf8_spanish_ci;
```
Para salir de la consola de MySQL solo escribe ‘exit’ y tecla Enter.

##### 6. Agregar información de variables globales
En el archivo .env se guardan todas la variables globales de distintos servicios que necesita tu proyecto para funcionar sin errores. Ah! y no te olvides agregar los datos de la base de datos que creaste en el punto anterior como es el nombre y contraseña.
También si tu proyecto va a estar mandando e-mails para informar distintas acciones que suceden en el sistema, necesitas configurar el cliente de correo que usaras para esto, como en este proyecto se uso Sengrid.

##### 7. Crear vínculo simbólico
Sí tu proyecto guarda algún tipo de archivo como imágenes, pdf’s etc., necesitas desde la consola de comandos crear un vínculo o enlace simbólico de la carpeta public a la carpeta storage para que tu sistema pueda tener acceso a los archivos, desde tu terminal teclea:
```sh
php artisan storage:link
```
##### 8. Composer dump-autoload
Sí en tu proyecto creaste nuevas clases como helpers tienes que correr este comando para que se agreguen al cargador automático de clases de otra manera cuando algún método mande a llamar estás clases te arrojará un error:
```sh
composer dump-autoload
```
##### 9. Correr migraciones y seeds

###### 9.1 Sí tu proyecto no usa los seeds para sembrar datos en la base de datos solo corre el comando:
```sh
php artisan migrate
```
###### 9.2 Sí tu proyecto cuenta con seeders y factories para poblar ciertas tablas en tu base de datos como usuarios para tu sistema escribe en la terminal:
```sh
php artisan migrate --seed
```
###### 9.3 El comando migrate:fresh eliminará todas las tablas de la base de datos y luego ejecutará el comando migrate:
```sh
php artisan migrate:fresh --seed
```
##### 10. Comando para optimizar el rendimiento

```sh
php artisan optimize
```

##### 11. Comando para ver las rutas del aplicativo
El comando route:list se puede utilizar para mostrar una lista de todas las rutas registradas para la aplicación. Este comando mostrará el dominio, método, URI, nombre, acción y middleware de las rutas que incluye en la tabla generada.

```sh
php artisan route:list
```
De acuerdo a lo anterior, son los pasos generales para clonar e instalar un proyecto de Laravel, para este proyecto SAGIS es OBLIGATORIO seguir los siguientes pasos: 1, 2, 3, 4, 5, 6, 7, 8, 9.1,9,2, 10 y 11.
___

### Autores:

1. Jarlin Andres Fonseca Bermón 1151758 - jarlinandresfb@ufps.edu.co
2. Junior Yoel Castilla Osorio 1151755 - osoriojunioryoelc@ufps.edu.co
3. Manuel Felipe Mora Espitia 1151863 - manuelfelipeme@ufps.edu.co
4. Camilo Alonso Gomez Castellanos - camiloalonsogoca@ufps.edu.co
5. Fabian Steven Reyes Gonzales - fabianstevenrego@ufps.edu.co

___
### Institución Académica:
Proyecto desarrollado en las materias de Análisis y Diseño de Sistemas, Ingeniería de Software [Programa de Ingeniería de Sistemas](https://ingsistemas.cloud.ufps.edu.co/ "Programa de Ingeniería de Sistemas") de la [Universidad Francisco de Paula Santander](https://ww2.ufps.edu.co/ "Universidad Francisco de Paula Santander")


