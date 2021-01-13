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
  try{
    $contact_id = $params['contact_id'];
    $rocketchat_id = '';
    // get user
    $contact = civicrm_api3("Contact", "getsingle", [
      'id' => $contact_id,
    ]);

    $rocketchat_names = \CRM_Lijurocketchat_Utils::create_rocketchat_names($contact);
    $rocketchat_params = [
      'name' => $rocketchat_names['name'],
      'username' => $rocketchat_names['username'],
      'active' => TRUE,
//      'sendwelcomemail' => TRUE,  // TODO
      'password' => \CRM_Lijurocketchat_Utils::generate_random_string(),
      'email' => \CRM_Lijurocketchat_Utils::get_primary_email($contact_id),
    ];

    $create_rocketchat_user_result = civicrm_api3('Rocketchat', 'createuser', $rocketchat_params);
     = $create_rocketchat_user_result['values']['rocketchat_id'];

    \CRM_Lijurocketchat_Utils::add_rc_id_to_contact($contact_id, $rocketchat_id);
    return civicrm_api3_create_success(["rocketchat_id" => $rocketchat_id]);
  } catch(Exception $e) {
    return civicrm_api3_create_error($e->getMessage());
  }
}
