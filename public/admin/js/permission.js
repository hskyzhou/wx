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
	            { "mData": "button"},
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

		var csrftoken = $update_modal.find('[name="_token"]').val();
		
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
			if(data.status){
				oTable.fnDraw();
				layer.msg(data.msg);
			}else{
				layer.msg(data.msg);
			}
			$('.modal').modal('hide');
		})
		.fail(function(response) {
			if(response.status == 422){
				var data = response.responseJSON;
				var layerStr = "";
				for(var i in data){
					layerStr += data[i];
				}
				layer.msg(layerStr);
			}else if(response.status == 401){
				layer.msg("请重新登录");
			}else{
				layer.msg("系统错误，请刷新重试或者记录已存在");
			}
		});
    });

    /*保存权限新建*/
    $(document).on('click', "#permission_insert", function(){
    	var $add_modal = $("#contentmodal");

		var name = $add_modal.find('[name="name"]').val();
		var description = $add_modal.find('[name="description"]').val();
		var model = $add_modal.find('[name="model"]').val();
		var slug = $add_modal.find('[name="slug"]').val();
		var csrftoken = $add_modal.find('[name="_token"]').val();

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

			$('.modal').modal('hide');
		})
		.fail(function(response) {
			if(response.status == 422){
				var data = response.responseJSON;
				var layerStr = "";
				for(var i in data){
					layerStr += data[i];
				}
				layer.msg(layerStr);
			}else if(response.status == 401){
				layer.msg("请重新登录");
			}else{
				layer.msg("系统错误，请刷新重试或者记录已存在");
			}
		});
    });
    /*权限删除*/
    $(document).on('click', '.permission_delete', function(){
    	var $this = $(this);
    	var id = $this.data('id');

    	layer.confirm("您确定要删除吗？", function(){
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
