<?php

#if we're viewing the attempt we're currently filling out
function _current_attempt_active($f3)
{
	$current_attempt = current_attempt($f3);
	$session_attempt = _get_module_attempt_number($f3);
	if ($current_attempt->attempt_number == $session_attempt)
	{
		return true;
	}
	return false;
}

function _set_module_attempt_number($f3, $attempt_number)
{
	$module_id = $f3->get('module_id');

	if ($attempt_number == 'current_attempt')
	{
		$current_attempt = current_attempt($f3, $module_id);
		$attempt_number = $current_attempt->attempt_number;
	}

	$attempts_by_module;
	if ($f3->exists('SESSION.attempts_by_module'))
	{
		$attempts_by_module = unserialize($f3->get('SESSION.attempts_by_module'));
	}
	else
	{
		$attempts_by_module = array();
	}

	$attempts_by_module[$module_id] = $attempt_number;
	$f3->set('SESSION.attempts_by_module', serialize($attempts_by_module));
}

function _get_module_attempt_number($f3, $module_id = NULL)
{
	if (!$module_id)
	{
		$module_id = $f3->get('module_id');
	}

	if ($f3->exists('SESSION.attempts_by_module'))
	{
		$attempts_by_module = unserialize($f3->get('SESSION.attempts_by_module'));
		if (array_key_exists($module_id, $attempts_by_module))
		{
			return $attempts_by_module[$module_id];
		}
	}
	$current_attempt = current_attempt($f3, $module_id);
	return $current_attempt->attempt_number; 

}


function all_user_attempts($f3, $username)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempts = $attempt->find(array('user=?',$username),array('order' => 'module_id, attempt_number DESC'));

	return $attempts;
}


function _latest_attempt($f3, $module_id)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempts = $attempt->find(array('user=? and module_id=?',$f3->get('user')->name, $module_id),array('order' => 'attempt_number DESC','limit' => 1));

	if ($attempts)
	{
		return array_shift($attempts);
	}
	return NULL;
}

function _create_new_attempt($f3, $module_id, $attempt_number, $module_name)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$attempt->attempt_number = $attempt_number;
	$attempt->user = $f3->get('user')->name;
	$attempt->module_id = $module_id;
	$attempt->module_name = $f3->get('module_name');
	$attempt->date_started = date("Y-m-d H:i:s");
	$attempt->date_completed = NULL;

	$attempt->save();

	return $attempt;
}

#loads the current user's current attempt number,
#creating one if there is no current attempt
function current_attempt($f3, $module_id = NULL)
{
	if (!$module_id)
	{
		$module_id = $f3->get('module_id');
	}

	$attempt = _latest_attempt($f3, $module_id);

	if (!$attempt)
	{
		return _create_new_attempt($f3, $module_id, 1, $f3->get('module_name'));
	}

	if ($attempt->date_completed == NULL)
	{
		return $attempt;
	}
	return _create_new_attempt($f3, $attempt->module_id, $attempt->attempt_number+1, $attempt->module_name);
}



function get_activity_answers($f3)
{
	if (!$f3->exists('module_id') || !$f3->exists('REQUEST.actid'))
	{
		$f3->error(400,'A module and an actid must be provided');
		exit;
	}

	$module_id = $f3->get('module_id');
	$module_name = $f3->get('module_name');
	$actid = $f3->get('REQUEST.actid');

	$attempt_number = _get_module_attempt_number($f3);
	
	$answer = new DB\SQL\Mapper($f3->get('db'), $f3->get('answer_table'));
	
	$answers = $answer->find(array('user=? and module_id=? and actid=? and attempt_number=?',$f3->get('user')->name, $module_id, $actid, $attempt_number),array('order_by' => 'qid,subqid'));

	return $answers;
}

#return the node of the bookmarked page in the module, or the module ID.
function module_bookmarked_page_path($f3, $module_id, $attempt_number)
{
	$user = $f3->get('user')->name;
	
	$bookmark = new DB\SQL\Mapper($f3->get('db'), $f3->get('bookmark_table'));
	$bookmark->load(array('user=? AND module_id=? AND attempt_number=?', $user, $module_id, $attempt_number));

	if ($bookmark->current_node)
	{
		return $bookmark->current_node;
	}
	return 'node/' . $module_id;
}



function dump_request($f3)
{
	error_log(print_r($f3->get('REQUEST'),True));
}






