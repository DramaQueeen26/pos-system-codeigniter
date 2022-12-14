<?php

namespace App\Models;

use CodeIgniter\Model;

class TaxModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'impuestos';
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ["identificacion", "impuesto", "porcentaje", "estado", "actualizado_en", "creado_en"];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'creado_en';
	protected $updatedField         = 'actualizado_en';

	public function createTax($data)
	{
		if($this->save($data)){
			return true;
		}
		
		return false;
	}

	public function getTaxes()
	{
		$query = $this
			->select('identificacion, impuesto, porcentaje, estado');
		return $query;
	}

	public function getTaxById($data)
	{
		$query = $this->where($data);
		return $query->get()->getResultArray();
	}

	

	public function updateTax($data, $identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set($data)
				->update();
		return $query;	
	}

	public function deleteTax($identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set('estado', 0)
				->update();
		return $query;
	}

	public function recoverTax($identification)
	{
		$query = $this
				->where('identificacion', $identification)
				->set('estado', 1)
				->update();
		return $query;
	}
}
