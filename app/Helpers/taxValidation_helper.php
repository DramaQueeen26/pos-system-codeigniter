<?php 

function createTaxValidation()
{
	$tax = [
		'name' => [
			'label' => 'name',
			'rules' => 'required|alpha_numeric_punct|is_unique[impuestos.impuesto]',
			'errors' => [
				'required' => 'El nombre del impuesto es requerido',
				'alpha_numeric_punct' => 'Para el nombre sólo se permiten carácteres alfanuméricos y el símbolo de porcentaje (%).',
				'is_unique' => 'El nombre del impuesto ya existe'
			]
		],
		'percentage' => [
			'label' => 'percentage',
			'rules' => 'required|max_length[2]|numeric',
			'errors' => [
				'required' => 'El porcentaje del impuesto es requerido',
				'max_length' => 'Para el porcentaje solo se permite un máximo de 2 números',
				'numeric' => 'Para el porcentaje sólo se permiten números'
			]
		]
	];
	return $tax;
}

function updateTaxValidation()
{
	$updateTax = [
		'identification' => [
			'label' => 'identification',
			'rules' => 'required|is_not_unique[marcas.identificacion]',
			'errors' => [
				'required' => 'La identificación del impuesto es requerida',
				'is_not_unique' => 'La identificación del impuesto no existe'
			]
		],
		'name' => [
			'label' => 'name',
			'rules' => 'required|alpha_numeric_punct|is_unique[impuestos.impuesto,identificacion,{identification}]',
			'errors' => [
				'required' => 'El nombre del impuesto es requerido',
				'alpha_numeric_punct' => 'Para el nombre sólo se permiten carácteres alfanuméricos y el símbolo de porcentaje (%).',
				'is_unique' => 'El nombre del impuesto ya existe'
			]
		],
		'percentage' => [
			'label' => 'percentage',
			'rules' => 'required|max_length[2]|numeric',
			'errors' => [
				'required' => 'El porcentaje del impuesto es requerido',
				'max_length' => 'Para el porcentaje solo se permite un máximo de 2 números',
				'numeric' => 'Para el porcentaje sólo se permiten números'
			]
		]
	];

	return $updateTax;
}