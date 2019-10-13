@extends('layouts.display')

@section('scripts')
  <script type="text/javascript" src="{{ URL::asset('public/js/locations.js') }}"></script>
@endsection

@section('content')

@if($data['status'] && $data['status'] == 'successfulEdit')
  <div class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;">
    Locations were updated successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if($data['status'] && $data['status'] == 'failedEdit')
  <div class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;">
    Locations couldn't be updated. Please try again later.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if($data['status'] && $data['status'] == 'successfulDelete')
  <div class="card alert alert-success alert-dismissible fade show" role="alert" style="width:95%;">
    Location was deleted successfully.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if($data['status'] && $data['status'] == 'failedDelete')
  <div class="card alert alert-danger alert-dismissible fade show" role="alert" style="width:95%;">
    Location couldn't be deleted. Please try again later.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<div class="card card-intro">
  <div class="card-body">
    <div class="card-title">
      <h1 class="text-center">Locations</h1>
    </div>
    <hr>
    <ul id="locations">
      @foreach($data['locations'] as $location)
        <li id="location-{{ $location->id }}" class="old-locations">
          <div class="row mb-4">
            <div class="col-3 text-center">
              <input type="text" id="locationName-{{ $location->id }}" class="location-name form-control text-center location-elements" value="{{ $location['name'] }}" style="height:50px;">
            </div>
            <div class="col-3 text-center">
              <input type="text" id="locationAddress-{{ $location->id }}" class="location-address form-control text-center location-elements" value="{{ $location['address'] }}" style="height:50px;">
            </div>
            <div class="col-4 text-center">
              <ul class="times" style="list-style-position: inside;">
                <li style="list-style-type: circle;">Mo <input type="time" class="time text-center" value="@if(strpos($location->moH, '-') !== false){{ explode('-', $location->moH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->moH, '-') !== false){{ explode('-', $location->moH)[1] }}@endif"><input id="Monday" @if(strpos($location->moH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Monday">Closed</label></li>
                <li style="list-style-type: circle;">Tu <input type="time" class="time text-center" value="@if(strpos($location->tuH, '-') !== false){{ explode('-', $location->tuH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->tuH, '-') !== false){{ explode('-', $location->tuH)[1] }}@endif"><input id="Tuesday" @if(strpos($location->tuH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Tuesday">Closed</label></li>
                <li style="list-style-type: circle;">We <input type="time" class="time text-center" value="@if(strpos($location->weH, '-') !== false){{ explode('-', $location->weH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->weH, '-') !== false){{ explode('-', $location->weH)[1] }}@endif"><input id="Wednesday" @if(strpos($location->weH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Wednesday">Closed</label></li>
                <li style="list-style-type: circle;">Th <input type="time" class="time text-center" value="@if(strpos($location->thH, '-') !== false){{ explode('-', $location->thH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->thH, '-') !== false){{ explode('-', $location->thH)[1] }}@endif"><input id="Thursday" @if(strpos($location->thH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Thursday">Closed</label></li>
                <li style="list-style-type: circle;">Fr <input type="time" class="time text-center" value="@if(strpos($location->frH, '-') !== false){{ explode('-', $location->frH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->frH, '-') !== false){{ explode('-', $location->frH)[1] }}@endif"><input id="Friday" @if(strpos($location->frH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Friday">Closed</label></li>
                <li style="list-style-type: circle;">Sa <input type="time" class="time text-center" value="@if(strpos($location->saH, '-') !== false){{ explode('-', $location->saH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->saH, '-') !== false){{ explode('-', $location->saH)[1] }}@endif"><input id="Saturday" @if(strpos($location->saH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Saturday">Closed</label></li>
                <li style="list-style-type: circle;">So <input type="time" class="time text-center" value="@if(strpos($location->suH, '-') !== false){{ explode('-', $location->suH)[0] }}@endif"> - <input type="time" class="time text-center" value="@if(strpos($location->suH, '-') !== false){{ explode('-', $location->suH)[1] }}@endif"><input id="Sunday" @if(strpos($location->suH, '-') === false) checked @endif class="closed mx-2" type="radio"><label for="Sunday">Closed</label></li>
              </ul>
            </div>
            <div class="col-2 text-center">
              <div class="card-edit non-selectable text-center location-elements"><a id="locationDelete-{{ $location->id }}" class="delete-location">Delete</a></div>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
    <div class="card-edit non-selectable"><a id="add-location">Add</a><a id="save-location">Save</a></div>
  </div>
</div>
@endsection
