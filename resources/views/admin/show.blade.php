@extends('admin.layout')

@section('customer_css')
	<link rel="stylesheet" href="{{asset('admin/vendor/select2/select2.css')}}">
	<link rel="stylesheet" href="{{asset('admin/vendor/datatables/dataTables.bootstrap.css')}}">
	@parent
@endsection

@section('content')
	welcome to admin page
@endsection

@section('js')
	@parent
@endsection