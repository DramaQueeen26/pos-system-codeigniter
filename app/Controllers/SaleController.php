<?php 
namespace App\Controllers;
use App\Models\SaleModel;
use App\Models\AuditModel;
use \Hermawan\DataTables\DataTable;

class SaleController extends BaseController
{
	protected $errorMessage = [
		"alert" => "simple",
		"type" => "error",
		"title" => "¡Oops!",
		"text" => ""
	];

	protected $successMessage = [
		"alert" => "simple",
		"type" => "success",
		"title" => "¡Éxito!",
		"text" => ""
	];

	protected $auditContent = [
		"usuario"		=> "",
		"modulo"		=> "Ventas",
		"accion"		=> "",
		"descripcion"	=> ""
	];

	public function createSale()
	{
		helper('saleValidation');

		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		if(!$this->validate(createSaleValidation())){

			//Mostrar errores de validación
			$errors = $this->validator->getErrors();
			foreach ($errors as $error) {
				$this->errorMessage['text'] = esc($error);
				return sweetAlert($this->errorMessage);
			}

		}

		$sale = [
			"cliente" 		=> $this->request->getPost('customer'),
			"usuario" 		=> $this->session->get('identification'),
			"tipo_documento"=> $this->request->getPost('receipt'),
			"moneda" 		=> $this->request->getPost('coin'),
			"tasa" 			=> $this->request->getPost('rate'),
			"impuesto" 		=> $this->request->getPost('tax'),
			"id_metodo_pago"=> $this->request->getPost('paymentMethod'),

		];

		$productCode = $this->request->getPost('productCode');
		$productQuantity = $this->request->getPost('productQuantity');
		$productPrice = $this->request->getPost('productPrice');
		$productStock = $this->request->getPost('productStock');

		$saleDetails = [];

		for($i = 0; $i < count($productCode); $i++){

			$price = str_replace(',', '', $productPrice[$i]);
			$price = floatval($price);

			if($productQuantity[$i] <= 0){
				$this->errorMessage['text'] = "La cantidad tiene que ser mayor a 0, por favor revisa la fila #$productCode[$i]";
				return sweetAlert($this->errorMessage);
			}

			if($price <= 0){
				$this->errorMessage['text'] = "El precio tiene que ser mayor a 0, por favor revisa la fila #$productCode[$i]";
				return sweetAlert($this->errorMessage);
			}

			if($productQuantity[$i] > $productStock[$i]){
				$this->errorMessage['text'] = "La cantidad supera el stock, revisa la fila #$productCode[$i]";
				return sweetAlert($this->errorMessage);
			}

			$data = [
				"producto"	=> $productCode[$i],
				"cantidad"	=> $productQuantity[$i],
				"precio"	=> $price
			];

			array_push($saleDetails, $data);

		}
		
		$SaleModel = new SaleModel();
		$sale = $SaleModel->createSale($sale, $saleDetails);

		if(!$sale){
			$this->errorMessage['text'] = "Error al registrar la venta, intenta nuevamente.";
			return sweetAlert($this->errorMessage);
		}

		//PARA LA AUDITORÍA
		$auditUserId = $this->session->get('identification');
		$this->auditContent['usuario'] 		= $auditUserId;
		$this->auditContent['accion'] 		= "Crear venta";
		$this->auditContent['descripcion'] 	= "Se ha creado la venta con identificacion " . $sale . " exitosamente.";
		$AuditModel = new AuditModel();
		$AuditModel->createAudit($this->auditContent);
		
		//SWEET ALERT
		$this->successMessage['alert'] 		= "clean";
		$this->successMessage['text'] 		= "La venta se ha registrado correctamente";
		return sweetAlert($this->successMessage);
	}

	public function getProducts()
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$db      	= \Config\Database::connect();
		$products 	= $db
						->table('productos')
						->select('productos.codigo, nombre, marcas.marca, categorias.categoria, monedas.simbolo, precio, cant_producto')
						->join('marcas', 'marcas.identificacion = productos.marca')
						->join('categorias', 'categorias.identificacion = productos.categoria')
						->join('monedas', 'monedas.identificacion = productos.categoria')
						->where('productos.estado', 1);
				
