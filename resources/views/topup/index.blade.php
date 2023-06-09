@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">
        <a href="#modal-dialog" id="btn-add" class="btn btn-primary mb-3" data-route="{{ route('topup.store') }}" data-bs-toggle="modal"><i class="ion-ios-add"></i> Add Topup</a>
        <a href="#modal-detail" class="btn btn-primary mb-3" data-bs-toggle="modal"><i class="ion-ios-check"></i> Check Saldo</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Tanggal</th>
                    <th class="text-nowrap">Member</th>
                    <th class="text-nowrap">Jumlah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Topup</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="form-topup">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="rfid">Rfid</label>
                            <input type="text" name="rfid" id="rfid" class="form-control" value="">

                            @error('rfid')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" value="">

                            @error('jumlah')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Check topup</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>

                <div class="modal-body body-detail">
                    <div class="form-group mb-3">
                        <label for="rfid_check">Rfid</label>
                        <input type="text" name="rfid_check" id="rfid_check" class="form-control" value="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama">Nama Member</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="saldo">Saldo</label>
                        <input type="text" name="saldo" id="saldo" class="form-control" value="" disabled>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" id="btn-close-check" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <form action="" class="d-none" id="form-delete" method="post">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}plugins/select2/dist/js/select2.min.js"></script>

<script>
    $(".multiple-select2").select2({
        dropdownParent: $('#modal-dialog'),
        placeholder: "Pilih Barang",
        allowClear: true
    })

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('topup.list') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'member',
                name: 'member'
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-topup").attr('action', route)
    })

    $("#btn-close").on('click', function() {
        $("#form-topup").removeAttr('action')
        $("#rfid_check").val("")
        $("#nama").val("")
        $("#saldo").val("")
    })
    $("#btn-close-check").on('click', function() {
        $("#rfid_check").val("")
        $("#nama").val("")
        $("#saldo").val("")
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-topup").attr('action', route)
        $("#form-topup").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/topup/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let topup = response.topup;
                let permission = response.permission;

                $("#name").val(topup.name)
                $('.multiple-select2').select2({
                    dropdownParent: $('#modal-dialog'),
                    placeholder: "Pilih Barang",
                    allowClear: true,
                }).val(permission).trigger('change')
            }
        })
    })

    $("#datatable").on('click', '.btn-detail', function() {
        let id = $(this).attr('id')

        $.ajax({
            url: "/topup/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let permissions = response.permissions;
                let append = ``;

                $.each(permissions, function(i, data) {
                    append += `<span class="badge bg-orange">` + data.name + `</span> `
                })

                $(".body-detail").empty().append(append)

            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data topup?',
            text: 'Menghapus topup bersifat permanen.',
            icon: 'error',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-danger',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-delete").submit()
            } else {
                $("#form-delete").attr('action', '')
            }
        });
    })

    $('#form-topup').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#rfid").on('keypress', function(e) {
        if (e.which == 13) {
            let rfid = $(this).val();

            $.ajax({
                url: "/api/members",
                type: "GET",
                method: "GET",
                data: {
                    rfid: rfid
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == 'success') {
                        let member = response.member;
                    } else {
                        $("#rfid").val("")

                    }

                },
                error: function(response) {
                    $("#rfid").val("")
                }
            })
        }
    })

    $("#rfid_check").on('keypress', function(e) {
        if (e.which == 13) {
            let rfid = $(this).val();

            $.ajax({
                url: "/api/members",
                type: "GET",
                method: "GET",
                data: {
                    rfid: rfid
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == 'success') {
                        let member = response.member;
                        $("#rfid_check").val(member.rfid)
                        $("#nama").val(member.nama)
                        $("#saldo").val(member.saldo)
                    } else {
                        $("#rfid").val("")

                    }

                },
                error: function(response) {
                    $("#rfid").val("")
                }
            })
        }
    })
</script>
@endpush