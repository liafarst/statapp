@extends('layouts.display')

@section('scripts')
  <script type="text/javascript" src="{{ URL::asset('public/js/history.js') }}"></script>
@endsection

@section('content')

<div class="modal fade" id="confirm-new-products" tabindex="-1" role="dialog" aria-labelledby="newProductsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newProductsLabel">New Products?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The following products do not appear in your <a href="#">Product list</a>:<br><br>
        <ul id="products-to-confirm"></ul>
        Would you like to add them?
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm-changes" class="btn btn-primary btn-correction">Save changes</button>
        <button type="button" class="btn btn-primary btn-correction" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    var orders = {!! json_encode($data['bill']->orders->toArray()) !!};
    var products = {!! json_encode($data['products']->toArray()) !!};
    var mainCat = {!! json_encode($data['main-categories']->toArray()) !!};
    var specificCat = {!! json_encode($data['specific-categories']->toArray()) !!};
</script>
<div id="fill-alert" class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal;display:none;">
  Please fill all fields.
</div>
<div class="card card-intro">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
        <div class="col-4 text-left pl-5" style="font-size:20px">
          <div class="form-group row">
            <label for="date" class="col-4 col-form-label">Date:</label>
            <div class="col-8">
              <input class="form-control" type="date" value="{{ \Carbon\Carbon::parse($data['bill']->created_at)->format('Y-m-d') }}" id="date" required="required">
            </div>
          </div>
        </div>
        <div class="col-5 text-center" style="font-size:20px">
          <label for="location" style="display:inline-block">Location: </label>
          <select class="form-control ml-4" id="location" style="max-width:200px;display:inline-block">
            @foreach($data['locations'] as $location)
              <option value="{{ $location->id }}" @if($location->name == $data['bill']->location->name) selected @endif>{{ $location->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-3 text-right pr-5" style="font-size:20px;">
          Total: <span id="total">{{ App\Helpers\CustomHelpers::fancyPrice($data['bill']->total) }}</span> &euro;
        </div>
      </div>
    </div>
    <hr>
    <ul>
      @foreach($data['bill']->orders as $i=>$order)
      <li id="order-{{ $order->id }}" class="old-orders">
        <div class="row">
          <div class="col-2 text-center">
            <span class="bullet-img">
              @if($order->product->mainCat && $order->product->specificCat)
                <img id="catImage{{ $order->id }}" src="{{URL::asset('public/images/' . $order->product->specificCat->name . $order->product->mainCat->id . '.png')}}" width="40" height="40">
              @elseif($order->product->mainCat)
                <img id="catImage{{ $order->id }}" src="{{URL::asset('public/images/' . $order->product->mainCat->name . '.png')}}" width="40" height="40">
              @else
                <img id="catImage{{ $order->id }}" src="{{URL::asset('public/images/Default.png')}}" width="40" height="40">
              @endif
            </span>
          </div>
          <div class="col-3 text-center product-name-block">
            <input id="product-name-{{ $order->id }}" autocomplete="off" name="hidden" value="{{ $order->product->name }}" type="text" class="form-control product-name-ea product-name" />
          </div>
          <div class="col-3 text-center">
            (
            <input id="quantity{{ $order->id }}" type="number" value="{{ $order->quantity}}" class="form-control quantity text-right" style="width:60px;display:inline;">

            <select id="quantity-type{{ $order->id }}" class="form-control quantity-type" style="width:100px;display:inline">
              <option value="0" @if($order->quantity_type == '0') selected @endif></option>
              <option value="piece" @if($order->quantity_type == 'piece') selected @endif>piece(s)</option>
              <option value="meter" @if($order->quantity_type == 'meter') selected @endif>meter(s)</option>
              <option value="kg" @if($order->quantity_type == 'kg') selected @endif>kg</option>
              <option value="liter" @if($order->quantity_type == 'liter') selected @endif>liter(s)</option>
            </select>
            )
          </div>
          <div class="col-2 text-center">
            (
            <input id="price{{ $order->id }}" type="number" class="form-control price price-box text-right" value="{{ App\Helpers\CustomHelpers::fancyPrice($order->price) }}" style="width:80px;display:inline;"> &euro;
            )
          </div>
          <div class="col-2 text-center">
            <div class="card-edit non-selectable text-center"><a id="orderDel-{{ $order->id }}" class="delete-order">Delete</a></div>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
    <div class="card-edit non-selectable"><a id="add-extra">Add</a><a id="save-orders">Save</a></div>
  </div>
</div>
@endsection
