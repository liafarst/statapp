<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Bill;
use App\Order;

class LocationController extends Controller {

  public function getLocations(Request $request){

    $status = $request->input('status');

    $locations = Location::where('user_id', '1')->get();

    foreach($locations as $location){
      $openingHours = $location->opening_hours;
      $openingHoursArray = explode(',', $openingHours);
      $location->moH = $openingHoursArray[0];
      $location->tuH = $openingHoursArray[1];
      $location->weH = $openingHoursArray[2];
      $location->thH = $openingHoursArray[3];
      $location->frH = $openingHoursArray[4];
      $location->saH = $openingHoursArray[5];
      $location->suH = $openingHoursArray[6];
    }

    $data = [
      'locations' => $locations,
      'status' => $status
    ];

    return view('pages.locations')->with('data', $data);
  }

  public function deleteLocation(){

    $locationID = request()->input('locationID');

    $location = Location::find($locationID);

    if($location){
      $bills = $location->bills()->get();
      for($i = 0; $i < sizeof($bills); $i++){
        $orders = $bills[$i]->orders()->get();
        for($j = 0; $j < sizeof($orders); $j++){
          $orders[$j]->delete();
        }
        $bills[$i]->delete();
      }
      $location->delete();
    }

    response(null, 204);

  }

  public function updateLocations(){

    $locationIDs = request()->input('locationIDs');
    $namesOld = request()->input('namesOld');
    $addressesOld = request()->input('addressesOld');
    $openingHoursOld = request()->input('openingHoursOld');
    $namesNew = request()->input('namesNew');
    $addressesNew = request()->input('addressesNew');
    $openingHoursNew = request()->input('openingHoursNew');

    foreach($locationIDs as $i=>$locationID){

      $location = Location::find($locationID);

      if($location){
        $location->name = $namesOld[$i];
        $location->address = $addressesOld[$i];
        $location->opening_hours = $openingHoursOld[$i];

        $location->save();
      }

    }

    if($namesNew){
      for($i = 0; $i < sizeof($namesNew); $i++){
        $location = new Location();

        $location->name = $namesNew[$i];
        $location->address = $namesNew[$i];
        $location->user_id = 1;
        $location->opening_hours = $openingHoursNew[$i];

        $location->save();
      }
    }

      response(null, 204);

  }

}
