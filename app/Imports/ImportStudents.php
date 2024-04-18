<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportStudents implements ToModel
{
    /**
     * Import data from Excel file.
     *
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function import($file)
    {
        // Implement your import logic here
        // For example, you can read the file and process each row

        // Sample implementation:
        // $data = \Maatwebsite\Excel\Facades\Excel::toArray($file)[0];
        // foreach ($data as $row) {
        //     Student::create([
        //         'nis' => $row[0],
        //         'nama' => $row[1],
        //         'gender' => $row[2],
        //     ]);
        // }

        // // Example: return the number of rows imported
        // return count($data);
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'nis' => $row[0],
            'name' => $row[1],
            'gender' => $row[2],
        ]);
    }
}
