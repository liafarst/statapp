@extends('layouts.charts')

@section('content')
  <script>
      var orders = {!! json_encode($data['orders']->toArray()) !!};
  </script>

  <h1>Spendings by product</h1>
  <h3 id="info"></h3>
  <!-- <div class="container-fluid"> -->
    <div class="row">
      <div class=col-xl-9>
        <canvas id="chart"></canvas>
      </div>
      <div class=col-xl-3>
        <div class="filter-container">
          <label for="product-name">Product</label>
          <input id="product-name" autocomplete="off" name="hidden" value="" type="text" class="form-control product-name" />
          <label for="">Period</label>
          <select id="period" class="form-control">
            <option value="lw">Last week</option>
            <option value="lm">Last month</option>
            <option value="ly">Last year</option>
            <option value="specific" selected>Specific period</option>
          </select>
          <div id="specific-period" style="margin-top:20px;">
            <label for="specific-year">Year</label>
            <ol id="specific-year" class="breadcrumb" style="background:none;">
              <li class="breadcrumb-item active">2019</li>
              <li class="breadcrumb-item">2018</li>
              <li class="breadcrumb-item">2017</li>
            </ol>
            <label for="specific-month">Month</label>
            <ol id="specific-month" class="breadcrumb" style="background:none;">
              <li class="breadcrumb-item active" value="0">All</li>
              <li class="breadcrumb-item" value="1">Jan.</li>
              <li class="breadcrumb-item" value="2">Feb.</li>
              <li class="breadcrumb-item" value="3">Mar.</li>
              <li class="breadcrumb-item" value="4">Apr.</li>
              <li class="breadcrumb-item" value="5">May.</li>
              <li class="breadcrumb-item" value="6">Jun.</li>
              <li class="breadcrumb-item" value="7">Jul.</li>
              <li class="breadcrumb-item" value="8">Aug.</li>
              <li class="breadcrumb-item" value="9">Sep.</li>
              <li class="breadcrumb-item" value="10">Oct.</li>
              <li class="breadcrumb-item" value="11">Nov.</li>
              <li class="breadcrumb-item" value="12">Dec.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="chart-container">
      <canvas id="chart"></canvas>
    </div>
    <div class="filter-container">
      <input id="product-name" autocomplete="off" name="hidden" value="" type="text" class="form-control product-name" />
    </div> -->
  <!-- </div> -->
@endsection

@section('scripts')
  <script type="text/javascript" src="{{ URL::asset('public/js/charts-by-product.js') }}"></script>
@endsection
