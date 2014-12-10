<?php

function _module_report($f3, $form_values)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$where = array();
	$where[] = 'date_started>=? AND date_started<=?';
	$where[] = $form_values['report_date_from'];
	$where[] = $form_values['report_date_to'];
	if ($form_values['report_module'] != 'all_modules')
	{
		$where[0] .= ' AND module_id=?';
		$where[] = $form_values['report_module'];
	}

	$attempts = $attempt->find($where);

	$data = array();
	foreach ($attempts as $att)
	{

		$module_name = $att->module_id;

		#if performance becomes an issue, this line can be run once rather than every time
	        $module_node = node_load($att->module_id);
		if ($module_node)
		{
			$module_name = $module_node->title;
		}

		if (!array_key_exists($module_name, $data))
		{
			$data[$module_name] = array(
				'started' => 0,
				'completed' => 0,
				'unique_users' => array()
			);
		}

		$data[$module_name]['started']++;

		if ($att->date_completed)
		{
			$data[$module_name]['completed']++;
		}

		if (!array_key_exists($att->user, $data[$module_name]['unique_users']))
		{
			$data[$module_name]['unique_users'][$att->user] = 0;
		}
		$data[$module_name]['unique_users'][$att->user]++;

		#fill in module attempts


	}

	#work out averages
	foreach (array_keys($data) as $module_name)
	{
		$sum = 0;
		$count = 0;
		foreach (array_values($data[$module_name]['unique_users']) as $user_count)
		{
			$sum += $user_count;
			$count++;
		}
		$data[$module_name]['average_attempts'] = $sum / $count;
	}

	return $data;
}

function _person_report($f3, $form_values)
{
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));

	$where = array();
	$where[] = 'date_completed>=? AND date_completed<=?';
	$where[] = $form_values['report_date_from'];
	$where[] = $form_values['report_date_to'];
	if ($form_values['report_module'] != 'all_modules')
	{
		$where[0] .= ' AND module_id=?';
		$where[] = $form_values['report_module'];
	}

	$attempts = $attempt->find($where,array('order' => 'module_id, user, attempt_number'));

	$data = array();
	foreach ($attempts as $att)
	{
		$row = array();
		$row[] = $att->user;

		$name = $att->user;
		$user_obj = user_load_by_name($att->user);
		if ($user_obj && $user_obj->name == $att->user)
		{
			$name = $user_obj->field_name_from_ldap[LANGUAGE_NONE][0]['value'];
		}
		$row[] = $name;

		$row[] = date("Y-m-d",strtotime($att->date_completed));

		$module_name = $att->module_id;

		#if performance becomes an issue, this line can be run once rather than every time
	        $module_node = node_load($att->module_id);
		if ($module_node)
		{
			$module_name = $module_node->title;
		}

		$data[$module_name][] = $row;
	}
	return $data;
}


function _report_form($f3)
{
	$form_url = $f3->get('api_base') . $f3->get('PARAMS.0');

	$form = new FloraForm(array('action' => $form_url));

	$modules = array( "all_modules" => "All Modules"); #get the modules and put them in here
	foreach (_all_active_module_ids_and_names($f3) as $id => $name)
	{
		$modules[$id] = $name;
	}

	$config = array(
		array( "type" => "CHOICE", "mode"=>"pull-down", "choices" => $modules, "id" => "report_module", "title" => "Module" ),
		array( "type" => "CHOICE", "choices" => array( 'person' => 'Person Report', 'module' => 'Module Report'), "id" => "report_report", "title" => "Report" ),
		array( "type" => "DATE", "id" => "report_date_from", "title" => "Date From"),
		array( "type" => "DATE", "id" => "report_date_to", "title" => "To"),
		array( "type" => "SUBMIT", "text" => "Choose")
	);


	$form->processConfig($config);
	return $form;
}

function _all_active_module_ids_and_names($f3)
{
	$nodes = node_load_multiple(array(), array('type' => 'book'));

	$modules = array();
	foreach ($nodes as $node_id => $node)
	{
		if (
			property_exists($node, 'book')
			&& array_key_exists('bid',$node->book)
			&& array_key_exists($node->book['bid'],$nodes)
			&& $nodes[$node->book['bid']]->status == 1
		)
		{
			$modules[ $node->book['bid'] ] = $nodes[$node->book['bid']]->title;
		}
	}
	
	return $modules;
}

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






