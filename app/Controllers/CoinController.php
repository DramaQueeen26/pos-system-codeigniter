<?php 
namespace App\Controllers;
use App\Models\CoinModel;
use App\Models\AuditModel;
use \Hermawan\DataTables\DataTable;

class CoinController extends BaseController
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
		"user"			=> "",
		"module"		=> "Monedas",
		"action"		=> "",
		"description"	=> ""
	];

	public function createCoin()
	{
		helper('coinValidation');

		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		if(!$this->validate(createCoinValidation())){

			//Mostrar errores de validación
			$errors = $this->validator->getErrors();
			foreach ($errors as $error) {
				$this->errorMessage['text'] = esc($error);
				return sweetAlert($this->errorMessage);
			}

		}

		$name = $this->request->getPost('name');
		$symbol = $this->request->getPost('symbol');

		$CoinModel = new CoinModel();
		$coin = $CoinModel->createCoin([
									'coin' => $name,
									'symbol' => $symbol
								]);

		if(!$coin){
			$this->errorMessage['text'] = "Error al guardar la moneda en la base de datos";
			return sweetAlert($this->errorMessage);
		}

		//PARA LA AUDITORÍA
		$auditUserId = $this->session->get('id');
		$this->auditContent['user_id'] 		= $auditUserId;
		$this->auditContent['action'] 		= "Crear moneda";
		$this->auditContent['description'] 	= "Se ha creado la moneda con ID #" . $CoinModel->getLastId() . " exitosamente.";
		$AuditModel = new AuditModel();
		$AuditModel->createAudit($this->auditContent);
		
		//SWEET ALERT
		$this->successMessage['alert'] 		= "clean";
		$this->successMessage['text'] 		= "La moneda se ha creado correctamente";
		return sweetAlert($this->successMessage);
	}

	public function getCoins()
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$CoinModel = new CoinModel();
				
		return DataTable::of($CoinModel->getCoins())
			->add('Acciones', function($row){
				return '<div class="btn-list"> 
                            <button type="button" class="btnUpdateCoin btn btn-sm btn-primary waves-effect" coin-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#updateCoinModal">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btnDeleteCoin btn btn-sm btn-danger waves-effect" coin-id="'.$row->id.'">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>';
			}, 'last') 
			->toJson();
	}

	public function getCoinById($id)
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$CoinModel = new CoinModel();
		$coin = $CoinModel->getCoinById(['id' => $id]);
		if(!$coin){
			return false;
		}
		return json_encode($coin);
	}

	public function updateCoin()
	{
		helper('coinValidation');

		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		if(!$this->validate(updateCoinValidation())){

			//Mostrar errores de validación
			$errors = $this->validator->getErrors();
			foreach ($errors as $error) {
				$this->errorMessage['text'] = esc($error);
				return sweetAlert($this->errorMessage);
			}

		}

		$id = $this->request->getPost('id');
		$name = $this->request->getPost('name');
		$symbol = $this->request->getPost('symbol');

		$CoinModel = new CoinModel();
		$coin = $CoinModel->updateCoin([
										"name" => $name,
										"symbol" => $symbol
									], $id);

		if(!$coin){
			$this->errorMessage['text'] = "Error actualizar la moneda en la base de datos";
			return sweetAlert($this->errorMessage);
		}

		//PARA LA AUDITORÍA
		$auditUserId = $this->session->get('id');
		$this->auditContent['user_id'] 		= $auditUserId;
		$this->auditContent['action'] 		= "Actualizar moneda";
		$this->auditContent['description'] 	= "Se ha actualizado la moneda con ID #" . $id . " exitosamente.";
		$AuditModel = new AuditModel();
		$AuditModel->createAudit($this->auditContent);
		
		//SWEET ALERT
		$this->successMessage['alert'] 		= "clean";
		$this->successMessage['text'] 		= "La moneda se ha actualizado correctamente";
		return sweetAlert($this->successMessage);
	}

	public function deleteCoin()
	{
		if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

		$id = $this->request->getPost('id');

		$CoinModel = new CoinModel();
		$deleteCoin = $CoinModel->deleteCoin($id);

		if(!$deleteCoin){
			$this->errorMessage['text'] = "La moneda no existe";
			return sweetAlert($this->errorMessage);
		}

		//PARA LA AUDITORÍA
		$auditUserId = $this->session->get('id');
		$this->auditContent['user_id'] 		= $auditUserId;
		$this->auditContent['action'] 		= "Eliminar moneda";
		$this->auditContent['description'] 	= "Se ha eliminado la moneda con ID #" . $id . " exitosamente.";
		$AuditModel = new AuditModel();
		$AuditModel->createAudit($this->auditContent);
		
		//SWEET ALERT
		$this->successMessage['alert'] 		= "clean";
		$this->successMessage['title'] 		= "Moneda eliminada";
		$this->successMessage['text'] 		= "Puede recuperarla desde la papelera";
		return sweetAlert($this->successMessage);
	}
}
