@extends('welcome')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <form action="{{ route('products.update', [$product->id]) }}" method="post">
                            @method('put')
                            @csrf
                            <label for="">Name product</label>
                            <input type="text" name="name" class="form-control" value="{{old('name',$product->name)}}" required autofocus>
                            @error('name')
                            {{$message}}
                            @enderror
                            <label for="">Price</label>
                            <input type="number" name="price" class="form-control" value="{{old('price',$product->price)}}">
                            @error('price')
                            {{$message}}
                            @enderror
                            <button type="submit">Update product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
