<?php

namespace MyEnterprise\Fixtures;

/* 
 * Return array with data for default table entries to load on table creation 
 * */

return [
	'currency' =>	[
			array('name' => 'EURO','label'  => 'EUR', 'default' => 1),
			
	],
	'vat' => [
			array('name' => 'TVA 20%','amount' => 20,'default' => 1,'debit' => 200,'credit' => 206),
			
	]
		
];