@if($status)
  <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">修改权限</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal">
      {!! csrf_field() !!}
      <div class="box-body">
          <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">权限名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" placeholder="权限名称" name="name" value="{{$permission['name']}}">
              </div>
          </div>
          
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">权限</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" placeholder="权限" name="slug" value="{{$permission['slug']}}">
              </div>
          </div>

          <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">描述</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" placeholder="描述" name="description" value="{{$permission['description']}}">
              </div>
          </div>

          <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">模型</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" placeholder="模型" name="model" value="{{$permission['model']}}">
              </div>
          </div>
      </div><!-- /.box-body -->
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="permission_save" data-id="{{$permission['id']}}">Save changes</button>
  </div>
@else
  <div>{{$msg}}</div>
@endif