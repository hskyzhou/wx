$(document).ready(function(){

	if(jQuery().DataTable()){
		/*datatable 显示*/
		var oTable = $('#user').dataTable({
			"bProcessing": true,
			"bServerSide": true,
	        "sAjaxSource": "/user/userlist",
	        "sDom": 'flrtip',

	        "aoColumns": [
	            { "mData": "name" },
	            { "mData": "email" },
	            { 
	            	"mData": "id",
	            	"mRender" : function(data, type, full){
	            		var returnStr = '';
	            		returnStr += '<ul>';
	            		var roles = full.roles;
	            		if(roles.length > 0){
	            			for(var i in roles){
	            				returnStr += '<li>'+roles[i].name+'</li>';
	            			}
	            		}
	            		returnStr += '</ul>';
	            		return returnStr;
	            	}

	            },
	            { 
	            	"mData": "id",
	            	"mRender" : function(data, type, full){
	            		return "<a data-toggle='modal' data-target='#contentmodal' href='/user/permission?id="+data+"'>查看权限</a>";
	            	}
	            },
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

    /*保存角色修改*/
    $(document).on('click', '#user_save', function(){
    	var $update_modal = $("#contentmodal");

		var name = $update_modal.find('[name="name"]').val();
		var email = $update_modal.find('[name="email"]').val();
		var password = $update_modal.find('[name="password"]').val();
		var role = $update_modal.find('[name="role"]').val();
		var permission = $update_modal.find('[name="permission"]').val();
		var csrftoken = $update_modal.find('[name="_token"]').val();

		var id = $(this).data('id');

		$.ajax({
			url: '/user/update',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				id : id,
				name : name,
				email : email,
				password : password,
				role : role,
				permission : permission
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

    /*保存角色新建*/
    $(document).on('click', '#user_insert', function(){
    	var $add_modal = $("#contentmodal");

		var name = $add_modal.find('[name="name"]').val();
		var email = $add_modal.find('[name="email"]').val();
		var password = $add_modal.find('[name="password"]').val();
		var role = $add_modal.find('[name="role"]').val();
		var permission = $add_modal.find('[name="permission"]').val();
		var csrftoken = $add_modal.find('[name="_token"]').val();
		
		$.ajax({
			url: '/user/add',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				name : name,
				email : email,
				password : password,
				role : role,
				permission : permission
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

    /*角色删除*/
    $(document).on('click', '.user_delete', function(){
    	var $this = $(this);
    	var id = $this.data('id');

    	layer.confirm("您确定要删除吗？", function(){
    		$.ajax({
    			url: '/user/delete',
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
    $("#user_length").find('select').css('width', '100').select2();

    /*modal事件监听*/
    $(".modal").on("hidden.bs.modal", function() {
        $(this).removeData("bs.modal");
    });
});
