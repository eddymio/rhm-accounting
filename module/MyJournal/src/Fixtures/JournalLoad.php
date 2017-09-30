<?php

namespace MyJournal\Fixtures;

/* 
 * Return array with data for default table entries to load on table creation 
 * http://www.orfisbti.com/sites/orfis/files/wysiwyg/guide_du_fec_ath_-_orfis_baker_tilly.pdf
 * */

return [
	'code' =>	[
			array('code' => 'AC','wording'  => 'Journal des achats'),
			array('code' => 'OD','wording'  => 'Journal des opérations diverses'),
			array('code' => 'VE','wording'  => 'Journal des ventes'),
			array('code' => 'BQ','wording'  => 'Journal de banque'),
			array('code' => 'TR','wording'  => 'Journal de trésorerie'),
			array('code' => 'CA','wording'  => 'Journal de caisse')
	],
	'type' =>	[
			array('name' => 'DEBIT','description'  => 'Ce qui rentre dans l\'entreprise'),
			array('name' => 'CREDIT','description'  => 'Ce qui sort de l\'entreprise')
	]
		
];