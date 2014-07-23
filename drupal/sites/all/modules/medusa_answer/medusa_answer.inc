<?php

#I can't seem to call medusa_answer_schema from this file.  Fix later
function _medusa_answer_schema_copy()
{
  return array(
    'medusa_answer' => array(
      'description' => 'Stores medusa answers',
      'fields' => array(
        'module' => array(
          'description' => t('module that the question is part of'),
          'type' => 'varchar',
          'length' => 16,
          'not null' => TRUE,
        ),
        'module_attempt' => array(
          'description' => t('The attempt number of the module (i.e. has it been done before)'),
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'default' => 0,
        ),
        'question' => array(
          'description' => t('question id'),
          'type' => 'varchar',
          'length' => 16,
          'not null' => TRUE,
        ),
        'subquestion' => array(
          'description' => t('subquestion id'),
          'type' => 'varchar',
          'length' => 16,
          'not null' => TRUE,
        ),
        'uid' => array(
          'description' => t('The user that answered.'),
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'default' => 0,
        ),
        'timestamp' => array(
          'description' => t('The timestamp for the answer (creation or lastmod).'),
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'default' => 0,
        ),
        'note' => array(
          'description' => t('The answer'),
          'type' => 'text',
          'size' => 'medium',
        ),
      ),
      'primary key' => array('module','module_attempt', 'question', 'subquestion', 'uid'),


      'indexes' => array(
        'index1' => array('uid','module'),
	'index2' => array('uid','module','module_attempt'),
	'index3' => array('module','module_attempt'),
      ),
    ),
  );

}



/**
 * Callback for creating medusa_answer resources.
 *
 * @param object $data
 * @return object
 */
function _medusa_answer_create($module, $module_attempt, $question, $subquestion, $answer) {
	global $user;

error_log('_medusa_answer_create called');

	$medusa_answer = array();

	$medusa_answer['uid'] = $user->uid;
	$medusa_answer['timestamp'] = time();

	$medusa_answer['module'] = $module;
	$medusa_answer['module_attempt'] = $module_attempt;
	$medusa_answer['question'] = $question;
	$medusa_answer['subquestion'] = $subquestion;
	$medusa_answer['answer'] = $answer;

	$schema = _medusa_answer_schema_copy();
	$primary_key = $schema['primary_key'];

	foreach ($primary_key as $p)
	{
		if (!$medusa_answer[$p])
		{
			return services_error("Missing parameter $p");
		}
	}

	medusa_answer_write_medusa_answer($medusa_answer);

	return (object)array(
		'id' => 'foo',
	);
}

/**
 * Callback for retrieving medusa_answer resources.
 *
 * @param int $id
 * @return object
 */
function _medusa_answer_retrieve($module, $module_attempt, $question, $subquestion) {
	global $user; 
	return medusa_answer_get_medusa_answer($user->uid, $module, $module_attempt, $question, $subquestion);

}

/**
 * Callback for updating medusa_answer resources.
 *
 * @param int $id
 * @param object $data
 * @return object
 */
function _medusa_answer_update($module, $module_attempt, $question, $subquestion, $answer) {
	return _medusa_answer_update($module, $module_attempt, $question, $subquestion, $answer);
}

/**
 * Callback for deleting medusa_answer resources.
 *
 * @param int $id
 * @return object
 */
function _medusa_answer_delete($id) {
  medusa_answer_delete_medusa_answer($id);
}

/**
 * Callback for listing medusa_answers.
 *
 * @param int $page
 * @param array $parameters
 * @return array
 */
function _medusa_answer_index($page, $parameters) {
  global $user;

  $medusa_answers = array();
  $res = db_query("SELECT * FROM {medusa_answer} WHERE uid=%d ORDER BY datestamp DESC", array(
    ':uid' => $user->uid,
  ));

  while ($medusa_answer = db_fetch_object($res)) {
    $medusa_answers[] = $medusa_answer;
  }

  return $medusa_answers;
}
