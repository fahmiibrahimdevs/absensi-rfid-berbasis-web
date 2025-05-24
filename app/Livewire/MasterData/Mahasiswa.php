<?php

namespace App\Livewire\MasterData;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Mahasiswa as ModelsMahasiswa;

class Mahasiswa extends Component
{
    use WithPagination;
    #[Title('Mahasiswa')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_kelas'            => 'required',
        'nim'                 => 'required',
        'nama_mahasiswa'      => 'required',
        'jenis_kelamin'       => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $uid_card, $id_kelas, $nim, $nama_mahasiswa, $jenis_kelamin;
    public $kelases;

    public function mount()
    {
        $this->kelases             = Kelas::get();
        $this->uid_card            = '';
        $this->id_kelas            = $this->kelases->first()->id;
        $this->nim                 = '240332107';
        $this->nama_mahasiswa      = 'Fahmi Ibra';
        $this->jenis_kelamin       = '-';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsMahasiswa::select('mahasiswa.*', 'kelas.nama_kelas')
            ->where(function ($query) use ($search) {
                $query->where('uid_card', 'LIKE', $search);
                $query->orWhere('nama_kelas', 'LIKE', $search);
                $query->orWhere('nim', 'LIKE', $search);
                $query->orWhere('nama_mahasiswa', 'LIKE', $search);
                $query->orWhere('jenis_kelamin', 'LIKE', $search);
            })
            ->join('kelas', 'kelas.id', 'mahasiswa.id_kelas')
            ->orderBy('id', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.master-data.mahasiswa', compact('data'));
    }

    public function store()
    {
        $this->validate();

        // dd($this->id_kelas, $this->nim, $this->nama_mahasiswa, $this->jenis_kelamin);

        ModelsMahasiswa::create([
            'uid_card'            => $this->uid_card,
            'id_kelas'            => $this->id_kelas,
            'nim'                 => $this->nim,
            'nama_mahasiswa'      => $this->nama_mahasiswa,
            'jenis_kelamin'       => $this->jenis_kelamin,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
        $this->dispatch('save-data');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsMahasiswa::where('id', $id)->first();
        $this->dataId           = $id;
        $this->uid_card         = $data->uid_card;
        $this->id_kelas         = $data->id_kelas;
        $this->nim              = $data->nim;
        $this->nama_mahasiswa   = $data->nama_mahasiswa;
        $this->jenis_kelamin    = $data->jenis_kelamin;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsMahasiswa::findOrFail($this->dataId)->update([
                'uid_card'            => $this->uid_card,
                'id_kelas'            => $this->id_kelas,
                'nim'                 => $this->nim,
                'nama_mahasiswa'      => $this->nama_mahasiswa,
                'jenis_kelamin'       => $this->jenis_kelamin,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->dataId = null;
            $this->dispatch('save-data');
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type'      => 'warning',
            'message'   => 'Are you sure?',
            'text'      => 'If you delete the data, it cannot be restored!'
        ]);
    }

    public function delete()
    {
        ModelsMahasiswa::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
    }

    public function updatingLengthData()
    {
        $this->resetPage();
    }

    private function searchResetPage()
    {
        if ($this->searchTerm !== $this->previousSearchTerm) {
            $this->resetPage();
        }

        $this->previousSearchTerm = $this->searchTerm;
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type'      => $type,
            'message'   => $message,
            'text'      => $text
        ]);

        $this->resetInputFields();
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
        $this->dispatch('initSelect2');
        $mode === false ? $this->uid_card = '' : '';
    }

    private function resetInputFields()
    {
        $this->nim                 = '';
        $this->nama_mahasiswa      = '';
        $this->jenis_kelamin       = '-';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
