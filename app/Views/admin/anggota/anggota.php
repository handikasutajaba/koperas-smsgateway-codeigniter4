<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <a href="<?= base_url('admin/addanggota');?>" class="btn btn-success delete"><i class="ti-plus"></i> Tambah Anggota</a>
        <p></p>
        <table id="anggota" class="table table-striped table-bordered dataTable display nowrap" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th></th>
                  <th>Photo</th>
                  <th>Nomor Identitas</th>
                  <th>Nama Lengkap</th>
                  <th>Jenis Kelamin</th>
                  <th>Alamat Lengkap</th>
                  <th>Tanggal Masuk</th>
                  <th>Phone</th>
                  <th>Status</th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#anggota').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
        "url": "<?= base_url('admin/getanggota') ?>",
        "dataType": "json",
        "type": "POST",
        "data":{'<?= csrf_token(); ?>' : '<?= csrf_hash() ?>' }
      },
      "columns": [
            { "data": "no",
              "searchable": false,
              "orderable":false,
              "width": "10%"
            },
            { "data": "no",
              "searchable": false,
              "orderable":false,
              "width": "30%"
            },
            { "data": "photo",
              "render": function (data, type, row) {
                if (row.photo == '') {
                  return '<img src="<?= base_url(); ?>/uploads/anggota/anggota.png" width="50px" />';
                }else{
                  return '<img src="<?= base_url(); ?>/uploads/anggota/'+row.photo+'" width="50px" />';
                }
              }
            },
            { "data": "no_identitas", "width": "30%"},
            { "data": "nm_anggota", "width": "30%"},
            { "data": "jk", "width": "25%"},
            { "data": "alamat", "width": "40%"},
            { "data": "tgl_masuk", "width": "15%"},
            { "data": "no_telp",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.no_telp != ''){
                  var length = 8;
                  var trimmedString = row.no_telp.length > length ? 
                                      row.no_telp.substring(0, length) + "-xxxx" : 
                                      row.no_telp;
                  return trimmedString;
                }else{
                  return 'Tidak Ada';
                }
              }
            },
            { "data": "status_anggota",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.status_anggota == 1){
                  return '<span class="badge badge-info m-l-5">Active</span>';
                }else{
                  return '<span class="badge badge-danger m-l-5">Non Aktive</span>';
                }
              }
            },
           ],
            columnDefs: [
            {  targets: 1,
              "align":"center",
               render: function (data, type, row, meta) {
                  return '<button class="btn btn-danger delete" id=id-' + meta.row + '/><i class="ti-trash"></i> Delete</button> <button class="btn btn-info edit" id=id-' + meta.row + '/><i class="ti-pencil-alt"></i> Edit</button>';
               }

            }
          ]
      });
    $('#anggota tbody').on('click', '.delete', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#anggota').DataTable().row( id ).data();
      Swal.fire({
        title: 'Apakah yakin?',
        text: "Apakah anda ingin menghapus data user "+data.nm_anggota+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Tidak',
        confirmButtonText: 'Ya, Hapus Sekarang',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url : "<?= base_url('admin/deletedanggota')?>",
            dataType:'json',
            data: {<?= csrf_token() ?>: '<?= csrf_hash() ?>' , id:data.idanggota},
            success: function(msg){
              Swal.fire(
                'Terhapus!',
                'Data yang anda pilih telah dihapus.',
                'success'
              )
              table.ajax.reload( null, false );
            },
            error: function(){
              Swal.fire(
                'Gagal',
                'Data yang anda pilih gagal terhapus.',
                'error'
              )
            }
          });
        }
      })
    });
    $('#anggota tbody').on('click', '.edit', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#anggota').DataTable().row( id ).data();
      window.location.href="<?= base_url('admin/editanggota')?>/"+data.idanggota;
    });
  });
</script>
