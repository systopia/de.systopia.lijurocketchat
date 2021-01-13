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

/**
 * Class CRM_Lijurocketchat_Utils
 */
class CRM_Lijurocketchat_Utils {


  /**
   * Generates a random alphanumeric string
   * Values [a-zA-Z0-9]
   *
   * @param int $strength
   * @return string
   */
  public static function generate_random_string($strength = 16)
  {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
  }

  /**
   * @param $contact_id
   * @return mixed
   * @throws CiviCRM_API3_Exception
   */
  public static function get_primary_email($contact_id) {
    $result = civicrm_api3('Email', 'get', [
      'sequential' => 1,
      'contact_id' => $contact_id,
    ]);
    foreach ($result['values'] as $email) {
      if ($email['is_primary'] == '1') {
        return $email['email'];
      }
    }
  }

  /**
   * @param $contact
   * @return array
   */
  public static function create_rocketchat_names($contact){
    $result = [];
    if (empty($contact['first_name']) && empty($contact['last_name'])) {
      $result['name'] = $contact['display_name'];
      $result['username'] = $contact['display_name'];
    } else {
      $result['name'] = $contact['first_name'] . " " . $contact['last_name'];
      $result['username'] = $contact['first_name'] . "_" . $contact['last_name'];
    }
    return $result;
  }


  /**
   * @param $contact_id
   * @param $rocketchat_id
   * @throws \CiviCRM_API3_Exception
   */
  public static function add_rc_id_to_contact($contact_id, $rocketchat_id) {
    $result = civicrm_api3('Contact', 'addidentity', [
      'contact_id' => $contact_id,
      'identifier' => $rocketchat_id,
      'identifier_type' => "rocketchat",
    ]);
  }

}
