<?php

require_once 'lijurocketchat.civix.php';
use CRM_Lijurocketchat_ExtensionUtil as E;

use \Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function lijurocketchat_civicrm_config(&$config) {
  _lijurocketchat_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_container()
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function lijurocketchat_civicrm_container(ContainerBuilder $container) {
  if (class_exists('\Civi\Lijurocketchat\ContainerSpecs')) {
    $container->addCompilerPass(new \Civi\Lijurocketchat\ContainerSpecs());
  }
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function lijurocketchat_civicrm_install() {
  _lijurocketchat_civix_civicrm_install();
  // check if rocketchat ID-type already exists
  $result = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'option_group_id' => "contact_id_history_type",
    'value' => "rocketchat",
  ]);
  // if not ID exists, create it
  if ($result['count'] != '1') {
    $result = civicrm_api3('OptionValue', 'create', [
      'option_group_id' => "contact_id_history_type",
      'label' => "rocketchat",
      'value' => "rocketchat",
    ]);
  }
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function lijurocketchat_civicrm_enable() {
  _lijurocketchat_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function lijurocketchat_civicrm_navigationMenu(&$menu) {
  _lijurocketchat_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _lijurocketchat_civix_navigationMenu($menu);
} // */
