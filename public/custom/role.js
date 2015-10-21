$(document).ready(function(){
	var prefix_role_row_id = 'role_row_';

	if(jQuery().DataTable()){
		/*datatable 显示*/
		var oTable = $('#role').dataTable({
			"bProcessing": true,
			"bServerSide": true,
	        "sAjaxSource": "/role/rolelist",
	        "sDom": 'flrtip',

	        "aoColumns": [
	            { "mData": "name" },
	            { "mData": "slug" },
	            { "mData": "description" },
	            { "mData": "level" },
	            { 
	            	"mData": "id",
	            	"mRender" : function(data, type, full){
	            		return "<a data-toggle='modal' data-target='#contentmodal' href='/role/permission?id="+data+"'>查看权限</a>";
	            	}
	            },
	            { "mData": "created_at" },
	            { "mData": "updated_at" },
	            { 
	            	"mData": "id",
	            	"mRender": function ( data, type, full ) {
	            		var returnStr = '';
	            		if(full.update){
	            			returnStr += "<a data-toggle='modal' data-target='#contentmodal' href='/role/update?id="+data+"'>修改</a> | ";
	            		}

	            		if(full.delete){
	            			returnStr += "<a class='role_delete' href='/role/delete' data-id="+data+">删除</a>";
	            		}

	            		return returnStr;
	            	}
	           	},
	        ],
	        "aLengthMenu": [
	        	[5, 10, 15, 20, -1],
	        	[5, 10, 15, 20, "All"]
	        ],

	        "pageLength": 10,
	    });
	}

    /*保存角色修改*/
    $(document).on('click', '#role_save', function(){
    	var $update_modal = $("#contentmodal");

		var name = $update_modal.find('[name="name"]').val();
		var description = $update_modal.find('[name="description"]').val();
		var level = $update_modal.find('[name="level"]').val();
		var slug = $update_modal.find('[name="slug"]').val();
		var permission = $update_modal.find('[name="permission"]').val();
		
		var csrftoken = $("#csrf").find('input[name="csrftoken"]').val();

		var id = $(this).data('id');

		$.ajax({
			url: '/role/update',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				id : id,
				name : name,
				description : description,
				level : level,
				slug : slug,
				permission : permission
			},
		})
		.done(function(data) {
			$("#csrf").find('input[name="csrftoken"]').val(data.csrftoken);
			if(data.status){
				oTable.fnDraw();
				layer.msg(data.msg);
			}else{
				layer.msg(data.msg);
			}
		})
		.fail(function() {
			layer.msg('修改失败');
		})
		.always(function() {
			$('.modal').modal('hide');
		});
    });

    /*保存角色新建*/
    $(document).on('click', '#role_insert', function(){
    	var $add_modal = $("#contentmodal");

		var name = $add_modal.find('[name="name"]').val();
		var description = $add_modal.find('[name="description"]').val();
		var level = $add_modal.find('[name="level"]').val();
		var slug = $add_modal.find('[name="slug"]').val();
		var permission = $add_modal.find('[name="permission"]').val();
		
		var csrftoken = $("#csrf").find('input[name="csrftoken"]').val();

		$.ajax({
			url: '/role/add',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				name : name,
				description : description,
				level : level,
				slug : slug,
				permission : permission
			},
		})
		.done(function(data) {
			$("#csrf").find('input[name="csrftoken"]').val(data.csrftoken);

			if(data.status){
				oTable.fnDraw();
				var name = $add_modal.find('[name="name"]').val('');
				var description = $add_modal.find('[name="description"]').val('');
				var level = $add_modal.find('[name="level"]').val('');
				var slug = $add_modal.find('[name="slug"]').val('');
				layer.msg(data.msg);
			}else{
				layer.msg(data.msg);
			}
		})
		.fail(function() {
			layer.msg('修改失败');
		})
		.always(function() {
			$('.modal').modal('hide');
		});
    });

    /*角色删除*/
    $(document).on('click', '.role_delete', function(){
    	var $this = $(this);
    	var id = $this.data('id');

    	$.ajax({
    		url: '/role/delete',
    		type: 'GET',
    		dataType: 'json',
    		data: {id: id},
    	})
    	.done(function(data) {
    		if(data.status){
    			layer.msg(data.msg);
    			oTable.fnDraw();
    		}else{
    			/*没有数据*/
    			layer.msg(data.msg);
    		}
    	})
    	.fail(function() {
    		layer.msg('获取失败,请稍后重试');
    	});
    	
    	return false;
    });

	/*使用select2*/
    $("#role_length").find('select').css('width', '100').select2();

    /*modal事件监听*/
    $(".modal").on("hidden.bs.modal", function() {
        $(this).removeData("bs.modal");
    });
});
