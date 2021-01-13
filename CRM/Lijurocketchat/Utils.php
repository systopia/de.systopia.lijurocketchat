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

}
