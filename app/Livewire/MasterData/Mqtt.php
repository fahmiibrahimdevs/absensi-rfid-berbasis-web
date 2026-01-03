<?php

namespace App\Livewire\MasterData;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Mqtt as ModelsMqtt;

class Mqtt extends Component
{
    use WithPagination;
    #[Title('Mqtt')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'host'                => 'required',
        'port'                => 'required',
        'username'            => 'required',
        'password'            => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $host, $port, $username, $password;

    public function mount()
    {
        $this->host                = '';
        $this->port                = '';
        $this->username            = '';
        $this->password            = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsMqtt::select('mqtt.*')
            ->where(function ($query) use ($search) {
                $query->where('host', 'LIKE', $search);
                $query->orWhere('port', 'LIKE', $search);
                $query->orWhere('username', 'LIKE', $search);
                $query->orWhere('password', 'LIKE', $search);
            })
            ->orderBy('id', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.master-data.mqtt', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsMqtt::create([
            'host'                => $this->host,
            'port'                => $this->port,
            'username'            => $this->username,
            'password'            => $this->password,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsMqtt::where('id', $id)->first();
        $this->dataId           = $id;
        $this->host             = $data->host;
        $this->port             = $data->port;
        $this->username         = $data->username;
        $this->password         = $data->password;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsMqtt::findOrFail($this->dataId)->update([
                'host'                => $this->host,
                'port'                => $this->port,
                'username'            => $this->username,
                'password'            => $this->password,
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
        ModelsMqtt::findOrFail($this->dataId)->delete();
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
        $this->host                = '';
        $this->port                = '';
        $this->username            = '';
        $this->password            = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
