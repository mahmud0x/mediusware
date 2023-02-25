@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" action="/product">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                    <option disabled value="">--Select A Variant--</option>
                        <optgroup label="color">
                        @foreach($dynamic_color as $color){
                            <option value="{{$color->variant}}">{{$color->variant}}</option>
                        }
                        @endforeach
                        </optgroup>
                        <optgroup label="size">
                        @foreach($dynamic_size as $size){
                            <option value="{{$size->variant}}">{{$size->variant}}</option>
                        }
                        @endforeach
                        </optgroup>
                        <optgroup label="style">
                        @foreach($dynamic_style as $style){
                            <option value="{{$style->variant}}">{{$style->variant}}</option>
                        }
                        @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" value="{{old('price_from')}}" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" value="{{old('price_to')}}" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($products as $product)
                    {
                        <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->title}} <br> Created at : {{$product->updated_at}}</td>
                        <td>{{Str::limit($product->description, 40)}}</td>

                        <td>
                            
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                @foreach ($product->variantPrices as $varprice)
                                <dt class="col-sm-3 pb-0">
                                    @foreach ($variants as $var)
                                        @if($var->id == $varprice->product_variant_one)
                                            {{$var->variant."/"}}
                                        @elseif($var->id == $varprice->product_variant_two)
                                            {{$var->variant."/"}}
                                        @elseif($var->id == $varprice->product_variant_three)
                                            {{$var->variant}}
                                        @else
                                            @continue
                                        @endif
                                    @endforeach

                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{$varprice->price}}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{$varprice->stock}}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                                </dt>

                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    }
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{$products->firstItem()}} to {{$products->lastItem()}} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-2">
                {{$products->links()}}
                </div>
            </div>
        </div>
    </div>

@endsection
