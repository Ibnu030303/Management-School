<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class Home extends Component implements HasForms
{
    use InteractsWithForms;


    public $name = '';
    public $gender = '';
    public $birthday = '';
    public $religion = '';
    public $contact = '';
    public $profile;
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Student')
                            ->required(),
                        Select::make('gender')
                            ->options([
                                "Male" => "Male",
                                "Female" => "Female"
                            ]),
                        DatePicker::make('birthday')
                            ->label('Birthday'),
                        Select::make('religion')
                            ->options([
                                'Islam' => 'Islam',
                                'Katolik' => 'Katolik',
                                'Protestan' => 'Protestan',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Khonghucu' => 'Khonghucu',
                            ]),
                        TextInput::make('contact'),
                        FileUpload::make('profile')
                            ->label('Profile Picture')
                            ->acceptedFileTypes(['image/*']) // Pilih tipe file yang diterima
                            ->placeholder('Upload your profile picture'), // Teks placeholder untuk input file
                    ])

            ]);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Proses upload foto
        if ($this->profile) {
            $uploadedFile = $this->profile;
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->storeAs('public/students', $fileName);

            $data['profile'] = 'students/' . $fileName;
        }

        Student::insert($data);

        Notification::make()
            ->success()
            ->title('Murid'.$this->name. 'Telah Mendaftar')
            ->sendToDatabase(User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get());
            
        session()->flash('message', 'Saved successfully');
    }

    public function delSession(): void
    {
        session()->forget('message');

        $this->name = '';
        $this->gender = '';
        $this->birthday = '';
        $this->religion = '';
        $this->contact = '';
        $this->profile;
    }
}
