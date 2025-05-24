<div>
    <section class='section custom-section'>
        <div class='section-header'>
            <h1>Mahasiswa</h1>
            <h1 class="ml-auto" wire:ignore>Status IoT: <span id="status-iot" class="tw-text-green-500">...</span>
            </h1>
        </div>

        <div class='section-body'>
            <div class='card'>
                <h3>Tabel Mahasiswa</h3>
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
                                    <th>UID Card</th>
                                    <th>Kelas</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>JK</th>
                                    <th class='text-center'><i class='fas fa-cog'></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                <tr class='text-center'>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class='text-left'>{{ $row->uid_card }}</td>
                                    <td class='text-left'>{{ $row->nama_kelas }}</td>
                                    <td class='text-left'>{{ $row->nim }}</td>
                                    <td class='text-left'>{{ $row->nama_mahasiswa }}</td>
                                    <td class='text-left'>{{ $row->jenis_kelamin }}</td>
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
                                    <td colspan='7' class='text-center'>No data available in the table</td>
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
        <button wire:click.prevent='isEditingMode(false)' class='btn-modal' data-toggle='modal' data-backdrop='static'
            data-keyboard='false' data-target='#formDataModal'>
            <i class='far fa-plus'></i>
        </button>
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
                            <label for='uid_card'>RFID Card</label>
                            <input type='text' wire:model='uid_card' id='uid_card' class='form-control' readonly>
                            @error('uid_card') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='id_kelas'>Kelas</label>
                            <div wire:ignore>
                                <select wire:model='id_kelas' id='id_kelas' class='form-control select2'>
                                    @foreach ($kelases as $kelas)
                                    <option value='{{ $kelas->id }}'>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_kelas') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='nim'>NIM</label>
                            <input type='text' wire:model='nim' id='nim' class='form-control'>
                            @error('nim') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='nama_mahasiswa'>Nama Mahasiswa</label>
                            <input type='text' wire:model='nama_mahasiswa' id='nama_mahasiswa' class='form-control'>
                            @error('nama_mahasiswa') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class='form-group'>
                            <label for='jenis_kelamin'>Jenis Kelamin</label>
                            <div wire:ignore>
                                <select wire:model='jenis_kelamin' id='jenis_kelamin' class='form-control select2'>
                                    <option value='-'>-</option>
                                    <option value='L'>Laki - Laki</option>
                                    <option value='P'>Perempuan</option>
                                </select>
                            </div>
                            @error('jenis_kelamin') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' wire:click='cancel()' class='btn btn-secondary tw-bg-gray-300'
                            data-dismiss='modal'>Close</button>
                        <button type='submit' wire:click.prevent='{{ $isEditing ? 'update()' : 'store()' }}'
                            wire:loading.attr='disabled' class='btn btn-primary tw-bg-blue-500' id="save-data">Save
                            Data</button>
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
        host = "152.42.199.74";
        port = "9001";
        client = new Paho.MQTT.Client(host, Number(port), clientID);
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        client.connect({
            onSuccess: onConnect,
            userName: 'faluk',
            password: '31750321'
        });

    }

    function onConnect() {
        client.subscribe("ABSENSI/status");
        client.subscribe("ABSENSI/REGISTER_UID");
        $('#status-iot').html('Connected')
        publishMessage("ABSENSI/MODE", "1")
    }

    function onConnectionLost(responseObject) {
        $('#status-iot').html('Disconnected')
        $('#status-iot').addClass('tw-text-red-500')

        if (responseObject.errorCode !== 0) {}
    }

    function onMessageArrived(message) {
        if (message.destinationName == "ABSENSI/REGISTER_UID") {
            let data = message.payloadString;
            console.log(data);
            @this.set('uid_card', data)
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
