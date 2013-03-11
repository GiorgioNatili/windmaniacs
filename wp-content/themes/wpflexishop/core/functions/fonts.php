<?php
function prima_webfonts() {
$webfonts = array(
		'arial' => array(
			'value' =>	'arial',
			'label' =>  'Arial',
			'type' =>  '',
			'font-family' => 'Arial, sans-serif'
		),
		'arialblack' => array(
			'value' =>	'arialblack',
			'label' =>  'Arial Black',
			'type' =>  '',
			'font-family' => '&quot;Arial Black&quot;, sans-serif'
		),
		'calibri' => array(
			'value' =>	'calibri',
			'label' =>  'Calibri*',
			'type' =>  '',
			'font-family' => 'Calibri, Candara, Segoe, Optima, sans-serif'
		),
		'geneva' => array(
			'value' =>	'geneva',
			'label' =>  'Geneva*',
			'type' =>  '',
			'font-family' => 'Geneva, Tahoma, Verdana, sans-serif'
		),
		'georgia' => array(
			'value' =>	'georgia',
			'label' =>  'Georgia',
			'type' =>  '',
			'font-family' => 'Georgia, serif'
		),
		'gillsans' => array(
			'value' =>	'gillsans',
			'label' =>  'Gill Sans*',
			'type' =>  '',
			'font-family' => '"Gill Sans", "Gill Sans MT", Calibri, sans-serif'
		),
		'helvetica' => array(
			'value' =>	'helvetica',
			'label' =>  'Helvetica*',
			'type' =>  '',
			'font-family' => '"Helvetica Neue", Helvetica, sans-serif'
		),
		'impact' => array(
			'value' =>	'impact',
			'label' =>  'Impact',
			'type' =>  '',
			'font-family' => 'Impact, Charcoal, sans-serif'
		),
		'lucida' => array(
			'value' =>	'lucida',
			'label' =>  'Lucida',
			'type' =>  '',
			'font-family' => '"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif'
		),
		'myriadpro' => array(
			'value' =>	'myriadpro',
			'label' =>  'Myriad Pro*',
			'type' =>  '',
			'font-family' => '"Myriad Pro", Myriad, sans-serif'
		),
		'palatino' => array(
			'value' =>	'palatino',
			'label' =>  'Palatino',
			'type' =>  '',
			'font-family' => 'Palatino, "Palatino Linotype", serif'
		),
		'sans-serif' => array(
			'value' =>	'sans-serif',
			'label' =>  'Sans-Serif',
			'type' =>  '',
			'font-family' => 'sans-serif'
		),
		'serif' => array(
			'value' =>	'serif',
			'label' =>  'Serif',
			'type' =>  '',
			'font-family' => 'serif'
		),
		'tahoma' => array(
			'value' =>	'tahoma',
			'label' =>  'Tahoma',
			'type' =>  '',
			'font-family' => 'Tahoma, Geneva, Verdana, sans-serif'
		),
		'timesnewroman' => array(
			'value' =>	'timesnewroman',
			'label' =>  'Times New Roman',
			'type' =>  '',
			'font-family' => '"Times New Roman", serif'
		),
		'trebuchet' => array(
			'value' =>	'trebuchet',
			'label' =>  'Trebuchet',
			'type' =>  '',
			'font-family' => 'Verdana, Geneva, sans-serif'
		),
		'verdana' => array(
			'value' =>	'verdana',
			'label' =>  'Verdana',
			'type' =>  '',
			'font-family' => 'Verdana, Geneva, sans-serif'
		)
	);
	return apply_filters('prima_webfonts', $webfonts);
}
function prima_customfonts() {
	$customfonts = array();
	/*
	$customfonts = array(
		'leaguegothic' => array(
			'value' =>	'leaguegothic',
			'label' =>  'League Gothic',
			'type' =>  'custom',
			'css' => 'leaguegothic.css',
			'font-family' => '"LeagueGothicRegular", sans-serif'
		),
		'museosans' => array(
			'value' =>	'museosans',
			'label' =>  'Museo Sans',
			'type' =>  'custom',
			'css' => 'museosans.css',
			'font-family' => '"MuseoSans500", sans-serif'
		),
		'museoslab' => array(
			'value' =>	'museoslab',
			'label' =>  'Museo Slab',
			'type' =>  'custom',
			'css' => 'museoslab.css',
			'font-family' => '"MuseoSlab500", sans-serif'
		)
	);
	*/
	return apply_filters('prima_customfonts', $customfonts);
}
function prima_googlefonts() {
	$googlefonts = array(
		'cantarell' => array(
			'value' =>	'cantarell',
			'label' =>  'Cantarell',
			'type' =>  'google',
			'family' =>	'Cantarell',
			'font-family' => '"Cantarell", sans-serif'
		),
		'cardo' => array(
			'value' =>	'cardo',
			'label' =>  'Cardo',
			'type' =>  'google',
			'family' =>	'Cardo',
			'font-family' => '"Cardo", serif'
		),
		'crimsontext' => array(
			'value' =>	'crimsontext',
			'label' =>  'Crimson Text',
			'type' =>  'google',
			'family' =>	'Crimson+Text',
			'font-family' => '"Crimson Text", serif'
		),
		'cuprum' => array(
			'value' =>	'cuprum',
			'label' =>  'Cuprum',
			'type' =>  'google',
			'family' =>	'Cuprum',
			'font-family' => '"Cuprum", sans-serif'
		),
		'droidsans' => array(
			'value' =>	'droidsans',
			'label' =>  'Droid Sans',
			'type' =>  'google',
			'family' =>	'Droid+Sans',
			'font-family' => '"Droid Sans", sans-serif'
		),
		'droidsansmono' => array(
			'value' =>	'droidsansmono',
			'label' =>  'Droid Sans Mono',
			'type' =>  'google',
			'family' =>	'Droid+Sans+Mono',
			'font-family' => '"Droid Sans Mono", sans-serif'
		),
		'droidserif' => array(
			'value' =>	'droidserif',
			'label' =>  'Droid Serif',
			'type' =>  'google',
			'family' =>	'Droid+Serif',
			'font-family' => '"Droid Serif", serif'
		),
		'imfell' => array(
			'value' =>	'imfell',
			'label' =>  'IM Fell DW Pica',
			'type' =>  'google',
			'family' =>	'IM+Fell+DW+Pica',
			'font-family' => '"IM Fell DW Pica", serif'
		),
		'inconsolata' => array(
			'value' =>	'inconsolata',
			'label' =>  'Inconsolata',
			'type' =>  'google',
			'family' =>	'Inconsolata',
			'font-family' => '"Inconsolata", sans-serif'
		),
		'josefin' => array(
			'value' =>	'josefin',
			'label' =>  'Josefin Sans',
			'type' =>  'google',
			'family' =>	'Josefin+Sans',
			'font-family' => '"Josefin Sans", sans-serif'
		),
		'lobster' => array(
			'value' =>	'lobster',
			'label' =>  'Lobster',
			'type' =>  'google',
			'family' =>	'Lobster',
			'font-family' => '"Lobster", cursive'
		),
		'molengo' => array(
			'value' =>	'molengo',
			'label' =>  'Molengo',
			'type' =>  'google',
			'family' =>	'Molengo',
			'font-family' => '"Molengo", sans-serif'
		),
		'neucha' => array(
			'value' =>	'neucha',
			'label' =>  'Neucha',
			'type' =>  'google',
			'family' =>	'Neucha',
			'font-family' => '"Neucha", cursive'
		),
		'neuton' => array(
			'value' =>	'neuton',
			'label' =>  'Neuton',
			'type' =>  'google',
			'family' =>	'Neuton',
			'font-family' => '"Neuton", serif'
		),
		'nobile' => array(
			'value' =>	'nobile',
			'label' =>  'Nobile',
			'type' =>  'google',
			'family' =>	'Nobile',
			'font-family' => '"Nobile", sans-serif'
		),
		'oldstandard' => array(
			'value' =>	'oldstandard',
			'label' =>  'Old Standard TT',
			'type' =>  'google',
			'family' =>	'Old+Standard+TT',
			'font-family' => '"Old Standard TT", serif'
		),
		'ptsans' => array(
			'value' =>	'ptsans',
			'label' =>  'PT Sans',
			'type' =>  'google',
			'family' =>	'PT+Sans',
			'font-family' => '"PT Sans", sans-serif'
		),
		'philosopher' => array(
			'value' =>	'philosopher',
			'label' =>  'Philosopher',
			'type' =>  'google',
			'family' =>	'Philosopher',
			'font-family' => '"Philosopher", sans-serif'
		),
		'reeniebeanie' => array(
			'value' =>	'reeniebeanie',
			'label' =>  'Reenie Beanie',
			'type' =>  'google',
			'family' =>	'Reenie+Beanie',
			'font-family' => '"Reenie Beanie", cursive'
		),
		'sortsmill' => array(
			'value' =>	'sortsmill',
			'label' =>  'Sorts Mill Goudy',
			'type' =>  'google',
			'family' =>	'Sorts+Mill+Goudy',
			'font-family' => '"Sorts Mill Goudy", serif'
		),
		'tangerine' => array(
			'value' =>	'tangerine',
			'label' =>  'Tangerine',
			'type' =>  'google',
			'family' =>	'Tangerine',
			'font-family' => '"Tangerine", cursive'
		),
		'vollkorn' => array(
			'value' =>	'vollkorn',
			'label' =>  'Vollkorn',
			'type' =>  'google',
			'family' =>	'Vollkorn',
			'font-family' => '"Vollkorn", serif'
		),
		'yanone' => array(
			'value' =>	'yanone',
			'label' =>  'Yanone Kaffeesatz',
			'type' =>  'google',
			'family' =>	'Yanone+Kaffeesatz',
			'font-family' => '"Yanone Kaffeesatz", sans-serif'
		)
	);
	return apply_filters('prima_googlefonts', $googlefonts);
}

