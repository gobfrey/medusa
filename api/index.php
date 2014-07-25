<?php

#load the drupal session
define('DRUPAL_ROOT', dirname(dirname(__DIR__)));
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
#note that loading the session (to check the user) relies on setting the cookie_domain in settings.php
global $user;


$f3=require('lib/fatfree/lib/base.php');

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

#get the key parameters
$module = $f3->get('REQUEST.module');
if ($module)
{
	$parts = explode(':', $module, 2);
	$f3->set('module_id', $parts[0]);
	$f3->set('module_name', $parts[1]);
}


function my_medusa($f3)
{
	$username = $f3->get('user')->name;

	$attempts = all_user_attempts($f3, $username);

	$f3->set("attempts", $attempts);
	
	echo(Template::instance()->render("my_medusa.htm"));
	exit;

}

function all_user_attempts($f3, $username)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempts = $attempt->find(array('user=?',$username),array('order' => 'module, attempt_number DESC'));

	return $attempts;
}

#loads the current user's current attempt number,
#creating one if there is no current attempt
function current_attempt($f3, $module_id, $module_name)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempts = $attempt->find(array('user=? and module_id=?',$f3->get('user')->name, $module_id),array('order' => 'attempt_number DESC','limit' => 1));

	$attempt_number = 0;
	if ($attempts)
	{
		$latest_attempt = $attempts[0];
		$attempt_number = $latest_attempt->attempt_number;

		if ($latest_attempt->date_completed == NULL)
		{
			return $attempt_number;
		}
	}

	$attempt_number++;

	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempt->attempt_number = $attempt_number;
	$attempt->user = $f3->get('user')->name;
	$attempt->module_id = $module_id;
	$attempt->module_name = $module_name;
	$attempt->date_started = date("Y-m-d H:i:s");
	$attempt->date_completed = NULL;

	$attempt->save();

	return $attempt_number;
}



function get_activity_answers($f3)
{
	if (!$f3->exists('module_id') || !$f3->exists('REQUEST.actid'))
	{
		$f3->error(400,'A module and an actid must be provided');
		exit;
	}

	$module_id = $f3->get('module_id');
	$actid = $f3->get('REQUEST.actid');
	$attempt_number = current_attempt($f3, $module_id, $module_name);
	
	$answer = new DB\SQL\Mapper($f3->get('db'), $f3->get('answer_table'));
	
	$answers = $answer->find(array('user=? and module_id=? and actid=? and attempt_number=?',$f3->get('user')->name, $module_id, $actid, $attempt_number),array('order_by' => 'qid,subqid'));

	return $answers;
}

function get_answer($f3)
{
#	$answers = get_activity_answers($f3);

	#convert to export format and sent to flash

}

function save_answer($f3)
{
	$module_id = $f3->get('module_id');
	$module_name = $f3->get('module_name');
	$actid = $f3->get('REQUEST.actid');
	if (!$module_id)
	{
		$f3->error(400,'A module number must be provided');
		exit;
	}

	$answer = new DB\SQL\Mapper($f3->get('db'), $f3->get('answer_table'));

	#each column name is the name of a parameter, so try to fill them in
	foreach ($answer->fields as $field_name => $field_meta)
	{
		if ($f3->exists("REQUEST.$field_name"))
		{
			$val = $f3->get("REQUEST.$field_name");
			$type = $field_meta['type'];
			$answer->$field_name = $val;
		}
	}

	$answer->module_id = $module_id;
	$answer->module_name = $module_name;
	$answer->attempt_number = current_attempt($f3, $module_id, $module_name);
	$answer->date = date("Y-m-d H:i:s");
	$answer->user = $f3->get('user')->name;

	$answer->save();
}

function session_exists($f3)
{
	#should return false if the user is not logged in.  This is handled with a 403 before routing if there's no user.
}

function get_all_user_answer($f3)
{
	dump_request($f3);
}

function clear_answer($f3)
{
	error_log('CLEAR ANSWER CALLED');
	$answers = get_activity_answers($f3);

	foreach ($answers as $a)
	{
		$a->erase();
	}
}

function dump_request($f3)
{
	error_log(print_r($f3->get('REQUEST'),True));
}





$f3->run();
