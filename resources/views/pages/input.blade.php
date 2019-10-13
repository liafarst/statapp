@extends('layouts.input')

@section('styles')
<style id="styleStep1"></style>
<style id="styleStep2"></style>
<style id="styleStep3"></style>
<style id="styleStep4"></style>
<style id="styleStep5"></style>
<style id="styleStep6"></style>
<style id="styleStep7"></style>
<style id="styleStep8"></style>
<style id="styleStep9"></style>
<style id="styleStep10"></style>
<style id="styleStep11"></style>
<style id="styleStep12"></style>
<style id="styleStep13"></style>
<style id="styleStep14"></style>
<style id="styleStep15"></style>
<style id="styleStep16"></style>
<style id="styleStep17"></style>
<style id="styleStep18"></style>
<style id="styleStep19"></style>
<style id="styleStep20"></style>
@endsection

@section('progress')
<div class="step-indicator-container">
  <ul class="step-indicator">
    <li id="step1" class="stepper active non-selectable">Product name</li>
    <li id="step2" class="stepper non-selectable">Product name</li>
    <li id="step3" class="stepper non-selectable">Product name</li>
    <li id="step4" class="stepper non-selectable">Product name</li>
    <li id="step5" class="stepper non-selectable">Product name</li>
    <li id="step6" class="stepper non-selectable">Product name</li>
    <li id="step7" class="stepper non-selectable">Product name</li>
    <li id="step8" class="stepper non-selectable">Product name</li>
    <li id="step9" class="stepper non-selectable">Product name</li>
    <li id="step10" class="stepper non-selectable">Product name</li>
    <li id="step11" class="stepper non-selectable">Product name</li>
    <li id="step12" class="stepper non-selectable">Product name</li>
    <li id="step13" class="stepper non-selectable">Product name</li>
    <li id="step14" class="stepper non-selectable">Product name</li>
    <li id="step15" class="stepper non-selectable">Product name</li>
    <li id="step16" class="stepper non-selectable">Product name</li>
    <li id="step17" class="stepper non-selectable">Product name</li>
    <li id="step18" class="stepper non-selectable">Product name</li>
    <li id="step19" class="stepper non-selectable">Product name</li>
    <li id="step20" class="stepper non-selectable">Product name</li>

  </ul>
</div>
@endsection

@section('content')

<script>
    var products = {!! json_encode($data['products']->toArray()) !!};
    var mainCat = {!! json_encode($data['main-categories']->toArray()) !!};
    var specificCat = {!! json_encode($data['specific-categories']->toArray()) !!};
</script>
<div class="product">
  <div class="plan basic">
    <div class="plan-inner">
      <div class="row text-center plan-inner" style="margin:0 !important;">
        <div class="col-4 text-left">
          <button type="button" id="button-left" class="btn btn-lg btn-primary btn-arrow-left ml-3 btn-correction" onclick="this.blur();">Previous</button>
        </div>
        <div class="col-4">
          <button type="button" id="submit" class="btn btn-lg btn-primary btn-correction" onclick="this.blur();">Submit</button>
        </div>
        <div class="col-4 text-right">
          <button type="button" id="button-right" class="btn btn-lg btn-primary btn-arrow-right mr-3 btn-correction" onclick="this.blur();">Next</button>
        </div>
      </div>
    </div>
  </div>
</div>
@for($i = 1; $i <= 20; $i++)
  <div id="product{{ $i }}" class="product @if($i != 1) d-none @endif">
    <div class="plan basic">
      <div class="plan-inner">
        <div class="entry-title">
          <!-- <span class="hot">Favourite</span> -->
          <div class="row product-name-block">
            <div class="col-6 text-left" style="color: #fff;">Product name</div>
            <div class="col-6">
              <input id="product-name{{ $i }}" autocomplete="off" name="hidden" value="" type="text" class="form-control" style="width:350px;" />
            </div>
          </div>
          <div class="categories">
            <div class="row mb-3">
              <div class="col-6">Main category</div>
              <div class="col-6">
                <select id="main-cat{{ $i }}" class="form-control main-cat">
                    <option value="0"></option>
                  @foreach($data['main-categories'] as $mainCat)
                    <option value="{{ $mainCat['id'] }}">{{ $mainCat['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-6">Specific category</div>
              <div class="col-6">
                <select id="specific-cat{{ $i }}" class="form-control specific-cat">
                    <option value="0"></option>
                </select>
              </div>
            </div>
          </div>
          <div class="price"><img id="catImage{{ $i }}" src="{{URL::asset('public/images/apple.png')}}" onerror="this.src='{{URL::asset('public/images/Default.png')}}'" width="130" height="130" />
          </div>
        </div>
        <div class="entry-content">
          <div class="row line">
            <div class="col-4"><strong class="align-middle">Quantity</strong></div>
            <div class="col-4"><input id="quantity{{ $i }}" type="number" class="form-control quantity" style="width:100%"></div>
            <div class="col-4">
              <select id="quantity-type{{ $i }}" class="form-control">
                <option value="0"></option>
                <option value="piece">piece(s)</option>
                <option value="meter">meter(s)</option>
                <option value="kg">kg</option>
                <option value="liter">liter(s)</option>
              </select>
            </div>
          </div>
          <div class="row line">
            <div class="col-4"><strong class="align-middle">Price</strong></div>
            <div class="col-4"><input id="price{{ $i }}" type="number" class="form-control" style="width:100%"></div>
            <div class="col-4 text-center">
              <span class="align-middle">&euro;</span>
            </div>
          </div>
          <!-- <div class="form-check text-center" style="margin-top:10px;">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label non-selectable" for="remember">
              Remember price and quantity
            </label>
          </div> -->
        </div>
      </div>
    </div>
  </div>
@endfor
@endsection
<!-- contenteditable="true" -->
