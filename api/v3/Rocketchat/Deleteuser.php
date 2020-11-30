<?php
use CRM_Lijurocketchat_ExtensionUtil as E;

/**
 * Rocketchat.Deleteuser API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_rocketchat_deleteuser_spec(&$spec) {
  $spec['rocketchat_id']['api.required'] = 1;
}

/**
 * Rocketchat.Deleteuser API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_rocketchat_deleteuser($params) {
    try{
    $test = new CRM_Lijurocketchat_Utils();
    $rc_id = 'hifbNL5jkyDmPfRGG';

    $response = $test->delete_rcUser($rc_id);
    return civicrm_api3_create_success('Hooray. Deleted user? ' . json_encode($response));

  } catch (Exception $e) {
    return civicrm_api3_create_success('Error: ' . $e->getMessage());
  }
}
