<?php

#load the drupal session
define('DRUPAL_ROOT', dirname(dirname(__DIR__)));
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
#note that loading the session (to check the user) relies on setting the cookie_domain in settings.php
global $user;


$f3=require('lib/fatfree/lib/base.php');
require_once('http_routes.php');
require_once('functions.php');

$f3->set('DEBUG',3);

$f3->config('config.ini');
$f3->config('secrets.ini');

if ($user->uid) #uid is 0 when user is not logged in
{
	$f3->set('user', $user);
}
else
{
	$f3->error(403, 'You must be logged in to use this service');
}

$db=new DB\SQL(
	'mysql:host='.$f3->get('db_host').';port=3306;dbname='.$f3->get('db_database'),
	$f3->get('db_username'),
	$f3->get('db_password')
);
$f3->set('db', $db);

#get module ID from module request param
$module_id = $f3->get('REQUEST.module');
if ($module_id)
{
	$module_node = node_load($module_id);
	if ($module_node)
	{
		$f3->set('module_id', $module_id);
		$f3->set('module_name', $module_node->title);

		#switch the attempt number if it is supplied as a parameter
		$attempt_number = $f3->get('REQUEST.attempt_number');
		if (!$attempt_number)
		{
			$attempt_number = _get_module_attempt_number($f3);
		}
		_set_module_attempt_number($f3, $attempt_number);
	}
	else
	{
		$f3->error(400,'Bad module ID');
		exit;
	}
}



$f3->run();