		return DataTable::of($products)
			->edit('cant_producto', function($row){
								
				if($row->cant_producto < 5){
					return '<div class="mt-sm-1 d-block"><a href="javascript:void(0)" class="badge bg-soft-danger text-dark p-2 px-3">'.$row->cant_producto.'</a></div>';
				}
				
				if($row->cant_producto < 15){
					return '<div class="mt-sm-1 d-block"><a href="javascript:void(0)" class="badge bg-soft-warning text-dark p-2 px-3">'.$row->cant_producto.'</a></div>';
				}

				return '<div class="mt-sm-1 d-block"><a href="javascript:void(0)" class="badge bg-soft-success text-dark p-2 px-3">'.$row->cant_producto.'</a></div>';
			})
			->edit('precio', function($row){
				$price = number_format($row->precio, 2);
				return $row->simbolo . $price;
			})
			->hide('simbolo')
			->add('Seleccionar', function($row){
				return '<div class="btn-list"> 
							<button type="button" class="btn-select-sale-product btn btn-sm btn-primary waves-effect" data-id="'.$row->codigo.'" data-type="products">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>';
			}, 'first') 
			->toJson();
	}

	public function getSales()
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$SaleModel = new SaleModel();
				
		return DataTable::of($SaleModel->getSales())
			->hide('simbolo')
			->hide('impuesto')
			->hide('tasa')
			->edit('estado', function($row){
						
				if($row->estado == 0){
					return '<div class="mt-sm-1 d-block"><a href="javascript:void(0)" class="badge bg-soft-danger text-danger p-2 px-3">Cancelada</a></div>';
				}

				return '<div class="mt-sm-1 d-block"><a href="javascript:void(0)" class="badge bg-soft-success text-success p-2 px-3">Procesada</a></div>';
			})
			->add('total', function($row){
				$db      	= \Config\Database::connect();
				$db 		= $db
								->table('detalle_ventas')
								->select('SUM(precio*cantidad) as total')
								->where('venta', $row->identificacion)
								->get()->getResultArray();

				// Calcular el impuesto
				$total = (($db[0]['total'] * $row->impuesto) / 100) + $db[0]['total'];
				$total = $total * $row->tasa;
				$total = number_format($total, 2);
				return "$row->simbolo $total";
				
			})
			->add('Acciones', function($row){
				if($row->estado == 1){
					return '<div class="btn-list"> 
								<button type="button" class="btnView btn btn-sm btn-primary waves-effect" data-id="'.$row->identificacion.'" data-type="sales" data-bs-toggle="modal" data-bs-target="#viewModal">
									<i class="far fa-eye"></i>
								</button>
								<button type="button" class="btnDelete btn btn-sm btn-danger waves-effect" data-id="'.$row->identificacion.'" data-type="sales">
									<i class="fas fa-times"></i>
								</button>
							</div>';
				}

				return '<div class="btn-list"> 
								<button type="button" class="btnView btn btn-sm btn-primary waves-effect" data-id="'.$row->identificacion.'" data-type="sales" data-bs-toggle="modal" data-bs-target="#viewModal">
									<i class="far fa-eye"></i>
								</button>
							</div>';

			}, 'last') 
			->filter(function ($builder, $request) {
		
				if ($request->status == ''){
					return true;
				}
				
				return $builder->where('ventas.estado', $request->status);
		
			})
			->toJson();
	}

	public function getSaleById($identification)
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$SaleModel = new SaleModel();
		$sale = $SaleModel->getSaleById(['ventas.identificacion' => $identification]);
		
		if(!$sale){
			return false;
		}

		return json_encode($sale);
	}

	public function getRate($identification)
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$SaleModel = new SaleModel();

		$SaleModel = $SaleModel->getRate($identification);

		if(!$SaleModel){
			
			return false;
		}

		return json_encode($SaleModel);

	}

	public function deleteSale()
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$identification = $this->request->getPost('identification');

		$SaleModel = new SaleModel();
		$deleteSale = $SaleModel->deleteSale($identification);

		if(!$deleteSale){
			$this->errorMessage['text'] = "Ocurrió un error al anular la venta";
			return sweetAlert($this->errorMessage);
		}

		//PARA LA AUDITORÍA
		$auditUserId = $this->session->get('identification');
		$this->auditContent['usuario'] 		= $auditUserId;
		$this->auditContent['accion'] 		= "Eliminar venta";
		$this->auditContent['descripcion'] 	= "Se ha eliminado la venta con identificación #" . $identification . " exitosamente.";
		$AuditModel = new AuditModel();
		$AuditModel->createAudit($this->auditContent);
		
		//SWEET ALERT
		$this->successMessage['alert'] 		= "clean";
		$this->successMessage['text'] 		= "Venta anulada";
		return sweetAlert($this->successMessage);
	}

}
