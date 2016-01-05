<div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<h4 class="modal-title">新建菜单</h4>
</div>
<div class="modal-body">
	<form class="form-horizontal">
      	<div class="box-body">
            {!! csrf_field() !!}
            <div class="form-group">
              	<label for="inputEmail3" class="col-sm-2 control-label">菜单名称</label>
              	<div class="col-sm-10">
                	<input type="text" class="form-control" id="inputEmail3" placeholder="菜单名称" name="name">
              	</div>
            </div>
            <div class="form-group">
              	<label for="inputPassword3" class="col-sm-2 control-label">权限</label>
              	<div class="col-sm-10">
                {{-- <input type="password" class="form-control" id="inputPassword3" placeholder="权限" name="slug"> --}}
                	<select class="form-control select2" style="width: 100%;" name="slug">
                		@forelse($menu_permissions as $menu_permission)
                			<option value="{{$menu_permission['slug']}}">{{$menu_permission['name']}}--{{$menu_permission['description']}}</option>
                		@empty
                			<option></option>
                		@endforelse
                	</select>
              	</div>
            </div>
            <div class="form-group">
              	<label for="inputPassword3" class="col-sm-2 control-label">地址</label>
              	<div class="col-sm-10">
                	<input type="text" class="form-control" id="inputPassword3" placeholder="地址" name="url">
              	</div>
            </div>
            <div class="form-group">
              	<label for="inputPassword3" class="col-sm-2 control-label">父级菜单</label>
              	<div class="col-sm-10">
                {{-- <input type="password" class="form-control" id="inputPassword3" placeholder="父级菜单" name="parent_id"> --}}
                    <select class="form-control select2" style="width: 100%;" name="parent_id">
                    	<option value="0">顶级菜单</option>
                    	@forelse($menu_menus as $menu_menu)
                    		<option value="{{$menu_menu['id']}}">{{$menu_menu['name']}}--{{$menu_menu['description']}}</option>
                    	@empty
                    		<option></option>
                    	@endforelse
                    </select>
              </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputPassword3" placeholder="排序" name="menu_order">
                </div>
            </div>

            <div class="form-group">
              	<label for="inputPassword3" class="col-sm-2 control-label">描述</label>
              	<div class="col-sm-10">
                	<input type="text" class="form-control" id="inputPassword3" placeholder="描述" name="description">
              	</div>
            </div>
      	</div><!-- /.box-body -->
    </form>
</div>
<div class="modal-footer">
  	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
  	<button type="button" class="btn btn-primary" id="menu_insert">Save changes</button>
</div>

<script type="text/javascript">
	$('select').select2();
</script>