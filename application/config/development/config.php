<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try to guess the protocol and
| path to your installation, but due to security concerns the hostname will
| be set to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/
$config['base_url'] = '';
$httpRoot  = "http://".$_SERVER['HTTP_HOST'];
$httpRoot .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$httpRoot = rtrim($httpRoot, '/'); /** */
$config['base_url'] = $httpRoot;

define('PATH_ASSETS', $config['base_url'] . '/assets/');

/*
|--------------------------------------------------------------------------
| Page Title
|--------------------------------------------------------------------------
**/
$config['title']      = '維聖班務系統';
$config['title_mini'] = '聖';
$config['title_lg']   = '維聖';

/*
|--------------------------------------------------------------------------
| Fake session config
|--------------------------------------------------------------------------
| The fake is temp for 2023 program. Try to format on $this->flags.
| The flags is MY_Controller protected array, It's for Old(Fet) controllers authority.
| Updated May2023.
**/
$config['flag_name'] = 'flags';
$config['flag_site'] = 'admin';
// | Session Admin Fields
$config['flag_session_userdata_fields'] = [
    'id' => 'flag_site+core_session_id',
    'user' => 'user',
    'login_check' => 'is_login',
    'permission' => 'permission'
];

$config['set_flag_wrapper'] = 'Auth/setOldFlags';
