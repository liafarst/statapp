@extends('layouts.display')

@section('scripts')
  <script type="text/javascript" src="{{ URL::asset('public/js/history.js') }}"></script>
@endsection

@section('content')

<script>
    var products = {!! json_encode($data['products']->toArray()) !!};
    var mainCat = {!! json_encode($data['main-categories']->toArray()) !!};
    var specificCat = {!! json_encode($data['specific-categories']->toArray()) !!};
    var locations = {!! json_encode($data['locations']->toArray()) !!};
</script>

@if($data['status'] && $data['status'] == 'successfulEdit')
  <div class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;">
    Bill was edited successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if($data['status'] && $data['status'] == 'failedEdit')
<div class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal">
  Bill couldn't be edited. Please try again later.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

@if($data['status'] && $data['status'] == 'successfulDelete')
  <div class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;">
    Bill was deleted successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if($data['status'] && $data['status'] == 'failedDelete')
<div class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;font-weight:normal">
  Bill couldn't be deleted. Please try again later.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<div class="modal fade" id="location-info" tabindex="-1" role="dialog" aria-labelledby="location-name" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="location-name">Location information</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>Address:</h4>
        <span id="location-address"></span><br><br>
        <h4>Opening hours:</h4>
        <h6>Mo:<span id="mo"></span></h6>
        <h6>Tu:<span id="tu"></span></h6>
        <h6>We:<span id="we"></span></h6>
        <h6>Th:<span id="th"></span></h6>
        <h6>Fr:<span id="fr"></span></h6>
        <h6>Sa:<span id="sa"></span></h6>
        <h6>Su:<span id="su"></span></h6>
      </div>
    </div>
  </div>
</div>

<div class="card card-intro">
  <div class="card-body">
    <h2 class="card-title">Bill History</h2>
    <p class="card-text">Here you can find and edit all your bills.</p>
  </div>
</div>

@foreach($data['bills'] as $bill)
<div class="card card-pop-up">
  <div class="card-body">
    <div class="card-title">
      <div class="row">
        <div class="col-4 text-left pl-5" style="font-size:20px">
          Date: {{ \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}
        </div>
        <div class="col-4 text-center" style="font-size:20px">
          Location: @if($bill->location) <a id="{{ $bill->location->id }}" class="non-selectable location-link">{{ $bill->location->name }}</a> @endif
        </div>
        <div class="col-4 text-right pr-5" style="font-size:20px;">
          Total: {{ App\Helpers\CustomHelpers::fancyPrice($bill->total) }} &euro;
        </div>
      </div>
    </div>
    <hr>
    @if(count($bill->orders) < 4)
    <ul>
      @foreach($bill->orders as $i=>$order)
        <li>
          <div class="row">
            <div class="col-2 text-center">
              <span #id="test-bullet" class="bullet-img">
                @if($order->product->mainCat && $order->product->specificCat)
                  <img src="{{URL::asset('public/images/' . $order->product->specificCat->name . $order->product->mainCat->id . '.png')}}" width="40" height="40">
                @elseif($order->product->mainCat)
                  <img src="{{URL::asset('public/images/' . $order->product->mainCat->name . '.png')}}" width="40" height="40">
                @else
                  <img src="{{URL::asset('public/images/Default.png')}}" width="40" height="40">
                @endif
              </span>
            </div>
            <div class="col-4 text-center">
              {{ $order->product->name }}
            </div>
            <div class="col-3 text-center">
              ({{ $order->quantity}} {{ $bill->orders[0]->quantity_type}})
            </div>
            <div class="col-3 text-center">
              ({{ App\Helpers\CustomHelpers::fancyPrice($order->price) }} &euro;)
            </div>
          </div>
        </li>
      @endforeach
    </ul>
    @else
    <div class="row">
      <div class="col-6">
        <ul>
          @foreach($bill->orders as $i=>$order)
            <li>
              <div class="row">
                <div class="col-2 text-center">
                  <span #id="test-bullet" class="bullet-img">
                    @if($order->product->mainCat && $order->product->specificCat)
                      <img src="{{URL::asset('public/images/' . $order->product->specificCat->name . $order->product->mainCat->id . '.png')}}" width="40" height="40">
                    @elseif($order->product->mainCat)
                      <img src="{{URL::asset('public/images/' . $order->product->mainCat->name . '.png')}}" width="40" height="40">
                    @else
                      <img src="{{URL::asset('public/images/Default.png')}}" width="40" height="40">
                    @endif
                  </span>
                </div>
                <div class="col-4 text-center">
                  {{ $order->product->name }}
                </div>
                <div class="col-3 text-center">
                  ({{ $order->quantity}} {{ $order->quantity_type}})
                </div>
                <div class="col-3 text-center">
                  ({{ App\Helpers\CustomHelpers::fancyPrice($order->price) }} &euro;)
                </div>
              </div>
            </li>
            @if($i + 1 == round(count($bill->orders) / 2))
          </ul>
        </div>
        <div class="col-6">
          <ul>
            @endif
          @endforeach
        </ul>
      </div>
    </div>
    @endif
    <div class="card-edit non-selectable">
      <a href="/bill-history/edit/{{ $bill->id }}">Edit</a>
      <a id="billDel-{{ $bill->id }}" class="delete-bill">Delete</a>
    </div>
  </div>
</div>
@endforeach
@endsection
