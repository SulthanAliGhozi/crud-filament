<?php

namespace App\Livewire;

use App\Models\Siswa;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class Home extends Component implements HasForms
{

    use InteractsWithForms;

    public $nama = '';
    public $tanggal_lahir = '';
    public $kontak = '';
    public $agama = '';
    public $kelamin = '';
    public $alamat = '';
    public $profil;

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->placeholder('Masukan Nama Siswa'),
                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required(),
                    TextInput::make('kontak')
                        ->label('No Telp')
                        ->required()
                        ->numeric()
                        ->placeholder('Masukan No Telp. Siswa'),
                    Select::make('agama')
                        ->options([
                            'Islam' => 'Islam',
                            'Kristen' => 'Kristen',
                            'Katolik' => 'Katolik',
                            'Hindu' => 'Hindu',
                            'Buddha' => 'Buddha',
                            'Khonghucu' => 'Khonghucu',
                        ])
                        ->placeholder('Pilih Agama Siswa')
                        ->required(),
                    Select::make('kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->placeholder('Pilih Kelamin Siswa Siswa')
                        ->required(),
                    Textarea::make('alamat')->required()
                        ->placeholder('Masukan Alamat Siswa'),
                    FileUpload::make('profil')
                        ->directory('siswas')
                        ->imageEditor()
                        ->downloadable()
                        ->openable()
                        ->previewable(true)
                        ->required(),
                        ]),
        ]); 
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save(): void
    {
        $data  = $this->form->getState();

        //process upload
        if ($this->profile) {
            $uploadedFile = $this->profile;
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->storeAs('public/siswas', $fileName);

            $data['profil'] = 'siswas/'.$fileName;
        }

        Siswa::insert($data);

        Notification::make()
            ->success()
            ->title('Murid '.$this->name. ' telah mendaftar')
            ->sendToDatabase(User::whereHas('roles', function ($query) {
                $query->where('nama', 'admin');
            })->get());

        session()->flash('message', 'Save Successfully');
    }

    public function delSession(): void
    {
        session()->forget('message');
        $this->nama = '';
        $this->kelamin = '';
        $this->tanggal_lahir = '';
        $this->agama = '';
        $this->kontak = '';
        $this->alamat = '';
        $this->profil = null;
    }
}
