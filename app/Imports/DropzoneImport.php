<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DropzoneImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public Collection $imported;
    public array $errors = [];
    
    public function __construct() {
        $this->imported = collect();
    }

    public function collection(Collection $rows): void
    {
        if(!empty($this->errors)) {
            return;
        }
        $this->imported = $rows;

        // foreach($rows as $row) {
        //     $this->imported->push($row);
        // }
    }
}
