<?php

namespace App\Exports;

use App\models\User;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class UserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return[
            "NAME", "EMAIL", "COLLEGE", "STREAM", "TESTNAME" 
        ];
    }
    public function collection()
    {
        return collect(User::getUsers());
    }
}
