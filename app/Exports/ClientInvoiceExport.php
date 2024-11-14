<?php

namespace App\Exports;

use App\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientInvoiceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoices::all();
    }
}
