<?php

return [
	'sidebar' => [
		'dashboard' => 'Dashboard',
		'users' => 'Users Management',
		'facility' => 'Facility Management',
		'specialty' => 'Specialty Management',
		'doctor' => 'Doctor Management',
	],

	// Doctor
	'doctor' => [
		'title' => 'Doctor Management',
		'form_title_add' => 'Doctor Form - Add',
		'form_title_edit' => 'Doctor Form - Edit',
		'facility' => 'Facility',
		'specialty' => 'Specialty',
		'experience_years' => 'Experience years',
		'validation' => [
			'required' => ':Attribute is required',
			'image' => ':Attribute must be an image file',
			'email' => ':Attribute must be a valid email address',
			'url' => ':Attribute must be a valid URL',
			'integer' => ':Attribute must be an integer',
			'min' => ':Attribute must be at least {min}',
		],
	],

	// Facility
	'facility' => [
		'title' => 'Facility Management',
		'form_title_add' => 'Facility Form - Add',
		'form_title_edit' => 'Facility Form - Edit',
		'type' => 'Type',
		'tax_code' => 'Tax code',
		'address' => 'Address',
		'website' => 'Website',
		'map_url' => 'Map URL',
		'validation' => [
			'required' => ':Attribute is required',
			'image' => ':Attribute must be an image file',
			'email' => ':Attribute must be a valid email address',
			'url' => ':Attribute must be a valid URL',
			'integer' => ':Attribute must be an integer',
			'min' => ':Attribute must be at least {min}',
		],
	],
];
