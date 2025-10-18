{{-- resources/views/pages/checkout-fail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1 class="text-danger mb-4">Thanh toán thất bại 😢</h1>
    <p>Giao dịch của bạn không thành công. Vui lòng thử lại hoặc chọn phương thức khác.</p>
    <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-3">Quay lại trang thanh toán</a>
</div>
@endsection
