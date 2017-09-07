<?php
/**
 * @file
 * Settings metadata for com.aghstrategies.proratemembership.
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */
return array(
  'proratemembership_pricefields' => array(
    'group_name' => 'Prorate Preferences',
    'group' => 'proratemembership',
    'name' => 'proratemembership_pricefields',
    'type' => 'Array',
    'default' => NULL,
    'add' => '4.6',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Array of price fields to be prorated',
    'help_text' => 'price fields in this array are prorated by the com.aghstrategies.proratemembership extension',
  ),
);
