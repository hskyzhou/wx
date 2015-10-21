@extends('admin.layout')

@section('customer_css')
	<link rel="stylesheet" href="{{asset('plugins/select2/select2.css')}}">
	<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('plugins/jstree/dist/themes/default/style.min.css')}}"/>
	@parent
@endsection

@section('content')
	<div id="csrf">
	  	<input type="hidden" name="csrftoken" value="{{csrf_token()}}">
	</div>

  	<div class="modal fade" id="contentmodal">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        
	      	</div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
  	</div><!-- /.modal -->

	<div class="box">
	    <div class="box-header">
	      	<h3 class="box-title">订单修改</h3>
	    </div><!-- /.box-header -->
	    <div class="box-body">
	    	@if($is_add)
	    		<a data-toggle="modal" data-target="#contentmodal" href="/role/add" class="btn btn-success">添加角色</a>
	    	@endif
	      	<table id="role" class="table table-bordered table-striped">
		        <thead>
		          	<tr>
		            	<th>角色名称</th>
		            	<th>角色</th>
		            	<th>描述</th>
		            	<th>级别</th>
		            	<th>权限</th>
		            	<th>创建时间</th>
		            	<th>修改时间</th>
		            	<th>操作</th>
		          	</tr>
		        </thead>
		        <tbody>
		          
		        </tbody>
	      	</table>
	    </div><!-- /.box-body -->
	</div><!-- /.box -->
@endsection

@section('js')
	@parent
	<!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
	<!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
	
	<script src="{{asset('layer/layer.js')}}"></script>
	<script src="{{asset('custom/role.js')}}"></script>


	<script src="{{asset('plugins/jstree/dist/jstree.min.js')}}"></script>
@endsection