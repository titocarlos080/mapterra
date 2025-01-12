<?php

namespace App\Imports;

use App\Models\Lote;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LotesImport implements ToModel, WithHeadingRow
{
    protected $predioId;

    // Constructor para pasar el predioId
    public function __construct($predioId)
    {
        $this->predioId = $predioId;
    }
    public function model(array $row)
    {  
         
        return new Lote([
            'codigo' => $row['codigo'], // Nombre del encabezado en el archivo Excel
            'nombre' => $row['nombre'],
            'hectareas' => $row['hectareas'],
            'predio_id'=> $this->predioId
         ]);
    }
}
