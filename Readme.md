# Versión de React & Symfony del popular juego Tic-Tac-Toe o Tres en Raya
## 1.	Introducción
### 1.1 Frontend
1. El FrontEnd está diseñado con React para las peticiones a la API y SweetAlert2 para mostrar 
las notificaciones.
2. En React se usan componentes funcionales y Hooks para el manejo de estados.

### 1.2 Backend 
1. El Backend está diseñado con Symfony para la gestión de toda la lógica 
del juego. 
2. En el Backend se separa la lógica en las siguientes capas: 
Entidades, Controladores, Repositorios y Servicios. Esto ayuda a una mejor separación 
de responsabilidades, mejor testeabilidad, mayor reutilización de código y por ende
es más fácil de refactorizar y escalar.
3. Adicionalmente se usa un Trait que nos facilitará la vida en los Mock de los Tests.

### 1.3 Nota adicional
1. Para la versión del juego contra la computadora se hizo una adaptación a PHP 
del algoritmo de AI conocido como Minimax (`AIGameService.php` ):
> Con cada jugada del jugador humano la AI sigue jugando adelante hasta que alcanza 
> una disposición terminal del tablero (estado terminal) que resulta en un empate, 
> una victoria o una pérdida. Una vez en un estado terminal, la AI asignará un 
> puntaje positivo arbitrario (+10) para una victoria, un puntaje negativo (-10) 
> para una pérdida o un puntaje neutral (0) para un empate.
> 
> Al mismo tiempo, el algoritmo evalúa los movimientos que conducen a un estado 
> terminal en función del turno de los jugadores. Elegirá el movimiento con la 
> puntuación máxima cuando sea el turno de la AI y elegirá el movimiento con la 
> puntuación mínima cuando sea el turno del jugador humano. Usando esta estrategia,
> Minimax evita perder con el jugador humano.
2. La AI siempre juega en este caso con "O" y el jugador notará que demora un poco más
por el proceso de análisis de la misma.

## 2. Requisitos mínimos del sistema operativo para desplegar la aplicación
* PHP >= 7.4.6
* Composer >= 2.0.13
* NodeJS >= 14.17.1 || Yarn >= 1.22.17 (Recomendado) <span style="color:red">[ Opcional ]: solo si queremos hacer modificaciones al código Javascript, sino solo tenemos que usar el código ya generado para el ambiente de producción que ya se encuentra disponible en el repo sin previa configuración.</span>
* Symfony CLI >= v4.28.1 <span style="color:red">[ Opcional ]: solo si queremos desplegar el proyecto en el server de prueba local provisto por Symfony.</span>
* MariaDB >= 10.4.11
* Apache >= 2.4.43 <span style="color:red">[ Opcional ]: solo si queremos desplegar el proyecto en un server diferente del server de prueba local provisto por Symfony.</span> 

## 3. Instrucciones para el despliegue de la aplicación
1. Una vez que hayamos descargado el proyecto debemos iniciar los servicios de MYSQL y Apache.
2. Crear una Base de Datos llamada: test_hiberus y correr el fichero dentro de
   ./sqldump/test_hiberus.sql en dicha Base de Datos.
3. Crear una Base de Datos llamada: test_hiberus_test (para los test funcionales) y correr el fichero dentro de
   ./sqldump/test_hiberus_test.sql en dicha Base de Datos.
4. Aquí se asume que el usuario de la Base de Datos es root, que la contraseña es vacía y que MySQL corre por el puerto 3306. De no ser así se deben editar estos valores en los ficheros .env y .env.test en la clave `DATABASE_URL`. Esta clave es igual en ambos ficheros, puesto que Symfony le adiciona el sufijo `_test` por defecto al nombre de la Base de Datos de Test.
5. En la raíz de la aplicación corremos el comando:```composer install```
6. Asumiendo que se está desplegando el proyecto en un entorno con Apache (puerto 80) y MySQL (tipo Xampp o Lampp) debemos
   configurar el `hosts`(por ejemplo a `hiberus.test`) y posteriormente configurar el virtual host(`httpd-vhosts.conf`) con una configuración similar a la de la siguiente imagen extrapolándolo al Sistema Operativo que estemos usando.

![alt text for screen readers](./assets/images/httpd-vhosts.png "httpd-vhosts.conf")
6.1 Posteriormente debemos reiniciar el servicio de Apache y poner en el navegador la url http://hiberus.test
7. Asumiendo que estemos desplegando el proyecto en un entorno local con el servidor de prueba de Symfony, bastará con correr el comando:```symfony server:start``` y navegar hacia la URL que nos indica el propio servidor de prueba.

## 4. Instrucciones para correr los Tests
##### El proyecto tiene configurado un grupo de Tests que podemos encontrar en el directorio `tests`
1. Para ejecutar todos los Tests podemos ejecutar el comando: `php vendor/bin/phpunit`
2. Para ejecutar solo los tests unitarios podemos ejecutar el comando: `php vendor/bin/phpunit --testsuite Unit`
3. Para ejecutar solo los tests funcionales podemos ejecutar el comando: `php vendor/bin/phpunit --testsuite Functional`

## 5. Instrucciones para el uso de la aplicación
1. En la pantalla inicial se muestran dos botones; el primero de izquierda a derecha
   es para crear un nuevo juego entre jugadores alternos en la misma pc, y el segundo es
   para crear un nuevo juego de un jugador(*en este caso el jugador X*) contra la computadora
   (*en este caso el jugador O*).

![alt text for screen readers](./assets/images/pantallaInicial.png "Pantalla Inicial")

2. Una vez que se inicie el juego, en cualquiera de sus dos modalidades se mostrará
una notificación como la de la siguiente imagen indicando que ya un nuevo juego fue
persistido en la base de datos.

![alt text for screen readers](./assets/images/juegoCreado.png "Juego creado exitosamente")

3. A medida que se va jugando se puede ver en la parte inferior del tablero de juego
un resumen del desempeño histórico de ambos jugadores e inmediatamente debajo el turno
del jugador actual. Todo esto se actualiza en tiempo real.
![alt text for screen readers](./assets/images/transcursodelJuego.png "Transcurso del Juego")

4. Una vez que termina el juego, se muestra una notificación indicando que el juego
finalizó con el resultado del mismo y si encima de este resultado se intenta jugar nos
lanza un error:

![alt text for screen readers](./assets/images/ganoUnJugador.png "Gano un Jugador")
![alt text for screen readers](./assets/images/errorYaGanoUnJugador.png "Ya gano un Jugador")

4.1 Si nos fijamos una vez que finaliza un juego aparece un botón al final de la página
para volver a jugar el cual nos llevará a la pantalla inicial.

5. Si intentamos jugar una casilla por segunda vez nos lanza un error:
![alt text for screen readers](./assets/images/casillaJugada.png "Casilla jugada anteriormente")