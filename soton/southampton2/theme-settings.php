<?php

/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function southampton2_form_system_theme_settings_alter(&$form, &$form_state)  {

  // Create the form using Forms API: http://api.drupal.org/api/7

  $form['southampton2_homename'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Name for "home" option in menu'),
    '#default_value' => theme_get_setting('southampton2_homename'),
    '#description'   => t("This option sets the name of the home link in the menu shown on all pages."),
  );
  $form['southampton2_searchurl'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Search URL'),
    '#default_value' => theme_get_setting('southampton2_searchurl'),
    '#description'   => t("This option sets the URL for the site search form. If not set will do something sensible." ),
  );
  $form['southampton2_colorscheme'] = array(
    '#type'          => 'radios',
    '#title'         => t('Colour Scheme'),
    '#default_value' => theme_get_setting('southampton2_colorscheme'),
    '#options'       => array(
		'teal' => t('Teal'),
		'light_blue' => t('Light Blue'), 
		'olive' => t('Olive'),
		'silver' => t('Silver'),
		'marine' => t('Marine'),
    ),
    '#description'   => t("Pick the colour scheme used by the site."),
  );
  $form['southampton2_rawextra'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Extra HTML to add at end of page'),
    '#default_value' => theme_get_setting('southampton2_rawextra'),
    '#description'   => t("This option lets you add additional style and javascript blocks at the end of every page to add additional CSS or features, and a Google Analytics block"),
  );

  // Remove some of the base theme's settings.
  unset($form['themedev']['zen_layout']); // We don't need to select the layout stylesheet.

  // We are editing the $form in place, so we don't need to return anything.
}

function southampton_preprocess_node(&$vars, $hook) {
  $function = 'southampton_preprocess_node'.'_'. $vars['node']->type;
  if (function_exists($function)) {
    $function($vars);
  }
}
 
