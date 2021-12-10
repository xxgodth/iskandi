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
$route['default_controller'] = 'C_main';
$route['verifying'] = 'C_main/verify';
$route['main'] = 'C_main/user_page';
$route['mail'] = 'C_main/mail_page';
$route['saving_mail'] = 'C_main/save_mail';
$route['get_mail_inbox'] = 'C_main/get_mail_inbox';
$route['update_view'] = 'C_main/update_view';
$route['saving_forward'] = 'C_main/save_forward';
$route['disposisi_pdf'] = 'C_main/disposisi_pdf';
$route['company'] = 'C_main/company';
$route['saving_company'] = 'C_main/save_company';
$route['mail_level'] = 'C_main/mail_level';
$route['saving_mail_level'] = 'C_main/save_mail_level';
$route['mail_type'] = 'C_main/mail_type';
$route['saving_mail_type'] = 'C_main/save_mail_type';
$route['user'] = 'C_main/user';
$route['saving_user'] = 'C_main/save_user';
$route['user_level'] = 'C_main/user_level';
$route['saving_user_level'] = 'C_main/save_user_level';
$route['disposisi'] = 'C_main/disposisi';
$route['saving_disposisi'] = 'C_main/save_disposisi';
$route['saving_app_info'] = 'C_main/save_app_info';
$route['saving_user_profile'] = 'C_main/save_user_profile';
$route['get_user_list'] = 'C_main/get_user_list';
$route['get_opt_disposisi'] = 'C_main/get_opt_disposisi';
$route['mail_history'] = 'C_main/mail_history';
$route['saving_forward_kepala'] = 'C_main/save_forward_kepala';
$route['download_rekap'] = 'C_main/download_rekap';
$route['mail_outbox'] = 'C_main/mail_outbox';
$route['saving_mailoutbox'] = 'C_main/save_mailoutbox';
$route['logout'] = 'C_main/logout';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
