<?php
use CRM_Lijurocketchat_ExtensionUtil as E;

/**
 * Rocketchat.Synchronizeuser API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_rocketchat_synchronizeuser_spec(&$spec) {
  $spec['contact_id']['api.required'] = 1;
}

/**
 * Rocketchat.Synchronizeuser API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_rocketchat_synchronizeuser($params) {

}
