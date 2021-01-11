<?php
/*-------------------------------------------------------+
| Project 60 - SEPA direct debit                         |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: P.Batroff (batroff -at- systopia.de)           |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/


namespace Civi\Lijurocketchat\ActionProvider\Action;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\Specification;
use \Civi\ActionProvider\Parameter\SpecificationBag;

use Civi\FormProcessor\API\Exception;
use CRM_Lijurocketchat_ExtensionUtil as E;

class LinkRocketchat extends AbstractAction {

  /**
   * Returns the specification of the configuration options for the actual action.
   *
   * TODO: Do we need default configuration parameters for this action?
   * @return SpecificationBag specs
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag([]);
  }


  /**
   * Returns the specification of the parameters of the actual action.
   *
   * @return SpecificationBag specs
   */
  public function getParameterSpecification() {
    return new SpecificationBag([
      // required fields
      new Specification('contact_id', 'Integer', E::ts('Contact ID'), true),
      new Specification('first_name',       'String',  E::ts('First Name'), true),
      new Specification('last_name',        'String',  E::ts('Last Name'), true),
      new Specification('email',  'String',  E::ts('Email Address'), true),
    ]);
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * This function could be overridden by child classes.
   *
   * @return SpecificationBag specs
   */
  public function getOutputSpecification() {
    return new SpecificationBag([
      new Specification('rocketchat_id',        'Integer', E::ts('Rocketchat ID'), false, null, null, null, false),
      new Specification('error',             'String',  E::ts('Error Message in case of failure'), false, null, null, null, false),
    ]);
  }

  /**
   * Run the action
   *
   * @param ParameterBagInterface $parameters
   *   The parameters to this action.
   * @param ParameterBagInterface $output
   * 	 The parameters this action can send back
   * @return void
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output) {
    try {
      // get RC id for email
      $rocketchat_id = $this->lookup_rc_user($parameters);

      if ($rocketchat_id == '0') {
        // no rocketchat account found for that email
        $rocketchat_id = $this->create_rc_user($parameters);
      }
      $this->add_rc_id_to_contact($parameters, $rocketchat_id);
      // set output paramters
      $output->setParameter('rocketchat_id', $rocketchat_id);
      $output->setParameter('error', '');

    } catch (\Exception $ex) {
      $output->setParameter('rocketchat_id', '');
      $output->setParameter('error', $ex->getMessage());
    }
  }

  /**
   * @param ParameterBagInterface $parameters
   * @return mixed
   * @throws Exception
   * @throws \CiviCRM_API3_Exception
   */
  protected function lookup_rc_user(ParameterBagInterface $parameters) {
    $rocketchat_user_lookup = civicrm_api3('Rocketchat', 'userlookup', [
      'email' => $parameters->getParameter('email')
    ]);

    echo "DEBUG: " . \GuzzleHttp\json_encode($rocketchat_user_lookup) . "\n";

    if ($rocketchat_user_lookup['is_error'] == '1') {
      \Civi::log()->debug("Internal Rocket chat Error: " . $rocketchat_user_lookup['error_message']);
      throw new Exception("Internal Rocket chat Error: " . $rocketchat_user_lookup['error_message']);
    }
    return $rocketchat_user_lookup['values']['rocketchat_id'];
  }

  /**
   * @param ParameterBagInterface $parameters
   * @throws \CiviCRM_API3_Exception
   */
  protected function  create_rc_user(ParameterBagInterface $parameters) {

    $contact = civicrm_api3("Contact", "getsingle", ['id' => $parameters->getParameter('contact_id')]);

    if (empty($contact['first_name']) && empty($contact['last_name'])) {
      $name = $contact['display_name'];
      $username = $contact['display_name'];
    } else {
      $name = $contact['first_name'] . " " . $contact['last_name'];
      $username = $contact['first_name'] . "_" . $contact['last_name'];
    }
    $params = [
      'name' => $name,
      'email' => $parameters->getParameter('email'),
      'password' => $this->generate_string(),
      'username' => $username,
      'active' => TRUE
    ];
    $create_rocketchat_user_result = civicrm_api3('Rocketchat', 'createuser', $params);
    return $create_rocketchat_user_result['rocketchat_id'];
  }

  /**
   * @param ParameterBagInterface $parameters
   * @param $rocketchat_id
   * @throws \CiviCRM_API3_Exception
   */
  protected function add_rc_id_to_contact(ParameterBagInterface $parameters, $rocketchat_id) {
    $contact_id = $parameters->getParameter('contact_id');
    $result = civicrm_api3('Contact', 'addidentity', [
      'contact_id' => $contact_id,
      'identifier' => $rocketchat_id,
      'identifier_type' => "rocketchat",
    ]);
  }

  /**
   * Generates a random alphanumeric string for a default password
   * Values [a-zA-Z0-9]
   *
   * @param int $strength
   * @return string
   */
  protected function generate_string($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
  }

}
