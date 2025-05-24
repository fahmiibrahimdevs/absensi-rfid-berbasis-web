<?php

namespace App\Livewire\MasterData;

use App\Models\Kelas as ModelsKelas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class Kelas extends Component
{
    use WithPagination;
    #[Title('Kelas')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_kelas'          => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $nama_kelas;

    public function mount()
    {
        $this->nama_kelas          = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsKelas::select('kelas.*')
            ->where(function ($query) use ($search) {
                $query->where('nama_kelas', 'LIKE', $search);
            })
            ->orderBy('id', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.master-data.kelas', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsKelas::create([
            'nama_kelas'          => $this->nama_kelas,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsKelas::where('id', $id)->first();
        $this->dataId           = $id;
        $this->nama_kelas       = $data->nama_kelas;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsKelas::findOrFail($this->dataId)->update([
                'nama_kelas'          => $this->nama_kelas,
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
        ModelsKelas::findOrFail($this->dataId)->delete();
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
        $this->nama_kelas          = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
