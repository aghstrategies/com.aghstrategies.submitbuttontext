CRM.$(function ($) {
  //Moves Field to enter contribution page button text to be on the Contribution Page Setings form.
  $('.crm-price-field-block-submitbuttontextfield')
  .insertAfter('.crm-contribution-contributionpage-settings-form-block-title');
  $('.deleteme').remove();
});
