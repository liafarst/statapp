@extends('layouts.input')

@section('progress')
<div class="step-indicator-container">
  <ul class="step-indicator">
    <li id="step1" class="active non-selectable">Product name</li>
    <li id="step2" class="non-selectable">Product name</li>
    <li id="step3" class="non-selectable">Product name</li>
    <li id="step4" class="non-selectable">Product name</li>
    <li id="step5" class="non-selectable">Product name</li>
    <li id="step6" class="non-selectable">Product name</li>
    <li id="step7" class="non-selectable">Product name</li>
    <li id="step8" class="non-selectable">Product name</li>
    <li id="step9" class="non-selectable">Product name</li>
    <li id="step10" class="non-selectable">Product name</li>
    <li id="step11" class="non-selectable">Product name</li>
    <li id="step12" class="non-selectable">Product name</li>
    <li id="step13" class="non-selectable">Product name</li>
    <li id="step14" class="non-selectable">Product name</li>
    <li id="step15" class="non-selectable">Product name</li>
    <li id="step16" class="non-selectable">Product name</li>
    <li id="step17" class="non-selectable">Product name</li>
    <li id="step18" class="non-selectable">Product name</li>
    <li id="step19" class="non-selectable">Product name</li>
    <li id="step20" class="non-selectable">Product name</li>
    <br><br><br>
  </ul>
</div>
@endsection

@section('content')

<script>
    var products = {!! json_encode($data['products']->toArray()) !!};
    var specificCat = {!! json_encode($data['specific-categories']->toArray()) !!};
</script>

@for($i = 1; $i <= 20; $i++)
  <div id="product{{ $i }}" class="product @if($i != 1) d-none @endif">
    <div class="plan basic">
      <div class="plan-inner">
        <div class="entry-title">
          <!-- <span class="hot">Hot</span> -->
          <div class="row product-name">
            <div class="col-4" style="color: #fff;">Product name</div>
            <div class="col-8"><input id="name{{ $i }}" type="text" class="form-control" style="width:350px;" /></div>
          </div>
          <div class="categories">
            <div class="row mb-3">
              <div class="col-4">Main category</div>
              <div class="col-8">
                <select id="main-cat{{ $i }}" class="form-control main-cat">
                    <option value="0"></option>
                  @foreach($data['main-categories'] as $mainCat)
                    <option value="{{ $mainCat['id'] }}">{{ $mainCat['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-4">Specific category</div>
              <div class="col-8">
                <select id="specific-cat{{ $i }}" class="form-control">
                    <option value="0"></option>
                  @foreach($data['specific-categories'] as $specificCat)
                    <option>{{ $specificCat['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="price"><img src="{{URL::asset('public/images/football.png')}}" width="60" height="60" />
          </div>
        </div>
        <div class="entry-content">
              <div class="row line">
                <div class="col-2"><strong>Quantity</strong></div>
                <div class="col-7"><input id="quantity{{ $i }}" type="number" class="form-control" style="width:100%"></div>
                <div class="col-3">
                  <select id="quantity-type{{ $i }}" class="form-control">
                    <option value="piece">piece(s)</option>
                    <option value="meter">meter(s)</option>
                    <option value="kg">kg</option>
                    <option value="liter">liter(s)</option>
                  </select>
                </div>
              </div>
              <div class="row line">
                <div class="col-2"><strong>Price</strong></div>
                <div class="col-7"><input id="price{{ $i }}" type="number" class="form-control" style="width:100%"></div>
                <div class="col-3">
                  &euro;
                </div>
              </div>
              <div class="row" style="padding:10px 0;">
                <div class="col-3">
                  <div class="blue-button">
                    <button class="button-left" type="button">Previous</button>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-check" style="margin-top:10px;">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label non-selectable" for="remember">
                      Remember price and quantity
                    </label>
                  </div>
                </div>
                <div class="col-3">
                  <div class="blue-button">
                    <button class="button-right" type="button">Next</button>
                  </div>
                </div>
              </div>
        </div>
      </div>
    </div>
  </div>
@endfor
@endsection
<!-- contenteditable="true" -->
