<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'init';

$route['ingresar']['post'] = 'login0/ingresar';
$route['salir']['post'] = 'login0/salir';
$route['nuevo_administrador']['post'] = 'login0/add_admin';
$route['nuevo_mqtt']['post'] = 'login0/add_mqtt';
$route['nuevo_invitado']['post'] = 'dashboard0/nuevo_invitado';
$route['listar_invitado']['post'] = 'dashboard0/listar_invitado';
$route['ingresar_mqtt']['post'] = 'login0/ingresar_mqtt';
$route['verificar_usuario']['get'] = 'login0/verificar_usuario';
$route['cambio_clave_invitado']['post'] = 'login0/cambio_clave_invitado';
$route['nuevo_lugar']['post'] = 'dashboard0/nuevo_lugar';
$route['listar_lugar']['get'] = 'dashboard0/listar_lugar';
$route['nuevo_topico']['post'] = 'dashboard0/nuevo_topico';
$route['listar_topico']['get'] = 'dashboard0/listar_topico';
$route['nuevo_dispositivo']['post'] = 'dashboard0/nuevo_dispositivo';
$route['listar_dispositivo']['get'] = 'dashboard0/listar_dispositivo';
$route['listar_permiso_topico']['post'] = 'dashboard0/listar_permisos_topico';
$route['setear_permiso_topico']['post'] = 'dashboard0/setear_permiso_topico';
$route['get_permiso_topico']['post'] = 'dashboard0/get_permiso_topico';
$route['nueva_camara']['post'] = 'dashboard0/nueva_camara';
$route['listar_camara']['get'] = 'dashboard0/listar_camara';
$route['estado_switch']['post'] = 'dashboard0/estado_switch';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
