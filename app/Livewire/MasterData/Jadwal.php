<?php

namespace App\Livewire\MasterData;

use App\Models\Jadwal as ModelsJadwal;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class Jadwal extends Component
{
    use WithPagination;
    #[Title('Jadwal')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'jam_mulai'           => 'required',
        'jam_selesai'         => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $jam_mulai, $jam_selesai;

    public function mount()
    {
        $this->jam_mulai           = '';
        $this->jam_selesai         = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsJadwal::select('jadwal.*')
            ->where(function ($query) use ($search) {
                $query->where('jam_mulai', 'LIKE', $search);
                $query->orWhere('jam_selesai', 'LIKE', $search);
            })
            ->orderBy('id', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.master-data.jadwal', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsJadwal::create([
            'jam_mulai'           => $this->jam_mulai,
            'jam_selesai'         => $this->jam_selesai,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsJadwal::where('id', $id)->first();
        $this->dataId           = $id;
        $this->jam_mulai        = $data->jam_mulai;
        $this->jam_selesai      = $data->jam_selesai;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsJadwal::findOrFail($this->dataId)->update([
                'jam_mulai'           => $this->jam_mulai,
                'jam_selesai'         => $this->jam_selesai,
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
        ModelsJadwal::findOrFail($this->dataId)->delete();
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
        $this->jam_mulai           = '';
        $this->jam_selesai         = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
