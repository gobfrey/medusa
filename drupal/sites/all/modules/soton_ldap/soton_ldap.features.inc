<?php
/**
 * @file
 * soton_ldap.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function soton_ldap_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "ldap_servers" && $api == "ldap_servers") {
    return array("version" => "1");
  }
}
