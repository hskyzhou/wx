@if($status)
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <h4 class="modal-title">修改用户</h4>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
			{!! csrf_field() !!}
	    <div class="box-body">
	    	@if($is_update || $is_owner)
    			{{-- 用户名称 --}}
    			<div class="form-group">
  			  	<label for="inputEmail3" class="col-sm-2 control-label">用户名称</label>
  			  	<div class="col-sm-10">
  			    	<input type="text" class="form-control" id="inputEmail3" placeholder="用户名称" name="name" value="{{$user['name']}}">
  			  	</div>
    			</div>
					
					{{-- 邮箱 --}}
    			<div class="form-group">
  			  	<label for="inputEmail3" class="col-sm-2 control-label">用户邮箱</label>
  			  	<div class="col-sm-10">
  			    	<input type="text" class="form-control" id="inputEmail3" placeholder="用户邮箱" name="email" value="{{$user['email']}}">
  			  	</div>
    			</div>
	      @endif

    		@if($is_owner)
    			{{-- 密码 --}}
					<div class="form-group">
				  	<label for="inputEmail3" class="col-sm-2 control-label">用户密码</label>
				  	<div class="col-sm-10">
				    	<input type="paddword" class="form-control" id="inputEmail3" placeholder="用户密码" name="password">
				  	</div>
					</div>
    		@endif

    		{{-- 用户角色 --}}
    		<div class="form-group">
  		    <label for="inputPassword3" class="col-sm-2 control-label">角色</label>
  		    <div class="col-sm-10">
  		      <select class="form-control select2" style="width: 100%;" name="role" multiple="multiple">
  		        @if(!empty($all_roles))
  		          @foreach($all_roles as $all_role)
  		          	@if(in_array($all_role['id'], $user_roles))
	  		            <option value="{{$all_role['id']}}" selected="selected">{{$all_role['name']}}</option>
  		          	@else
	  		            <option value="{{$all_role['id']}}">{{$all_role['name']}}</option>
  		          	@endif
  		          @endforeach
  		        @else
  		          <option></option>
  		        @endif
  		      </select>
  		    </div>
    		</div>

    		{{-- 用户权限 --}}
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
	  	<button type="button" class="btn btn-primary" id="user_save" data-id="{{$user['id']}}">Save changes</button>
	</div>

	<script type="text/javascript">
		$("select").select2();

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
	<div>{{$msg}}</div>
@endif