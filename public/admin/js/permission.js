$(document).ready(function(){
	if(jQuery().DataTable()){
		/*datatable 显示*/
		var oTable = $('#permission').dataTable({
			"bProcessing": true,
			"bServerSide": true,
	        "sAjaxSource": "/permission/permissionlist",
	        "sDom": 'flrtip',

	        "aoColumns": [
	            { "mData": "name" },
	            { "mData": "slug" },
	            { "mData": "description" },
	            { "mData": "model" },
	            { "mData": "created_at" },
	            { "mData": "updated_at" },
	            { 
	            	"mData": "id",
	            	"mRender": function ( data, type, full ) {
	            		var returnStr = '';
	            		if(full.update){
	            			returnStr += "<a data-toggle='modal' data-target='#contentmodal' href='/permission/update?id="+data+"'>修改</a> | ";
	            		}

	            		if(full.delete){
		            		returnStr += "<a class='permission_delete' href='/permission/delete' data-id="+data+">删除</a>";
	            		}

	            		return returnStr;
	            	}
	           	},
	        ],
	        "aLengthMenu": [
	        	[5, 10, 15, 20, 50],
	        	[5, 10, 15, 20, 50]
	        ],

	        "pageLength": 10,
	    });
	}

    /*保存权限修改*/
    $(document).on('click', '#permission_save', function(){
    	var $update_modal = $("#contentmodal");

		var name = $update_modal.find('[name="name"]').val();
		var description = $update_modal.find('[name="description"]').val();
		var model = $update_modal.find('[name="model"]').val();
		var slug = $update_modal.find('[name="slug"]').val();
		
		var csrftoken = $("#csrf").find('input[name="csrftoken"]').val();

		var id = $(this).data('id');

		$.ajax({
			url: '/permission/update',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				id : id,
				name : name,
				description : description,
				model : model,
				slug : slug,
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

    /*保存权限新建*/
    $(document).on('click', "#permission_insert", function(){
    	var $add_modal = $("#contentmodal");

		var name = $add_modal.find('[name="name"]').val();
		var description = $add_modal.find('[name="description"]').val();
		var model = $add_modal.find('[name="model"]').val();
		var slug = $add_modal.find('[name="slug"]').val();
		
		var csrftoken = $("#csrf").find('input[name="csrftoken"]').val();

		$.ajax({
			url: '/permission/add',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				name : name,
				description : description,
				model : model,
				slug : slug,
			},
		})
		.done(function(data) {
			$("#csrf").find('input[name="csrftoken"]').val(data.csrftoken);

			if(data.status){
				oTable.fnDraw();
				var name = $add_modal.find('[name="name"]').val('');
				var description = $add_modal.find('[name="description"]').val('');
				var model = $add_modal.find('[name="model"]').val('');
				var slug = $add_modal.find('[name="slug"]').val('');
				layer.msg(data.msg);
			}else{
				layer.msg(data.msg);
			}
		})
		.fail(function(data) {
			layer.msg(data.msg);
		})
		.always(function() {
			$('.modal').modal('hide');
		});
    });
    /*权限删除*/
    $(document).on('click', '.permission_delete', function(){
    	var $this = $(this);
    	var id = $this.data('id');

    	$.ajax({
    		url: '/permission/delete',
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
    $("#permission_length").find('select').css('width', '100').select2();

    /*modal事件监听*/
    $(".modal").on("hidden.bs.modal", function() {
        $(this).removeData("bs.modal");
    });
});
