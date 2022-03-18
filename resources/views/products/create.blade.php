@extends('welcome')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <label for="">Name product</label>
                            <input type="text" name="name" class="form-control" value="{{old('name')}}">
                            @error('name')
                            {{$message}}
                            @enderror
                            <label for="">Price</label>
                            <input type="number" name="price" class="form-control" value="{{old('price')}}">
                            @error('price')
                            {{$message}}
                            @enderror
                            <label for="">Photo</label>
                            <input type="file" name="photo" class="form-control">
                            @error('photo')
                            {{$message}}
                            @enderror
                            <button type="submit">Create product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
