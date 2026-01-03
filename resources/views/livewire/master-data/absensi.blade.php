<div>
    <section class='section custom-section'>
        <div class='section-header'>
            <h1>Absensi</h1>
            <h1 class="ml-auto" wire:ignore>Status IoT: <span id="status-iot" class="tw-text-green-500">...</span>
            </h1>
        </div>

        <div class='section-body'>
            <div class='card'>
                <h3>Tabel Absensi</h3>
                <div class='card-body'>
                    <div class='show-entries'>
                        <p class='show-entries-show'>Show</p>
                        <select wire:model.live='lengthData' id='length-data'>
                            <option value='25'>25</option>
                            <option value='50'>50</option>
                            <option value='100'>100</option>
                            <option value='250'>250</option>
                            <option value='500'>500</option>
                        </select>
                        <p class='show-entries-entries'>Entries</p>
                    </div>
                    <div class='search-column'>
                        <p>Search: </p><input type='search' wire:model.live.debounce.750ms='searchTerm' id='search-data'
                            placeholder='Search here...' class='form-control'>
                    </div>
                    <div class='table-responsive tw-max-h-96'>
                        <table>
                            <thead class='tw-sticky tw-top-0'>
                                <tr class='tw-text-gray-700'>
                                    <th width='6%' class='text-center'>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th class='text-center'><i class='fas fa-cog'></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                <tr class='text-center'>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class='text-left'>{{ $row->nama_mahasiswa }}</td>
                                    <td class='text-left'>{{ $row->nama_kelas }}</td>
                                    <td class='text-left'>{{ $row->tanggal }}</td>
                                    <td class='text-left'>{{ $row->waktu }}</td>
                                    <td class='text-left'>
                                        @if ($row->status == "terlambat")
                                        <span
                                            class="tw-bg-yellow-300 tw-px-3 tw-py-1 tw-rounded-full tw-text-yellow-800">{{ $row->status }}</span>
                                        @else
                                        <span
                                            class="tw-bg-green-300 tw-px-3 tw-py-1 tw-rounded-full tw-text-green-800">{{ $row->status }}</span>
                                        @endif
                                    </td>
                                    <td class='text-left'>{{ $row->keterangan }}</td>
                                    <td>
                                        <button wire:click.prevent='edit({{ $row->id }})' class='btn btn-primary'
                                            data-toggle='modal' data-target='#formDataModal'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button wire:click.prevent='deleteConfirm({{ $row->id }})'
                                            class='btn btn-danger'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='8' class='text-center'>No data available in the table</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class='mt-5 px-3'>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- <button wire:click.prevent='isEditingMode(false)' class='btn-modal' data-toggle='modal' data-backdrop='static'
            data-keyboard='false' data-target='#formDataModal'>
            <i class='far fa-plus'></i>
        </button> --}}
    </section>

    <div class='modal fade' wire:ignore.self id='formDataModal' aria-labelledby='formDataModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='formDataModalLabel'>{{ $isEditing ? 'Edit Data' : 'Add Data' }}</h5>
                    <button type='button' wire:click='cancel()' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <form>
                    <div class='modal-body'>
                        <div class='form-group'>
                            <label for='uid_card'>UID Card</label>
                            <input type='text' wire:model='uid_card' id='uid_card' class='form-control'>
                            @error('uid_card') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='id_siswa'>ID Siswa</label>
                            <input type='text' wire:model='id_siswa' id='id_siswa' class='form-control'>
                            @error('id_siswa') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='tanggal'>Tanggal</label>
                            <input type='date' wire:model='tanggal' id='tanggal' class='form-control'>
                            @error('tanggal') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='waktu'>Waktu</label>
                            <input type='time' wire:model='waktu' id='waktu' class='form-control'>
                            @error('waktu') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='status'>Status</label>
                            <select wire:model='status' id='status' class='form-control select2'>
                                <option value='opsi1'>Opsi 1</option>
                                <option value='opsi2'>Opsi 2</option>
                                <option value='opsi3'>Opsi 3</option>
                            </select>
                            @error('status') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='keterangan'>Keterangan</label>
                            <textarea wire:model='keterangan' id='keterangan' class='form-control'
                                style='height: 100px !important;'></textarea>
                            @error('keterangan') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' wire:click='cancel()' class='btn btn-secondary tw-bg-gray-300'
                            data-dismiss='modal'>Close</button>
                        <button type='submit' wire:click.prevent='{{ $isEditing ? 'update()' : 'store()' }}'
                            wire:loading.attr='disabled' class='btn btn-primary tw-bg-blue-500'>Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('general-css')
<link href="{{ asset('assets/midragon/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('js-libraries')
<script src="{{ asset('/assets/midragon/select2/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
@endpush

@push('scripts')
<script>
    window.addEventListener('initSelect2', event => {
        $(document).ready(function () {
            $('.select2').select2();

            $('.select2').on('change', function (e) {
                var id = $(this).attr('id');
                // console.log(id)
                var data = $(this).select2("val");
                @this.set(id, data);
            });
        });
    })

</script>
<script>
    $(document).ready(function () {
        startConnect()

        window.addEventListener('save-data', event => {
            publishMessage("ABSENSI/FEEDBACK_WEB", "Berhasil Didaftarkan");
            publishMessage("ABSENSI/MODE", "2");
        })
    });

    function publishMessage(topic, payload) {
        message = new Paho.MQTT.Message(payload)
        message.destinationName = topic;
        client.send(message);
    }

    function startConnect() {
        clientID = "client_ind" + parseInt(Math.random() * 100);
        host = @json($mqtt_host);
        port = @json($mqtt_port);
        client = new Paho.MQTT.Client(host, Number(port), clientID);
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        client.connect({
            onSuccess: onConnect,
            userName: @json($mqtt_username),
            password: @json($mqtt_password)
        });

    }

    function onConnect() {
        client.subscribe("ABSENSI/FEEDBACK_CHECKIN");
        $('#status-iot').html('Connected')
    }

    function onConnectionLost(responseObject) {
        $('#status-iot').html('Disconnected')
        $('#status-iot').addClass('tw-text-red-500')

        if (responseObject.errorCode !== 0) {}
    }

    function onMessageArrived(message) {
        if (message.destinationName == "ABSENSI/FEEDBACK_CHECKIN") {
            Livewire.dispatch('load')
        }
        // else if (message.destinationName == "cam/token") {
        //     console.log("image token cam incoming");
        //     let data = message.payloadString;
        //     document.getElementById("statcam").src = data;
        // }
    }

    function startDisconnect() {
        client.disconnect();
        document.getElementById("messages").innerHTML = '<span>Disconnected</span><br/>';
    }

</script>
@endpush
