<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends BaseController
{
    public function saleInvoice($id)
    {

        if(!$this->session->has('name')){
			return redirect()->to(base_url());
		}

        $db     = \Config\Database::connect();
		$venta 	= $db
				->table('ventas')
				->select('ventas.identificacion as idVenta, clientes.identificacion as clienteId, clientes.nombre as clienteNom, tasa, impuestos.impuesto, impuestos.porcentaje, monedas.moneda, monedas.simbolo, ventas.actualizado_en, ventas.creado_en, detalle_ventas.identificacion as idDetalleVenta, productos.nombre as producto, cantidad, detalle_ventas.precio, productos.codigo, alto_caucho.alto_numero, ancho_caucho.ancho_numero, categorias.categoria, marcas.marca, usuarios.nombre as vendedor, ventas.estado')
				->join('detalle_ventas', 'detalle_ventas.venta = ventas.identificacion')
				->join('productos', 'productos.codigo = detalle_ventas.producto')
				->join('clientes', 'clientes.identificacion = ventas.cliente')
				->join('usuarios', 'usuarios.identificacion = ventas.usuario')
                ->join('ancho_caucho', 'ancho_caucho.id_ancho_caucho = productos.id_ancho_caucho')
			    ->join('alto_caucho', 'alto_caucho.id_alto_caucho = productos.id_alto_caucho')
                ->join('marcas', 'marcas.identificacion = productos.marca')
			    ->join('categorias', 'categorias.identificacion = productos.categoria')
				->join('impuestos', 'impuestos.identificacion = ventas.impuesto')
				->join('monedas', 'monedas.identificacion = ventas.moneda')
				->where('ventas.identificacion', $id)
                ->get()->getResult();

        $data = [
            "id"    => $id,
            "venta" => $venta,
            "businessName" 			=> $this->businessName,
            "businessIdentification"=> $this->businessIdentification,
            "businessAddress" 		=> $this->businessAddress,
            "businessPhone" 		=> $this->businessPhone
        ];

        // return view('app/invoices/saleInvoice', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('app/invoices/saleInvoice', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $dompdf->stream();

    }
}
