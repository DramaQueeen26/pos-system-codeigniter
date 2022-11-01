<?php 

function createPurchaseValidation()
{
	$purchase = [
		'date' => [
			'label' => 'date',
			'rules' => 'required|valid_date',
			'errors' => [
				'required' => 'La fecha es requerida',
				'valid_date' => 'La fecha tiene que tener un formato dd/mm/aaaa'
			]
		],
		'provider' => [
			'label' => 'provider',
			'rules' => 'required|alpha_numeric_punct|is_not_unique[proveedores.codigo]',
			'errors' => [
				'required' => 'El proveedor es requerido',
				'alpha_numeric_punct' => 'Ingresa un proveedor válido',
				'is_not_unique' => 'El proveedor no existe'
			]
		],
		'receipt' => [
			'label' => 'receipt',
			'rules' => 'required|is_not_unique[tipo_documento.identificacion]',
			'errors' => [
				'required' => 'El comprobante es requerido',
				'is_not_unique' => 'El comprobante no existe'
			]
		],
		'reference' => [
			'label' => 'reference',
			'rules' => 'required|numeric|is_unique[compras.referencia]|min_length[5]|max_length[25]',
			'errors' => [
				'required' => 'El número de referencia es requerido',
				'numeric' => 'El número de referencia debe contenter números solamente',
				'is_unique' => 'El número de referencia ya existe',
				'min_length' => 'La referencia debe tener mínimo 5 números',
				'max_length' => 'La referencia debe tener máximo 25 números'
			]
		],
		'coin' => [
			'label' => 'coin',
			'rules' => 'required|numeric|is_not_unique[monedas.identificacion]',
			'errors' => [
				'required' => 'La moneda es requerida',
				'numeric' => 'Ingresa una moneda válida',
				'is_not_unique' => 'La moneda no existe'
			]
		],
		'productCode.*' => [
			'label' => 'productId',
			'rules' => 'required|alpha_numeric_punct|is_not_unique[productos.codigo]',
			'errors' => [
				'required' => 'El producto es requerido',
				'alpha_numeric_punct' => 'El producto {value} es incorrecto',
				'is_not_unique' => 'El producto {value} no existe'
			]
		],
		'productQuantity.*' => [
			'label' => 'productQuantity',
			'rules' => 'required|numeric',
			'errors' => [
				'required' => 'La cantidad del producto es requerida',
				'numeric' => 'Solo se permiten números para la cantidad'
			]
		],
		'productPrice.*' => [
			'label' => 'productPrice',
			'rules' => 'required|max_length[10]',
			'errors' => [
				'required' => 'El precio es requerido',
				'max_length' => 'El precio no debe contener más de 10 carácteres'
			]
		]
		
	];

	return $purchase;
}

function updatePurchaseValidation()
{
		$updatePurchase = [
		'date' => [
			'label' => 'date',
			'rules' => 'required|valid_date',
			'errors' => [
				'required' => 'La fecha es requerida',
				'valid_date' => 'La fecha tiene que tener un formato dd/mm/aaaa'
			]
		],
		'provider' => [
			'label' => 'provider',
			'rules' => 'required|alpha_numeric_punct|is_not_unique[proveedores.codigo]',
			'errors' => [
				'required' => 'El proveedor es requerido',
				'alpha_numeric_punct' => 'Ingresa un proveedor válido',
				'is_not_unique' => 'El proveedor no existe'
			]
		],
		'receipt' => [
			'label' => 'receipt',
			'rules' => 'required|is_not_unique[tipo_documento.identificacion]',
			'errors' => [
				'required' => 'El comprobante es requerido',
				'is_no_unique' => 'El comprobante no existe'
			]
		],
		'reference' => [
			'label' => 'reference',
			'rules' => 'required|numeric|is_unique[compras.referencia,identificacion,{identification}]|min_length[5]|max_length[25]',
			'errors' => [
				'required' => 'El número de referencia es requerido',
				'numeric' => 'El número de referencia debe contenter números solamente',
				'is_unique' => 'El número de referencia ya existe',
				'min_length' => 'La referencia debe tener mínimo 5 números',
				'max_length' => 'La referencia debe tener máximo 25 números'
			]
		],
		'coin' => [
			'label' => 'coin',
			'rules' => 'required|numeric|is_not_unique[monedas.identificacion]',
			'errors' => [
				'required' => 'La moneda es requerida',
				'numeric' => 'Ingresa una moneda válida',
				'is_not_unique' => 'La moneda no existe'
			]
		],
		'productCode.*' => [
			'label' => 'productCode',
			'rules' => 'required|alpha_numeric_punct|is_not_unique[productos.codigo]',
			'errors' => [
				'required' => 'El producto es requerido',
				'alpha_numeric_punct' => 'El producto {value} es incorrecto',
				'is_not_unique' => 'El producto {value} no existe'
			]
		],
		'productQuantity.*' => [
			'label' => 'productQuantity',
			'rules' => 'required|numeric',
			'errors' => [
				'required' => 'La cantidad del producto es requerida',
				'numeric' => 'Solo se permiten números para la cantidad'
			]
		],
		'productPrice.*' => [
			'label' => 'productPrice',
			'rules' => 'required|max_length[10]',
			'errors' => [
				'required' => 'El precio es requerido',
				'max_length' => 'El precio no debe contener más de 10 carácteres'
			]
		]
		
	];

	return $updatePurchase;
}