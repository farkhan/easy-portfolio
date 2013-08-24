<?php defined('BASEPATH') or exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// back-end
$route['easy_portfolio/admin/categories(/:any)?']				= 'admin_categories$1';
$route['easy_portfolio/admin/items/images/edit/(:any)?']			= 'admin_items/edit_image/$1';
$route['easy_portfolio/admin/items(/:any)?']						= 'admin_items$1';
$route['easy_portfolio/admin(/:any)?']							= 'admin_items$1';



// front-end
$route['easy_portfolio/(:any)/(:any)']							= 'portfolio/index/$1/$2';
$route['easy_portfolio/(:any)']									= 'portfolio/index/$1';
$route['easy_portfolio']											= 'portfolio/index';
