CRM.$(function ($) {
  // Changes Confirm Contribution Buttons to say Submit
  var buttonText = CRM.vars.submitbuttontext.buttontext;
  
  // get value from php setting and change submit to be that variable
  $('button.crm-form-submit').html(buttonText);
});
