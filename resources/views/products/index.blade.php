@extends('welcome')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{session('status')}}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <th>Product name</th>
                                <th>Price</th>
                                <th>Price (EUR)</th>
                            </tr>
                            @forelse ($products as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->price_eur}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No products found.</td>
                                </tr>
                            @endforelse
                        </table>

                        {{$products->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
