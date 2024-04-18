<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Filament\Resources\StudentHasClassResource;
use App\Models\Classroom;
use App\Models\HomeRoom;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Redirect;

class FormStudentClass extends Page
{
    protected static string $resource = StudentHasClassResource::class;

    protected static string $view = 'filament.resources.student-has-class-resource.pages.form-student-class';

    public $students = []; // Menyimpan id siswa yang dipilih dari formulir
    public $classrooms = ''; // Menyimpan id ruang kelas yang dipilih dari formulir
    public $periode = ''; // Menyimpan id periode yang dipilih dari formulir

    public function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Select::make('students') // Mengubah menjadi 'students' untuk sesuai dengan properti yang digunakan dalam class ini
                    ->multiple()
                        ->columnSpan(3)
                        ->options(Student::all()->pluck('name', 'id'))
                        ->label('Nama Student'),
                    Select::make('classrooms') // Mengubah menjadi 'classrooms' untuk sesuai dengan properti yang digunakan dalam class ini
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->label('Class'),
                    Select::make('periode') // Mengubah menjadi 'periode' untuk sesuai dengan properti yang digunakan dalam class ini
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->label('Periode'),
                ])
                ->columns(3)
        ];
    }

    public function save()
    {
        // Mendapatkan data yang dipilih dari formulir
        $students = $this->students;
        $classrooms = $this->classrooms;
        $periode = $this->periode;

        // Membuat array untuk setiap siswa yang akan dimasukkan ke dalam database
        $insert = [];
        foreach ($students as $student) {
            $insert[] = [
                'students_id' => $student,
                'classrooms_id' => $classrooms,
                'periode_id' => $periode,
                'is_open' => 1
            ];
        }

        // Memasukkan data ke dalam database
        StudentHasClass::insert($insert);

        // Mengarahkan pengguna kembali setelah menyimpan data
        return Redirect::to('admin/student-has-classes');
    }
}
