@extends('admin.layout')

@section('customer_css')
	<link rel="stylesheet" href="{{asset('plugins/select2/select2.css')}}">
	<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
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
	    	@if($is_add)
		    	<button data-target="#contentmodal" data-toggle="modal" href="/menu/add" type="button" class="btn btn-success">添加菜单</button>
	    	@endif
	      	<table id="menu" class="table table-bordered table-striped">
		        <thead>
		          	<tr>
		            	<th></th>
		            	<th>菜单名称</th>
		            	<th>权限</th>
		            	<th>地址</th>
		            	<th>父级菜单</th>
		            	<th>描述</th>
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
	<script src="{{asset('custom/menu.js')}}"></script>
@endsection