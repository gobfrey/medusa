<?php

function get_all_user_answer($f3)
{
	dump_request($f3);
}

function medusa_report($f3)
{
	#check user level


	$form = _report_form($f3);

	$f3->set("form", $form);
	$f3->set('report_report', 'module');

	$form_values = $form->fromForm();

	#set defaults
	if (empty($form_values))
	{
		$form_values['report_module'] = 'all_modules';
		$form_values['report_report'] = 'module';
		$form_values['report_date_from'] = date('Y-m-d',strtotime('-1 year'));
		$form_values['report_date_to'] = date('Y-m-d');
	}

	$f3->set("form_values", $form_values);

	#Patrick, is there a better way of doing this?
	$csv_link_url = $f3->get('api_base') . $f3->get('PARAMS.0');
	$cgi_params = array();
	$cgi_params[] = 'report_format=csv';
	foreach ($form_values as $param => $value)
	{
		$cgi_params[] = $param . '=' . urlencode($value);
	}
	$csv_link_url .= '?' . implode('&', $cgi_params);
	$f3->set('report_csv_url', $csv_link_url);

	if (
		$f3->exists('REQUEST.report_format')
		&& $f3->get('REQUEST.report_format') == 'csv'
		&& $form_values['report_report'] == 'person' #no csv export for module report
	)
	{

		{
			header('Content-Type: text/csv; charset=UTF-8');
			header('Content-Disposition: attachment; filename=medusa_person_report.csv');
			$output = fopen('php://output', 'w');

			fputcsv($output, array('Module','Username','Name','Date Completed'));
			$data = _person_report($f3, $form_values);
			foreach ($data as $module => $attempts)
			{
				foreach ($attempts as $attempt)
				{
					array_unshift($attempt, $module);
					fputcsv($output, $attempt);
				}
			}
			exit;
		}
	}

	#get data
	if ($form_values['report_report'] == 'person')
	{
		$f3->set('report_report','person');
		$f3->set('report_headings',array('Username','Name','Date Completed'));
		$f3->set('report_data', _person_report($f3, $form_values));
	}
	else
	{
		$f3->set('report_report','module');

		$f3->set('report_headings', array('Activity', 'Completed'));
		$f3->set('report_data', _module_report($f3, $form_values));
	}

	#load into structure for template to render

	echo(Template::instance()->render("report.htm"));
}


function medusa_certificate($f3)
{
	$module_id = $f3->get('module_id');
	$attempt_number = _get_module_attempt_number($f3, $module_id);

	if (!$module_id || !$attempt_number)
	{
		$f3->error(400,'A module id and attempt number must be provided');
		exit;
	}

	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));
	$attempt->load(array('user=? AND module_id=? AND attempt_number=?', $f3->get('user')->name, $module_id, $attempt_number));


	if ($attempt->date_completed)
	{
		$f3->set('css_root', $f3->get('drupal_base') . '/themes/southampton2/medusa_certificate/');
		$f3->set('img_root', $f3->get('drupal_base') . '/themes/southampton2/medusa_certificate/');
		$user = $f3->get('user');
		$user = user_load($user->uid);


		$f3->set('user_name', $user->field_name_from_ldap[LANGUAGE_NONE][0]['value']);
		$f3->set('date_completed', date('d F Y',strtotime($attempt->date_completed)));

		echo(Template::instance()->render("certificate.htm"));
		exit;
	}
	else
	{
		echo "Not Completed";
	}
}


function medusa_activity($f3)
{
	$date_from = '01/01/2009';
	$date_to = date("d/m/Y"); 

	if ($f3->exists('REQUEST.date_from'))
	{
		$date_from = $f3->get('REQUEST.date_from');
	}
	if ($f3->exists('REQUEST.date_to'))
	{
		$date_from = $f3->get('REQUEST.date_from');
	}



}


#redirects to a module page after having reset the session attempt number
function module_redirect($f3)
{
	$module_id = $f3->get('module_id');
	if (!$module_id)
	{
		$f3->error(400,'A missing args');
		exit;
	}

	$node_path = module_bookmarked_page_path($f3, $module_id, _get_module_attempt_number($f3, $module_id));


	$url = $f3->get("drupal_base") ."/".drupal_lookup_path('alias',$node_path); #drupal API call

	$f3->reroute($url);
}


