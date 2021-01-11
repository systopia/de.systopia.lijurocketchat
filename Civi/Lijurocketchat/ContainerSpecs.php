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

namespace Civi\Lijurocketchat;

use CRM_Lijurocketchat_ExtensionUtil as E;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerSpecs implements CompilerPassInterface {

  /**
   * Register Link Rocketchat Actions
   */
  public function process(ContainerBuilder $container) {
    if (!$container->hasDefinition('action_provider')) {
      return;
    }
    $typeFactoryDefinition = $container->getDefinition('action_provider');
    $typeFactoryDefinition->addMethodCall('addAction', ['link_rocketchat', 'Civi\Lijurocketchat\ActionProvider\Action\LinkRocketchat', E::ts('Link Rocketchat Account submitted user'), [
      \Civi\ActionProvider\Action\AbstractAction::SINGLE_CONTACT_ACTION_TAG,
    ]]);
  }
}
