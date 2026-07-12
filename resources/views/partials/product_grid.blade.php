@foreach($products as $product)
    @include('partials.product_card', ['product' => $product])
@endforeach
