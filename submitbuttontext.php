<?php

require_once 'submitbuttontext.civix.php';

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function submitbuttontext_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {
    // Adding a field to set the the Submit button text on the backend
    $form->add('text', 'buttontext', ts('Submit Button Text'));
    CRM_Core_Resources::singleton()->addScriptFile('com.aghstrategies.submitbuttontext', 'js/backendformstyling.js');

    //set default value
    $defaults = array('buttontext' => 'Confirm Contribution');
    try {
      $buttonInfo = civicrm_api3('Setting', 'get', array(
        'return' => "submitbuttontext_buttontext",
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.submitbuttontext',
        1 => $error,
      )));
    }
    //TODO this needs to be refactored
    if (!empty($buttonInfo['values'][1]['submitbuttontext_buttontext']) && in_array($form->getVar('_id'), $buttonInfo['values'][1]['submitbuttontext_buttontext'])) {
      $defaults['buttontext'] = 1;
    }
    $form->setDefaults($defaults);
    // Assumes templates are in a templates folder relative to this file.
    $templatePath = realpath(dirname(__FILE__) . "/templates");
    CRM_Core_Region::instance('form-top')->add(array(
      'template' => "{$templatePath}/submitbuttontextfield.tpl",
    ));
  }

  // JS to change button text on front end
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    $pageId = $form->getVar('_id');
    try {
      $buttonInfo = civicrm_api3('Setting', 'get', array(
        'return' => "submitbuttontext_buttontext",
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.submitbuttontext',
        1 => $error,
      )));
    }
    if (!empty($pageId) && !empty($buttonInfo['values']['submitbuttontext_buttontext'][$pageId])) {
      CRM_Core_Resources::singleton()->addVars('submitbuttontext', array('buttontext' => $buttonInfo['values']['submitbuttontext_buttontext'][$pageId]));
      CRM_Core_Resources::singleton()->addScriptFile('com.aghstrategies.submitbuttontext', 'js/changebuttontext.js');
    }
  }
}


/**
 * Implements hook_civicrm_postProcess().
 */
function submitbuttontext_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {

    // Get existing Alternate Button Texts this should be an array where the key is the contrib page id and the value is the alternate text
    try {
      $buttonInfo = civicrm_api3('Setting', 'get', array(
        'return' => "submitbuttontext_buttontext",
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.submitbuttontext',
        1 => $error,
      )));
    }
    $contribPageId = $form->getVar('_id');
    $buttonInfoToUpdate = $buttonInfo['values']['submitbuttontext_buttontext'];
    // If text for the button has been submitted
    if (!empty($form->_submitValues['buttontext'])) {
      $buttonInfoToUpdate[$contribPageId] = $form->_submitValues['buttontext'];
    }

    // If there is no text for the button, check if it has been deleted and if so remove that page from the setting
    else {
      if (in_array($contribPageId, $buttonInfoToUpdate)) {
        unset($buttonInfoToUpdate[$contribPageId]);
      }
    }
    try {
      $result = civicrm_api3('Setting', 'create', array(
        'submitbuttontext_buttontext' => $buttonInfoToUpdate,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.submitbuttontext',
        1 => $error,
      )));
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function submitbuttontext_civicrm_config(&$config) {
  _submitbuttontext_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function submitbuttontext_civicrm_xmlMenu(&$files) {
  _submitbuttontext_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function submitbuttontext_civicrm_install() {
  _submitbuttontext_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function submitbuttontext_civicrm_postInstall() {
  _submitbuttontext_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function submitbuttontext_civicrm_uninstall() {
  _submitbuttontext_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function submitbuttontext_civicrm_enable() {
  _submitbuttontext_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function submitbuttontext_civicrm_disable() {
  _submitbuttontext_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function submitbuttontext_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _submitbuttontext_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function submitbuttontext_civicrm_managed(&$entities) {
  _submitbuttontext_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function submitbuttontext_civicrm_caseTypes(&$caseTypes) {
  _submitbuttontext_civix_civicrm_caseTypes($caseTypes);
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
function submitbuttontext_civicrm_angularModules(&$angularModules) {
  _submitbuttontext_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function submitbuttontext_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _submitbuttontext_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function submitbuttontext_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function submitbuttontext_civicrm_navigationMenu(&$menu) {
  _submitbuttontext_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'com.aghstrategies.submitbuttontext')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _submitbuttontext_civix_navigationMenu($menu);
} // */
