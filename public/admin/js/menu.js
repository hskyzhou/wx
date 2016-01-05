$(document).ready(function(){
	/* 子菜单内容 */
	function fnFormatDetails ( oTable, nTr ){
	    var aData = oTable.fnGetData( nTr );

	    var son = aData.son;

	    var sOut = ''; //输出内容
	    sOut = "<table class='table table-bordered table-striped'>";
	    sOut += "<tr>";

    	sOut += "<th>菜单名称</th>";
    	sOut += "<th>权限</th>";
    	sOut += "<th>地址</th>";
    	sOut += "<th>父级菜单</th>";
    	sOut += "<th>描述</th>";
    	sOut += "<th>创建时间</th>";
    	sOut += "<th>修改时间</th>";
    	sOut += "<th>操作</th>";
    	sOut += "</tr>";
	    for(var i in son){
	    	sOut += "<tr'>";
	    	sOut += "<td>"+son[i].name+"</td>";
	    	sOut += "<td>"+son[i].slug+"</td>";
	    	sOut += "<td>"+son[i].url+"</td>";
	    	sOut += "<td>"+son[i].parent_id+"</td>";
	    	sOut += "<td>"+son[i].description+"</td>";
	    	sOut += "<td>"+son[i].created_at+"</td>";
	    	sOut += "<td>"+son[i].updated_at+"</td>";
	    	sOut += "<td><a class='menu_update' data-toggle='modal' data-target='#contentmodal' href='/menu/update?id="+son[i].id+"'>修改</a> | <a class='menu_delete' href='' data-id="+son[i].id+">删除</a></td>";
	    	sOut += "</tr>";
	    }
	    sOut += "</table>";
	     
	    return sOut;
	}
	if(jQuery().DataTable()){
		/*datatable 显示*/
		var oTable = $('#menu').dataTable({
			"bProcessing": true,
			"bServerSide": true,
	        "sAjaxSource": "/menu/menulist",
	        "sDom": 'flrtip',

	        "aoColumns": [
	            {
	            	"bSortable": false,
	            	"mData": "id", 
	            	"mRender": function ( data, type, full ) {
	            		return '<img src="/admin/images/details_open.png">';
	            	}
	            },
	            { "mData": "name" },
	            { "mData": "slug" },
	            { "mData": "url" },
	            { "mData": "parent_id" },
	            { "mData": "description" },
	            { "mData": "menu_order" },
	            { "mData": "created_at" },
	            { "mData": "updated_at" },
	            { "mData" : "button"},
	        ],
	        "aLengthMenu": [
	        	[5, 10, 15, 20, -1],
	        	[5, 10, 15, 20, "All"]
	        ],

	        "pageLength": 10,
	    });
	}

	/*显示子菜单*/
    $(document).on('click', '#menu tbody td img', function () {
        var nTr = $(this).parents('tr')[0];
        
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            this.src = "/admin/images/details_open.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            this.src = "/admin/images/details_close.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );

    /*保存菜单修改*/
    $(document).on('click', '#menu_save', function(){
    	var $update_modal = $("#contentmodal");

		var name = $update_modal.find('[name="name"]').val();
		var description = $update_modal.find('[name="description"]').val();
		var url = $update_modal.find('[name="url"]').val();
		
		var slug = $update_modal.find('[name="slug"] option:selected').val();
		var parent_id = $update_modal.find('[name="parent_id"] option:selected').val();
		var menu_order = $update_modal.find('[name="menu_order"]').val();

		var csrftoken = $("input:hidden[name='_token']").val();

		var id = $(this).data('id');

		$.ajax({
			url: '/menu/update',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				id : id,
				name : name,
				description : description,
				url : url,
				slug : slug,
				parent_id : parent_id,
				menu_order : menu_order
			},
		})
		.done(function(data) {
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

    /*保存菜单新建*/
    $(document).on('click', '#menu_insert', function(){
    	var $update_modal = $("#contentmodal");

		var name = $update_modal.find('[name="name"]').val();
		var description = $update_modal.find('[name="description"]').val();
		var url = $update_modal.find('[name="url"]').val();
		
		var slug = $update_modal.find('[name="slug"] option:selected').val();
		var parent_id = $update_modal.find('[name="parent_id"] option:selected').val();
		var menu_order = $update_modal.find('[name="menu_order"]').val();

		var csrftoken = $("input:hidden[name='_token']").val();

		$.ajax({
			url: '/menu/add',
			type: 'POST',
			dataType: 'json',
			headers : {
				"X-CSRF-TOKEN":csrftoken
			},
			data: {
				name : name,
				description : description,
				url : url,
				slug : slug,
				parent_id : parent_id,
				menu_order : menu_order
			},
		})
		.done(function(data) {
			if(data.status){
				oTable.fnDraw();
				layer.msg(data.msg);
			}else{
				layer.msg(data.msg);
			}
		})
		.fail(function() {
			layer.msg('添加失败');
		})
		.always(function() {
			$('.modal').modal('hide');
		});
    });

	/*菜单删除*/
	$(document).on('click', '.menu_delete', function(){
		var $this = $(this);
		var id = $this.data('id');

		layer.confirm("您确定要删除吗？", function(){
			$.ajax({
				url: '/menu/delete',
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
    $("#menu_length").find('select').css('width', '100').select2();

 	/*modal事件监听*/
 	$(".modal").on("hidden.bs.modal", function() {
 	    $(this).removeData("bs.modal");
 	});
});