@if($status)
	<div class="modal-header">
	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	<h4 class="modal-title">修改角色</h4>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
	      	<div class="box-body">
	            <div class="form-group">
	              	<label for="inputEmail3" class="col-sm-2 control-label">角色名称</label>
	              	<div class="col-sm-10">
	                	<input type="text" class="form-control" id="inputEmail3" placeholder="角色名称" name="name" value="{{$role['name']}}">
	              	</div>
	            </div>
	            
	            <div class="form-group">
	              	<label for="inputPassword3" class="col-sm-2 control-label">角色</label>
	              	<div class="col-sm-10">
	                	<input type="text" class="form-control" id="inputPassword3" placeholder="角色" name="slug" value="{{$role['slug']}}">
	              	</div>
	            </div>
	            <div class="form-group">
	              	<label for="inputPassword3" class="col-sm-2 control-label">描述</label>
	              	<div class="col-sm-10">
	                	<input type="text" class="form-control" id="inputPassword3" placeholder="描述" name="description" value="{{$role['description']}}">
	              	</div>
	            </div>
	            <div class="form-group">
	              	<label for="inputPassword3" class="col-sm-2 control-label">级别</label>
	              	<div class="col-sm-10">
	                	<input type="text" class="form-control" id="inputPassword3" placeholder="级别" name="level" value="{{$role['level']}}">
	              	</div>
	            </div>
	            <div class="form-group">
	              	<label for="inputPassword3" class="col-sm-2 control-label">权限</label>
	      			<input type="hidden" name="permission" value="">

	              	<div class="col-sm-10">
	                	<div id="tree_2" class="tree-demo">
	                	</div>
	              	</div>
	            </div>
	      	</div><!-- /.box-body -->
	    </form>
	</div>
	<div class="modal-footer">
	  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	  <button type="button" class="btn btn-primary" id="role_save" data-id="{{$role['id']}}">Save changes</button>
	</div>
	<script type="text/javascript">
		$('#tree_2').jstree({
		    'plugins': ["wholerow", "checkbox", "types"],
		    'core': {
		        "themes" : {
		            "responsive": false
		        },    
		        "data" : {!!$permissions!!}
		    },
		    "types" : {
		        "default" : {
		            "icon" : "fa fa-folder icon-state-warning icon-lg"
		        },
		        "file" : {
		            "icon" : "fa fa-file icon-state-warning icon-lg"
		        }
		    }
		});

		$('#tree_2').on("changed.jstree", function (e, data) {
			$("input:hidden[name='permission']").val(data.selected);
		});
	</script>
@else
	<p>{{$msg}}</p>
@endif