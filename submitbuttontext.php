<?php

require_once 'submitbuttontext.civix.php';

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function submitbuttontext_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {
    $contribPageId = $form->getVar('_id');
    // Adding a field to set the the Submit button text on the backend
    $form->add('text', 'buttontext', ts('Submit Button Text'));
    CRM_Core_Resources::singleton()->addScriptFile('com.aghstrategies.submitbuttontext', 'js/backendformstyling.js');

    // Look up if this form has a saved button text value
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
    if (!empty($buttonInfo['values'][1]['submitbuttontext_buttontext'][$contribPageId])) {
      $defaults['buttontext'] = $buttonInfo['values'][1]['submitbuttontext_buttontext'][$contribPageId];
      $form->setDefaults($defaults);
    }
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
    if (!empty($pageId) && !empty($buttonInfo['values'][1]['submitbuttontext_buttontext'][$pageId])) {
      CRM_Core_Resources::singleton()->addVars('submitbuttontext', array('buttontext' => $buttonInfo['values'][1]['submitbuttontext_buttontext'][$pageId]));
      CRM_Core_Resources::singleton()->addScriptFile('com.aghstrategies.submitbuttontext', 'js/changebuttontext.js');
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function submitbuttontext_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {

    // Get existing Alternate Button Texts this should be an array where the key is the contrib page id and the value is the alternate text
    try {
      $buttonInfo = civicrm_api3('Setting', 'get', array(
        'sequential' => 1,
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
    $buttonInfoToUpdate = array();
    if (!empty($buttonInfo['values'][0]['submitbuttontext_buttontext'])) {
      $buttonInfoToUpdate = $buttonInfo['values'][0]['submitbuttontext_buttontext'];
    }
    // If text for the button has been submitted
    if (!empty($form->_submitValues['buttontext'])) {
      $buttonInfoToUpdate[$contribPageId] = $form->_submitValues['buttontext'];
    }

    // If there is no text for the button, check if it has been deleted and if so remove that page from the setting array
    else {
      if (!empty($buttonInfoToUpdate[$contribPageId])) {
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
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function submitbuttontext_civicrm_install() {
  _submitbuttontext_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function submitbuttontext_civicrm_enable() {
  _submitbuttontext_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

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
