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

  private $rocketchat_connector;

  /**
   * CRM_Lijurocketchat_Utils constructor.
   */
  public function __construct() {
    $this->rocketchat_connector = new CRM_Rocketchatapi_Rocketchatconnector();
  }

  /**
   * test function
   * @return \Httpful\Response
   * @throws \Httpful\Exception\ConnectionErrorException
   */
  public function whoami() {
    return $this->rocketchat_connector->execute("me");
  }
}
