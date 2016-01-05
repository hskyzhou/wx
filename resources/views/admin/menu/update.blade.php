@if($status)
	<div class="modal-header">
	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	<h4 class="modal-title">修改菜单</h4>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
			{!! csrf_field() !!}
    	<div class="box-body">
          <div class="form-group">
            	<label for="inputEmail3" class="col-sm-2 control-label">菜单名称</label>
            	<div class="col-sm-10">
              	<input type="text" class="form-control" id="inputEmail3" placeholder="菜单名称" name="name" value="{{$menu['name']}}">
            	</div>
          </div>
          <div class="form-group">
            	<label for="inputPassword3" class="col-sm-2 control-label">权限</label>
            	<div class="col-sm-10">
              {{-- <input type="password" class="form-control" id="inputPassword3" placeholder="权限" name="slug"> --}}
              	<select class="form-control select2" style="width: 100%;" name="slug">
              		@forelse($menu_permissions as $menu_permission)
              			@if($menu_permission['slug'] == $menu['slug'])
                			<option selected="selected" value="{{$menu_permission['slug']}}">{{$menu_permission['name']}}--{{$menu_permission['description']}}</option>
              			@else
                			<option value="{{$menu_permission['slug']}}">{{$menu_permission['name']}}--{{$menu_permission['description']}}</option>
              			@endif
              		@empty
              			<option></option>
              		@endforelse
              	</select>
            	</div>
          </div>
          <div class="form-group">
            	<label for="inputPassword3" class="col-sm-2 control-label">地址</label>
            	<div class="col-sm-10">
              	<input type="text" class="form-control" id="inputPassword3" placeholder="地址" name="url" value="{{$menu['url']}}">
            	</div>
          </div>
          <div class="form-group">
            	<label for="inputPassword3" class="col-sm-2 control-label">父级菜单</label>
            	<div class="col-sm-10">
              {{-- <input type="password" class="form-control" id="inputPassword3" placeholder="父级菜单" name="parent_id"> --}}
                  <select class="form-control select2" style="width: 100%;" name="parent_id">
                  	<option value="0">顶级菜单</option>
                  	@forelse($menu_menus as $menu_menu)
                  		@if($menu_menu['id'] == $menu['parent_id'])
                    		<option selected="selected" value="{{$menu_menu['id']}}">{{$menu_menu['name']}}--{{$menu_menu['description']}}</option>
                  		@else
                    		<option value="{{$menu_menu['id']}}">{{$menu_menu['name']}}--{{$menu_menu['description']}}</option>
                  		@endif
                  	@empty
                  		<option></option>
                  	@endforelse
                  </select>
            </div>
          </div>
					
					{{-- 排序 --}}
          <div class="form-group">
            	<label for="inputPassword3" class="col-sm-2 control-label">排序</label>
            	<div class="col-sm-10">
              	<input type="text" class="form-control" id="inputPassword3" placeholder="排序" name="menu_order" value="{{$menu['menu_order']}}">
            	</div>
          </div>

          <div class="form-group">
            	<label for="inputPassword3" class="col-sm-2 control-label">描述</label>
            	<div class="col-sm-10">
              	<input type="text" class="form-control" id="inputPassword3" placeholder="描述" name="description" value="{{$menu['description']}}">
            	</div>
          </div>
    	</div><!-- /.box-body -->
	  </form>
	</div>
	<div class="modal-footer">
	  	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	  	<button type="button" class="btn btn-primary" id="menu_save" data-id="{{$menu['id']}}">Save changes</button>
	</div>

	<script type="text/javascript">
		$("select").select2();
	</script>
@else
	<div>{{$msg}}</div>
@endif