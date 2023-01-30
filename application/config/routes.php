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
// $route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ----------------- オーダー管理 ----------------- //
$route['order/order_list/([1-9][0-9]*)'] = 'order/order_list/index/$1';
$route['order/open_waiting/([1-9][0-9]*)'] = 'order/open_waiting/index/$1';
$route['order/order_input_list/([1-9][0-9]*)'] = 'order/order_input_list/index/$1';
$route['order/order_input_list/([1-9][0-9]*)/([1-9][0-9]*)'] = 'order/order_input_list/index/$1/$2';
$route['order/order_edit/([1-9][0-9]*)'] = 'order/order_edit/index/$1';
$route['order/order_edit/([1-9][0-9]*)/([1-9][0-9]*)'] = 'order/order_edit/index/$1/$2';
$route['order/new_order/([1-3])'] = 'order/new_order/index/$1';

// ----------------- インベントリ管理 ----------------- //
$route['inventory/sim_stock/([1-9][0-9]*)'] = 'inventory/sim_stock/index/$1';

$route['default_controller'] = 'home';


