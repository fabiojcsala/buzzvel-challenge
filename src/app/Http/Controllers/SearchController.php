<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    function jsonDecode() {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL,("https://buzzvel-interviews.s3.eu-west-1.amazonaws.com/hotels.json"));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close($ch);
        
        $result = json_decode($server_output);
        
        $allData = $result->{'message'};
        
        return $allData;
    }

    function calcDistance($lat1, $lon1, $lat2, $lon2) {
        $theta = ($lon1 - $lon2);

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        
        $dist = acos($dist);
        
        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;
        
        return floatval(($miles * 1.609344));
    }

    function makeObject($userLati, $userLong) {
        $myArray = [];

        $myObj = [];

        $allData = $this->jsonDecode();

        // dd($allData);

        // dd($allData[0]);
    
        for($i = 0; $i < count($allData); $i++) {
            if($allData[$i][1] != "" && $allData[$i][2] != "" && count($allData[$i]) < 5) {
                $myObj = [
                    "name" => $allData[$i][0],
                    "latitude" => $allData[$i][1],
                    "longitude" => $allData[$i][2],
                    "price" => $allData[$i][3],
                    "distance" => $this->calcDistance($userLati, $userLong, $allData[$i][1], $allData[$i][2])
                ];
    
                array_push($myArray, $myObj);
            }
        }

        // dd($myArray);

        return $myArray;
    }

    public function home() {
        $array = 0;

        return view('home.index', compact('array'));
    }

    public function distance(Request $request) {
        $dataForm = $request -> all();

        // dd($dataForm);

        $array = $this->makeObject($dataForm['latitude'], $dataForm['longitude']);

        // dd($array);

        usort($array, function ($a, $b) {
            return (($a['distance'] != $b['distance']) ? (($a['distance'] < $b['distance']) ? -1 : 1) : 0);
        });

        // dd($array);

        return view('search-result.index', compact('array'));
    }

    public function price($array) {
        usort($array, function ($a, $b) {
            return (($a[3] != $b[3]) ? (($a[3] < $b[3]) ? -1 : 1) : 0);
        });
    }
}
