@extends('layouts.display')

@section('styles')
  <link rel="stylesheet" href="{{ URL::asset('public/css/dropdown.css') }}"/>
@endsection

@section('scripts')
  <script type="text/javascript" src="{{ URL::asset('public/js/products.js') }}"></script>
@endsection

@section('content')

  <script>
    var products = {!! json_encode($data['products']->toArray()) !!};
    var mainCat = {!! json_encode($data['main-categories']->toArray()) !!};
    var specificCat = {!! json_encode($data['specific-categories']->toArray()) !!};
  </script>

  <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteLabel">Confirmation required</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete the following product?<br><br>
          <ul id="products-to-delete"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" id="confirm-delete" class="btn btn-primary btn-correction">Delete</button>
          <button type="button" class="btn btn-primary btn-correction" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  @if($data['status'] && $data['status'] == 'successfulEdit')
    <div class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;">
      Products were updated successfully.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if($data['status'] && $data['status'] == 'failedEdit')
  <div class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal">
    Products couldn't be updated. Please try again later.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif

  <div id="fill-alert" class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal;display:none;">
    Please fill all fields.
  </div>

  <div id="deleted-alert" class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal;display:none;"></div>
  <div class="card card-intro">
    <div class="card-body">
      <div class="card-title">
        <h1 class="text-center">Products</h1>
      </div>
      <hr>
      <ul>
        @foreach($data['products'] as $product)
          <li id="product-{{ $product->id }}" class="old-products">
            <div class="row">
              <div class="col-1 text-center">
                @if($product->mainCat && $product->specificCat)
                  <img id="catImage{{ $product->id }}" src="{{URL::asset('public/images/' . $product->specificCat->name . $product->mainCat->id . '.png')}}" width="50" height="50">
                @elseif($product->mainCat)
                  <img id="catImage{{ $product->id }}" src="{{URL::asset('public/images/' . $product->mainCat->name . '.png')}}" width="50" height="50">
                @else
                  <img id="catImage{{ $product->id }}" src="{{URL::asset('public/images/Default.png')}}" width="50" height="50">
                @endif
              </div>
              <div class="col-1 text-center">
                <i id="favourite-{{ $product->id }}" class="@if($product->favourite == 0) far @else fas @endif fa-star favourite-product" style="cursor:pointer;font-size:25px;"></i>
              </div>
              <div class="col-2 text-center">
                <input type="text" id="productName-{{ $product->id }}" class="product-name form-control" contenteditable="true" value="{{ $product['name'] }}" style="height:50px">
              </div>
              <div class="col-3 text-center">
                <span class="custom-dropdown">
                  <select id="mainCat-{{ $product->id }}" class="main-cat products-select">
                    <option value="0"></option>
                    @foreach($data['main-categories'] as $mainCat)
                      <option @if($mainCat->id == $product->main_cat_id) selected @endif value="{{ $mainCat->id }}">{{ $mainCat->name }}</option>
                    @endforeach
                  </select>
                </span>
              </div>
              <div class="col-3 text-center">
                <span class="custom-dropdown">
                <select id="specificCat-{{ $product->id }}" class="specific-cat products-select">
                    <option value="0"></option>
                    @if($product->mainCat)
                      @foreach($product->mainCat->specificCats as $specificCat)
                        <option @if($specificCat->id == $product->specific_cat_id) selected @endif value="{{ $specificCat->id }}">{{ $specificCat->name }}</option>
                      @endforeach
                    @endif
                  </select>
                </span>
              </div>
              <div class="col-2 text-center">
                <div class="card-edit non-selectable text-center"><a id="productDelete-{{ $product->id }}" class="delete-product">Delete</a></div>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
      <div class="card-edit non-selectable"><a id="add-product">Add</a><a id="save-products">Save</a></div>
    </div>
  </div>


@endsection
