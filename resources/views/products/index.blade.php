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
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('products.create') }}" class="btn btn-primary">Add new product</a>
                            <br><br>
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
                                    <td>
                                        @if(auth()->user()->is_admin)

                                            <a href="{{ route('products.edit',$item) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('products.destroy',$item) }}" method="post"
                                                  onsubmit="return confirm('Are you sure?')">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                            </form>

                                        @endif
                                            <a href="{{ route('products.cart',$item->id) }}" class="btn btn-sm btn-primary" >Add to cart</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No products found.</td>
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
