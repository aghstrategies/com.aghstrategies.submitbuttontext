CRM.$(function ($) {
  // Changes Confirm Contribution Buttons to say Submit
  var buttonText = CRM.vars.submitbuttontext.buttontext;

  // TODO get value from php setting and change submit to be that variable
  $('.crm-form-submit').attr('value', buttonText);
});