function get_answer($f3)
{
	$answers = get_activity_answers($f3);

	$sxe = new SimpleXMLElement('<eSDAnswerList/>');
	foreach ($answers as $answer)
	{
		$answer_xml = $sxe->addChild('Answer');

		$answer_xml->addChild( 'UserAnswerID', (@$answer['id'] ?: -1) );
		$answer_xml->addChild('SessionID', (@$answer[''] ?: -1) );
		$answer_xml->addChild('ActivityID', (@$answer['actid'] ?: -1) );
		$answer_xml->addChild('QuestionID', (@$answer['qid'] ?: -1) );
		$answer_xml->addChild('SubQuestionID', (@$answer['subqid'] ?: -1) );
		$answer_xml->addChild('UserAnswer', (@$answer['ans'] ?: -1) );
		$answer_xml->addChild('Score', (@$answer['no_idea_where_this_comes_from'] ?: -1) );
		$answer_xml->addChild('SCompleted', (@$answer['finishact'] ?: -1) );
		$answer_xml->addChild('SEndTime', (@$answer['date'] ?: -1) );
	}

#	error_log('returning from get_answers');
#	error_log($sxe->asXML());

	echo $sxe->asXML();
	exit;
}

function save_answer($f3)
{
	#force session attempt to current attempt for writing
	$current_attempt = current_attempt($f3);
	_set_module_attempt_number($f3, $current_attempt->attempt_number);

#do we need to check for existance of answers?
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
	$answer->attempt_number = $current_attempt->attempt_number;
	$answer->date = date("Y-m-d H:i:s");
	$answer->user = $f3->get('user')->name;

	$answer->save();

}

function session_exists($f3)
{
	#should return false if the user is not logged in.  This is handled with a 403 before routing if there's no user.
}

function clear_answer($f3)
{
	if (!_current_attempt_active($f3))
	{
		return; #do nothing for completed attempts
	}

	$answers = get_activity_answers($f3);

	foreach ($answers as $a)
	{
		$a->erase();
	}
}

#saves the place of a user in a module for use in the continue link
function module_bookmark($f3)
{
	if (!_current_attempt_active($f3))
	{
		return; #do nothing for completed attempts
	}


	$module_id = $f3->get('module_id');
	$module_name = $f3->get('module_name');
	$user = $f3->get('user')->name;
	$current_node = $f3->get('REQUEST.current_node_path');

	if (!$module_id || !$user || !$current_node || !$module_name)
	{
		$f3->error(400,'A missing args');
		exit;
	}

	$bookmark = new DB\SQL\Mapper($f3->get('db'), $f3->get('bookmark_table'));
	$bookmark->load(array('user=? AND module_id=?', $user, $module_id));

	$current_attempt = current_attempt($f3);
	$bookmark->attempt_number = $current_attempt->attempt_number;
	$bookmark->user = $user;
	$bookmark->module_id = $module_id;
	$bookmark->current_node = $current_node;

	$bookmark->save();
}

function module_status($f3)
{
	$module_id = $f3->get('module_id');
	$attempt_number = _get_module_attempt_number($module_id);
	$username = $f3->get('user')->name;

	if (!$f3->exists('module_id'))
	{
		$f3->error(400,'A module id must be provided');
		exit;
	}

	#load straight from the db to avoid creating a new one
	$attempt = new DB\SQL\Mapper($f3->get('db'), $f3->get('module_attempt_table'));
	$bookmark->load(array('user=? AND module_id=? AND attempt_number=?', $username, $module_id, $attempt_number));

	if ($attempt->date_completed == NULL)
	{
		return 'incomplete';
	}
	return 'complete';
}

function complete_module($f3)
{
	$attempt = current_attempt($f3);

	$attempt->date_completed = date("Y-m-d H:i:s");
	$attempt->save();

	#we've written, so make sure we're on the correct attempt number.
	_set_module_attempt_number($f3, $attempt->attempt_number);
}


function my_medusa($f3)
{
	$username = $f3->get('user')->name;

	$attempts = all_user_attempts($f3, $username);

	$uncompleted = array();
	$completed = array();
	foreach ($attempts as $attempt)
	{
		$attempt_arr = $attempt->cast();

		if (!@$attempt_arr['module_id'])
		{
			continue;
		}

		#generate URL for each module
		$book_id = $attempt->module_id;
		$attempt_number = $attempt->attempt_number;

		$attempt_arr['url'] = $f3->get("drupal_base") ."/soton/api/module_redirect?module=$book_id&attempt_number=$attempt_number";
		$attempt_arr['certificate_url'] = $f3->get("drupal_base") ."/soton/api/medusa_certificate?module=$book_id&attempt_number=$attempt_number";

		if (@$attempt_arr['date_completed'])
		{
			$completed[] = $attempt_arr;
		}
		else
		{
			$uncompleted[] = $attempt_arr;
		}
	}

	$f3->set("completed_modules", $completed);
	$f3->set("uncompleted_modules", $uncompleted);
	
	echo(Template::instance()->render("my_medusa.htm"));
	exit;

}

include("patricks_http_routes.php");
