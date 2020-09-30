<!-- partial -->
<div class="content-wrapper">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <?php if ($this->session->flashdata('demo') or $this->session->flashdata('hapus')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('demo'); ?>
                        <?php echo $this->session->flashdata('hapus'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('ubah') or $this->session->flashdata('tambah')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('ubah'); ?>
                        <?php echo $this->session->flashdata('tambah'); ?>
                    </div>
                <?php endif; ?>
                <h4 class="card-title">Permissions</h4>
                <div class="tab-minimal tab-minimal-success">

				
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/easyui/css/easyui.css'); ?>">
        <!--<link href="<?php echo base_url('assets/css/redmond/jquery-ui-1.9.2.custom.css'); ?>" rel="stylesheet" media="screen">
        <link href="<?php echo base_url('assets/css/redmond/ui.layout.css'); ?>" rel="stylesheet" media="screen">-->

		<link href="<?php echo base_url('asset/easyui/css/style.css'); ?>" rel="stylesheet" media="screen">
		<link href="<?php echo base_url('asset/easyui/css/icon.css'); ?>" rel="stylesheet" media="screen">	
	
	<script type="text/javascript" src="<?php echo base_url('asset/easyui/js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('asset/easyui/js/jquery.easyui.min.js'); ?>"></script>
	
        
        <!-- #Body -->
                    <!-- ## CONTENT ##-->

<script>
function OnSelectGrid(index, record){
	$('#datagrid-permission').datagrid({
				url: '<?php echo base_url('setting/grid/'); ?>/' + index
	});
}

function check_click(menu_id, name, group_id) {

	if (group_id == '000000' || group_id == '') {
		$.messager.show({
			title:'INFO',
			msg:"Select one of the Group",
			timeout:5000,
			showType:'slide'
		});
	} else {
	
		var value = document.getElementById(name + menu_id);
		if ( value.checked ) { val = 1; } else { val = 0; }

		$.ajax({
			type:'POST',
			url:'<?php echo base_url(); ?>setting/update_id/' + menu_id + "-" + name + "-" + val + "-" + group_id, // this external php file isn't connecting to mysql db
		});
	
	}

}

</script>
<!-- Data Grid -->
<div id="toolbar">
<div style="padding:10px;">
	Group : 
	<select class="easyui-combogrid" id="group_id" style="width:250px" data-options="
			panelWidth: 300,
			idField: 'group_id',
			textField: 'group_name',
			editable: false,
			url: '<?php echo base_url('setting/combogrid_group/'); ?>',
			method: 'get',
			columns: [[
				{field:'group_id',title:'ID',width:50},
				{field:'group_name',title:'Group name',width:80,align:'right'}
			]],
			fitColumns: true,
			onChange: OnSelectGrid
		">
	</select>
	
</div>
</div>
<table id="datagrid-permission" style="height: 450px" class="easyui-datagrid" url="<?php echo base_url('setting/grid'); ?>/000000" fit="false" toolbar="#toolbar" pagination="false" rownumbers="true" fitColumns="true" singleSelect="true" collapsible="true"
data-options="
	onSortColumn: function(){
        //var opts = $(this).datagrid('options');
            // opts.sorting = false;
			// test = $.parseJSON(opts);
			//alert(opts.sortName);
			// opts.sortable  = false;
			//$('#' + opts.sortName + '44').prop('checked', true);
    },
	onDblClickCell:function(index,field,value){
		var row = $(this).datagrid('getSelected');
		var group = $('#group_id').combobox('getValue');
		
		var checked = $('#view' + row.menu_id).is(':checked');
		
		if(row.view.length > 0) {
			if (checked)
				$('#view' + row.menu_id).prop('checked', false);
			else
				$('#view' + row.menu_id).prop('checked', true);
		
			check_click(row.menu_id, 'view', group);
		}
		
		if(row.created.length > 0) {
			if (checked)
				$('#created' + row.menu_id).prop('checked', false);
			else
				$('#created' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'created', group);
		}
		
		if(row.updated.length > 0) {
			if (checked)
				$('#updated' + row.menu_id).prop('checked', false);
			else
				$('#updated' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'updated', group);
		}

		if(row.cancelled.length > 0) {
			if (checked)
				$('#cancelled' + row.menu_id).prop('checked', false);
			else
				$('#cancelled' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'cancelled', group);
		}
		
		if(row.deleted.length > 0) {
			if (checked)
				$('#deleted' + row.menu_id).prop('checked', false);
			else
				$('#deleted' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'deleted', group);
		}
		
		if(row.print.length > 0) {
			if (checked)
				$('#print' + row.menu_id).prop('checked', false);
			else
				$('#print' + row.menu_id).prop('checked', true);
				
			check_click(row.menu_id, 'print', group);
		}
		
		if(row.downloaded.length > 0) {
			if (checked)
				$('#downloaded' + row.menu_id).prop('checked', false);
			else
				$('#downloaded' + row.menu_id).prop('checked', true);
				
			check_click(row.menu_id, 'downloaded', group);
		}
		
		if(row.uploaded.length > 0) {
			if (checked)
				$('#uploaded' + row.menu_id).prop('checked', false);
			else
				$('#uploaded' + row.menu_id).prop('checked', true);
				
			check_click(row.menu_id, 'uploaded', group);
		}
		
		if(row.closed.length > 0) {
			if (checked)
				$('#closed' + row.menu_id).prop('checked', false);
			else
				$('#closed' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'closed', group);
		}
		
		if(row.verified.length > 0) {
			if (checked)
				$('#verified' + row.menu_id).prop('checked', false);
			else
				$('#verified' + row.menu_id).prop('checked', true);
			
			check_click(row.menu_id, 'verified', group);
		}
		
	},
	onSelect: function(index, row) {
		// $(this).datagrid('unselectRow', index);
	}
	">
			<thead>
		<tr>
				<th field="menu_id" width="30" sortable="false">ID</th>
				<!--<th field="url" width="80" sortable="false">URL</th>-->

			<th field="menu" width="150" sortable="false">Menu</th>
			<th field="view" width="30" sortable="false" align="center">View</th>
			<th field="created" width="30" sortable="false" align="center" >Create</th>
			<th field="updated" width="30" sortable="false" align="center">Update</th>
			<th field="cancelled" width="30" sortable="false" align="center">Cancel</th>
			<th field="deleted" width="30" sortable="false" align="center">Delete</th>
			<th field="print" width="30" sortable="false" align="center">Print</th>
			<th field="uploaded" width="30" sortable="false" align="center">Upload</th>
			<th field="closed" width="30" sortable="false" align="center">Close</th>
			<th field="verified" width="30" sortable="false" align="center">Verify</th>
		</tr>
	</thead>
</table>

                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= base_url(); ?>asset/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/intlTelInput-jquery.min.js"></script>
<!-- endinject -->

<!-- Plugin js for this page-->
<script src="<?= base_url(); ?>asset/node_modules/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/chart.js/dist/Chart.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/raphael/raphael.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/morris.js/morris.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/datatables.net/js/jquery.dataTables.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/dropify/dist/js/dropify.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/pwstabs/assets/jquery.pwstabs.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/icheck/icheck.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/select2/dist/js/select2.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/dragula/dist/dragula.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/summernote/dist/summernote-bs4.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/tinymce/tinymce.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/quill/dist/quill.min.js"></script>
<script src="<?= base_url(); ?>asset/node_modules/simplemde/dist/simplemde.min.js"></script>
<!-- End plugin js for this page-->

<!-- inject:js -->
<script src="<?= base_url(); ?>asset/js/off-canvas.js"></script>
<script src="<?= base_url(); ?>asset/js/hoverable-collapse.js"></script>
<script src="<?= base_url(); ?>asset/js/misc.js"></script>
<script src="<?= base_url(); ?>asset/js/settings.js"></script>
<script src="<?= base_url(); ?>asset/js/todolist.js"></script>

<!-- endinject -->

<!-- Custom js for this page-->
<script src="<?= base_url(); ?>asset/js/dashboard.js"></script>
<script src="<?= base_url(); ?>asset/js/data-table.js"></script>
<script src="<?= base_url(); ?>asset/js/dropify.js"></script>
<script src="<?= base_url(); ?>asset/js/tabs.js"></script>
<script src="<?= base_url(); ?>asset/js/file-upload.js"></script>
<script src="<?= base_url(); ?>asset/js/iCheck.js"></script>
<script src="<?= base_url(); ?>asset/js/typeahead.js"></script>
<script src="<?= base_url(); ?>asset/js/select2.js"></script>
<script src="<?= base_url(); ?>asset/js/dragula.js"></script>
<script src="<?= base_url(); ?>asset/js/editorDemo.js"></script>
<script src="<?= base_url(); ?>asset/js/duit.js"></script>
