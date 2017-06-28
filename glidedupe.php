<?php

require_once 'glidedupe.civix.php';

function glidedupe_civicrm_dupeQuery($form, $type, &$data) {
  if ($type == 'supportedFields' && !empty($data)) {
    $data['Organization']['civicrm_contact']['custom_org_state']                = "Custom: Organization Name AND State";
/*
    $data['Individual']['civicrm_contact']['custom_first_last_email']          = "Custom: First Name AND Last Name AND Email";
    $data['Individual']['civicrm_contact']['custom_first_last_phone']          = "Custom: First Name AND Last Name AND Phone";
    $data['Individual']['civicrm_contact']['custom_first_last_postcode']       = "Custom: First Name AND Last Name AND Postal Code";
*/
  }
  if ($type == 'dedupeIndexes' && !empty($data)) {
    foreach ($data['civicrm_contact'] as $key => $val) {
      if (in_array($val, 
        array(
          'custom_org_state',
/*
          'custom_first_last_phone', 
          'custom_first_last_email', 
          'custom_first_last_postcode',
*/
        ))
      ) {
        unset($data['civicrm_contact'][$key]);
      }
    }
  }
  if ($type == 'table' && !empty($data)) {
    foreach ($data as $key => &$query) {
      list($table, $col, $wt) = explode('.', $key);
      if ($table == 'civicrm_contact' && $col == 'custom_org_state') {
        $data[$key] = "
    SELECT t1.id id1, t2.id id2, $wt weight 
      FROM civicrm_contact t1
INNER JOIN civicrm_address adr1 on t1.id = adr1.contact_id
      JOIN (SELECT cc.id, cc.organization_name, cc.contact_type, adr2.state_province_id
              FROM civicrm_contact cc
        INNER JOIN civicrm_address adr2 ON cc.id = adr2.contact_id) t2 ON t1.organization_name = t2.organization_name AND
                                                                          adr1.state_province_id = t2.state_province_id
     WHERE t1.contact_type = 'Organization' AND 
           t2.contact_type = 'Organization' AND 
           t1.id < t2.id AND 
           t1.organization_name IS NOT NULL"; 
      }
    }
  }
  // make sure custom queries are executed first
  if ($type == 'tableCount' && !empty($data)) {
    $customQuery = array();
    foreach ($data as $key => &$query) {
      list($table, $col, $wt) = explode('.', $key);
      if (in_array($col, 
        array(
          'custom_org_state',
/*
          'custom_first_last_phone', 
          'custom_first_last_email', 
          'custom_first_last_postcode',
*/
        ))
      ) {
        $customQuery = array($key => $query);
        unset($data[$key]);
      }
    }
    if (!empty($customQuery)) {
      $data = array_merge($customQuery, $data);
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function glidedupe_civicrm_config(&$config) {
  _glidedupe_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function glidedupe_civicrm_xmlMenu(&$files) {
  _glidedupe_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function glidedupe_civicrm_install() {
  _glidedupe_civix_civicrm_install();
}

/**
* Implements hook_civicrm_postInstall().
*
* @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
*/
function glidedupe_civicrm_postInstall() {
  _glidedupe_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function glidedupe_civicrm_uninstall() {
  _glidedupe_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function glidedupe_civicrm_enable() {
  _glidedupe_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function glidedupe_civicrm_disable() {
  _glidedupe_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function glidedupe_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _glidedupe_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function glidedupe_civicrm_managed(&$entities) {
  _glidedupe_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function glidedupe_civicrm_caseTypes(&$caseTypes) {
  _glidedupe_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function glidedupe_civicrm_angularModules(&$angularModules) {
_glidedupe_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function glidedupe_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _glidedupe_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function glidedupe_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function glidedupe_civicrm_navigationMenu(&$menu) {
  _glidedupe_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'org.gilderlehrman.glidedupe')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _glidedupe_civix_navigationMenu($menu);
} // */
