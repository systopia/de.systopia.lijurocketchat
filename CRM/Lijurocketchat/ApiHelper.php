<?php
/*-------------------------------------------------------+
| SYSTOPIA Linksjugend Rocketchat Integration            |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: P. Batroff (batroff@systopia.de)               |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Lijurocketchat_ExtensionUtil as E;
use Httpful\Exception\ConnectionErrorException;
use Httpful\Response;

/**
 * Class CRM_Lijurocketchat_ApiHelper
 */
class CRM_Lijurocketchat_ApiHelper {

  private $rocketchat_connector;

  private $new_rocketchat_user = [];

  private $rc_user_attrib = [
    'email',
    'name',
    'password',
    'username',
    'active',
    'roles',
    'joinDefaultChannels',
    'requirePasswordChange',
    'sendWelcomeEmail',
    'verified',
    'customFields',
  ];

  /**
   * CRM_Lijurocketchat_Utils constructor.
   */
  public function __construct() {
    $this->rocketchat_connector = new CRM_Rocketchatapi_Rocketchatconnector();
  }

  /**
   * Userlookup in the Rocketchat DB via Email
   *
   * @param $email
   * @return Response
   * @throws ConnectionErrorException
   */
  public function get_rcUser($email) {
    $params = [
      'query' => [
        'emails' => [
          '$elemMatch' => ['address' => ['$eq' => $email]]
        ]
      ]
    ];
    return $this->rocketchat_connector->execute_get('users.list', $params);
  }

  /**
   * Deletes a user by its RC ID
   * https://docs.rocket.chat/api/rest-api/methods/users/delete
   *
   * @param $rc_id
   * @return Response
   * @throws ConnectionErrorException
   */
  public function delete_rcUser($rc_id) {
    $params = [
      'userId'            => $rc_id,
      'confirmRelinquish' => TRUE,
    ];
    return $this->rocketchat_connector->execute_post('users.delete', json_encode($params));
  }

  /**
   * Get the rocketchat Id via userLookup by Email
   *
   * @param $email
   * @return string
   * @throws ConnectionErrorException
   */
  public function get_rcId($email) {
    $response = $this->get_rcUser($email);
    $users = $response->body->users;
    if (count($users) > 1) {
      throw new Exception("[CRM_Lijurocketchat_Utils->get_rcId] More than one user found with given Email. This shouldn't happen");
    }
    foreach ($users as $user) {
      return $user->_id;
    }
    return "0";
  }

  /**
   * Creates a user with given parameters
   *
   * @param $user
   * @return Response
   * @throws ConnectionErrorException
   */
  public function create_rcUser($user) {
    $this->verify_user_parameters($user);
    $response = $this->rocketchat_connector->execute_post('users.create', json_encode($user));
    // check for errors
    if ($response->body->success == false) {
      throw new Exception('Rocket Chat Create user Error: ' . $response->body->error);
    }
    $this->new_rocketchat_user[$response->body->user->_id] = $response;
    return $response->body->user->_id;
  }


  /**
   * Verify if user array is valid against local attribute list
   * See https://docs.rocket.chat/api/rest-api/methods/users/create for valid attributes
   *
   * @param $params
   * @throws Exception
   */
  private function verify_user_parameters($user) {
    foreach ($user as $attribute => $value) {
      if (!in_array($attribute, $this->rc_user_attrib, FALSE)) {
        throw new Exception('[CRM_Lijurocketchat_Utils->verify_user_parameters] Invalid parameters in user array');
      }
    }
  }

  /**
   * helper function to check possible parameters for user array
   *
   * @param $param
   * @return bool
   */
  public function check_parameter($param) {
    if (in_array($param, $this->rc_user_attrib, FALSE)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * test function
   * @return Response
   * @throws ConnectionErrorException
   */
  public function whoami() {
    return $this->rocketchat_connector->execute_get("me");
  }
}
