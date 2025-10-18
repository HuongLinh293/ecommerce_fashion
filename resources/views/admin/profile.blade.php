@extends('layouts.admin')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-4">Thông tin tài khoản</h1>
    <p>Tên: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
</div>
@endsection
