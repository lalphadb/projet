<?php

namespace App\Exports;

use App\Models\Membre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembresExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Membre::select('id', 'prenom', 'nom', 'email', 'telephone', 'date_naissance')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Prénom', 'Nom', 'Email', 'Téléphone', 'Date de naissance'];
    }
}
