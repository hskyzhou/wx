@extends('admin.layout')

@section('customer_css')
	<link rel="stylesheet" href="{{asset('admin/vendor/select2/select2.css')}}">
	<link rel="stylesheet" href="{{asset('admin/vendor/datatables/dataTables.bootstrap.css')}}">
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
	      	<h3 class="box-title">菜单列表</h3>
	    </div><!-- /.box-header -->
	    <div class="box-body">
	    	{!! $add_button !!}
      	<table id="menu" class="table table-bordered table-striped">
	        <thead>
	          	<tr>
	            	<th></th>
	            	<th>菜单名称</th>
	            	<th>权限</th>
	            	<th>地址</th>
	            	<th>父级菜单</th>
	            	<th>描述</th>
	            	<th>排序</th>
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
    <script src="{{asset('admin/vendor/select2/select2.full.min.js')}}"></script>
	<!-- DataTables -->
    <script src="{{asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>
	
	<script src="{{asset('admin/vendor/layer/layer.js')}}"></script>
	<script src="{{asset('admin/js/menu.js')}}"></script>
@endsection