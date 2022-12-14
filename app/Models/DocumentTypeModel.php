<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentTypeModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'tipo_documento';
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ["identificacion", "nombre", "estado", "actualizado_en", "creado_en"];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'creado_en';
	protected $updatedField         = 'actualizado_en';

	public function createDocumentType($data)
	{
		if($this->save($data)){
			return true;
		}
		
		return false;
	}

	public function getDocumentsType()
	{
		$query = $this
			->select('identificacion, nombre, estado');
		return $query;
	}

	public function getDocumentTypeById($data)
	{
		$query = $this->where($data);
		return $query->get()->getResultArray();
	}

	

	public function updateDocumentType($data, $identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set($data)
				->update();
		return $query;	
	}

	public function deleteDocumentType($identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set('estado', 0)
				->update();
		return $query;
	}

	public function recoverDocumentType($identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set('estado', 1)
				->update();
		return $query;
	}
}
