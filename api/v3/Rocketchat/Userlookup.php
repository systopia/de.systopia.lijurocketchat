<?php
use CRM_Lijurocketchat_ExtensionUtil as E;

/**
 * Rocketchat.Userlookup API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_rocketchat_userlookup_spec(&$spec) {
  $spec['email'] = array(
    'name'         => 'email',
    'api.required' => 1,
    'type'         => CRM_Utils_Type::T_TEXT,
    'title'        => 'Email',
    'description'  => 'user lookup for REST API via Email address',
  );
}

/**
 * Rocketchat.Userlookup API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_rocketchat_userlookup($params) {
  $test = new CRM_Lijurocketchat_Utils();
  $res = $test->whoami();
  return civicrm_api3_create_success('HOORAY. The End.');
}
