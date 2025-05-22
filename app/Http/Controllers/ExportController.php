<?php

namespace App\Http\Controllers;

use App\Exports\MembresExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportMembresExcel()
    {
        return Excel::download(new MembresExport, 'membres.xlsx');
    }

    public function exportMembresPdf()
    {
        $membres = (new MembresExport)->collection();

        $pdf = \PDF::loadView('exports.membres-pdf', ['membres' => $membres]);
        return $pdf->download('membres.pdf');
    }
}
