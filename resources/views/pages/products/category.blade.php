@extends('layouts.app')

@section('title', ucfirst($category))

@section('content')
<h1 class="text-2xl mb-6">Category: {{ ucfirst($category) }}</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @foreach ($products as $product)
        <x-product-card :product="$product"/>
    @endforeach
</div>

{{ $products->links() }}
@endsection
