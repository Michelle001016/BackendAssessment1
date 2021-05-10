<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Product;
use Illuminate\Support\Facades\Hash;

class ContactsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Contact|null
     */
    public function model(array $row)
    {
        return new Product([
        'user_id'=>$row[1],
        'name'   => $row[2],
        'price'  => $row[3], 
        ]);
    }
}

