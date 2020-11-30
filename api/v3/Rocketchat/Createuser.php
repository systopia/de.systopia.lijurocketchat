<?php
use CRM_Lijurocketchat_ExtensionUtil as E;

/**
 * Rocketchat.Createuser API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_rocketchat_createuser_spec(&$spec) {
  $spec['name']['api.required'] = 1;
  $spec['email'] = array(
    'name'         => 'email',
    'api.required' => 1,
    'type'         => CRM_Utils_Type::T_TEXT,
    'title'        => 'Email',
    'description'  => 'user lookup for REST API via Email address',
  );
  $spec['password']['api.required'] = 1; // needs to be set randomly, can then be set afterwards
  $spec['username']['api.required'] = 1;
  $spec['active']['api.required'] = 1;
  $spec['roles']['api.required'] = 0;
  $spec['joinDefaultChannels']['api.required'] = 0;
  $spec['requirePasswordChange']['api.required'] = 0;
  $spec['sendWelcomeEmail']['api.required'] = 0;
  $spec['verified']['api.required'] = 0;
}

/**
 * Rocketchat.Createuser API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_rocketchat_createuser($params) {
  try{
    $test = new CRM_Lijurocketchat_Utils();
    $user = [];
    foreach ($params as $name => $value) {
      if ($test->check_parameter($name)) {
        $user[$name] = $value;
      }
    }
    $t = [
      'email'     => 'mailtest@systopia.de',
      'name'      => 'SYSTOPIA Mailtester',
      'password'  => '1234567890',
      'username'  => 'systopia_test',
      'active'    => TRUE,
    ];
    // TODO debug first before creating user here
    $response = $test->create_rcUser($t);
    return civicrm_api3_create_success('Hooray. Created user? ' . json_encode($response));

  } catch (Exception $e) {
    return civicrm_api3_create_success('Error: ' . $e->getMessage());
  }

}
