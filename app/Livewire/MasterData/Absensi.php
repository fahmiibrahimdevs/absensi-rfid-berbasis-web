<?php

namespace App\Livewire\MasterData;

use App\Models\Absensi as ModelsAbsensi;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class Absensi extends Component
{
    use WithPagination;
    #[Title('Absensi')]

    protected $listeners = [
        'delete',
        'load'
    ];

    protected $rules = [
        'uid_card'            => 'required',
        'id_mahasiswa'            => 'required',
        'tanggal'             => 'required',
        'waktu'               => 'required',
        'status'              => '',
        'keterangan'          => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $uid_card, $id_mahasiswa, $tanggal, $waktu, $status, $keterangan;

    public function mount()
    {
        $this->uid_card            = '';
        $this->id_mahasiswa            = '';
        $this->tanggal             = date('Y-m-d');
        $this->waktu               = '';
        $this->status              = 'opsi1';
        $this->keterangan          = '';
    }

    public function load()
    {
        $this->render();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsAbsensi::select('absensi.*', 'mahasiswa.nama_mahasiswa', 'kelas.nama_kelas')
            ->where(function ($query) use ($search) {
                $query->where('nama_mahasiswa', 'LIKE', $search);
                $query->orWhere('kelas.nama_kelas', 'LIKE', $search);
                $query->orWhere('tanggal', 'LIKE', $search);
                $query->orWhere('waktu', 'LIKE', $search);
                $query->orWhere('status', 'LIKE', $search);
                $query->orWhere('keterangan', 'LIKE', $search);
            })
            ->join('mahasiswa', 'mahasiswa.id', 'absensi.id_mahasiswa')
            ->join('kelas', 'kelas.id', 'mahasiswa.id_kelas')
            ->orderBy('id', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.master-data.absensi', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsAbsensi::create([
            'uid_card'            => $this->uid_card,
            'id_mahasiswa'            => $this->id_mahasiswa,
            'tanggal'             => $this->tanggal,
            'waktu'               => $this->waktu,
            'status'              => $this->status,
            'keterangan'          => $this->keterangan,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsAbsensi::where('id', $id)->first();
        $this->dataId           = $id;
        $this->uid_card         = $data->uid_card;
        $this->id_mahasiswa         = $data->id_mahasiswa;
        $this->tanggal          = $data->tanggal;
        $this->waktu            = $data->waktu;
        $this->status           = $data->status;
        $this->keterangan       = $data->keterangan;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsAbsensi::findOrFail($this->dataId)->update([
                'uid_card'            => $this->uid_card,
                'id_mahasiswa'            => $this->id_mahasiswa,
                'tanggal'             => $this->tanggal,
                'waktu'               => $this->waktu,
                'status'              => $this->status,
                'keterangan'          => $this->keterangan,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->dataId = null;
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
        ModelsAbsensi::findOrFail($this->dataId)->delete();
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
    }

    private function resetInputFields()
    {
        $this->uid_card            = '';
        $this->id_mahasiswa            = '';
        $this->tanggal             = date('Y-m-d');
        $this->waktu               = '';
        $this->status              = 'opsi1';
        $this->keterangan          = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
