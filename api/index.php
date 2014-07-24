<?php

$f3=require('lib/fatfree/lib/base.php');

$f3->set('DEBUG',3);

$f3->config('config.ini');



function get_answer($f3)
{
	dump_request($f3);
}

function save_answer($f3)
{
	dump_request($f3);
}
function session_exists($f3)
{
	dump_request($f3);
}
function get_all_user_answer($f3)
{
	dump_request($f3);
}
function clear_answer($f3)
{
	dump_request($f3);
}

function dump_request($f3)
{
	error_log(print_r($f3->get('REQUEST'),True));
}





$f3->run();
