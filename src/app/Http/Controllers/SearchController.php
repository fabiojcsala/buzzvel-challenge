<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | jsonDecode
    |--------------------------------------------------------------------------
    |
    | O objetivo desta função quando solicitada é basicamente:
    | acessar a API externa, obter os dados, armazená-los em um array
    | e retorná-lo.
    |
    */

    function jsonDecode() {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL,("https://buzzvel-interviews.s3.eu-west-1.amazonaws.com/hotels.json"));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close($ch);
        
        $result = json_decode($server_output);
        
        $dataJson = $result->{'message'};
        
        return $dataJson;
    }

    /*
    |--------------------------------------------------------------------------
    | calcDistance
    |--------------------------------------------------------------------------
    |
    | O objetivo desta função quando solicitada é basicamente:
    | obter por parâmetro a latitude e longitude do usuário
    | e a latitude e longitude do hotel cadastrado, efetuar o cálculo
    | de distância entre duas geolocalidades baseadas nestes parâmetros,
    | converter para a unidade "KM" e retorná-la.
    |
    */

    function calcDistance($lat1, $lon1, $lat2, $lon2) {
        $theta = ($lon1 - $lon2);

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        
        $dist = acos($dist);
        
        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;
        
        return floatval(($miles * 1.609344));
    }

    /*
    |--------------------------------------------------------------------------
    | getNearbyHotels
    |--------------------------------------------------------------------------
    |
    | O objetivo desta função quando solicitada é basicamente:
    | obter por parâmetro a latitude e longitude do usuário,
    | solicitar os dados da API externa através da função jsonDecode,
    | uma vez com o array contendo as informações dos hotéis cadastrados,
    | executar um laço de repetição onde será:
    | validado algumas informações como dados em branco (""),
    | cada dado será organizado em um novo array denominado "hotel",
    | e este novo array será adicionado a um outro array denominado "hoteis"
    | e este retornado.
    |
    */

    function getNearbyHotels($latitude, $longitude) {
        $hotels = [];
        $hotel = [];

        $dataJson = $this->jsonDecode();
    
        for($i = 0; $i < count($dataJson); $i++) {
            if($dataJson[$i][1] != "" && $dataJson[$i][2] != "" && count($dataJson[$i]) < 5) {
                $hotel = [
                    "name" => $dataJson[$i][0],
                    "latitude" => $dataJson[$i][1],
                    "longitude" => $dataJson[$i][2],
                    "price" => number_format($dataJson[$i][3], 2, ".", ""),
                    "distance" => number_format(floatval($this->calcDistance($latitude, $longitude, $dataJson[$i][1], $dataJson[$i][2])), 3, '.', '')
                ];
                array_push($hotels, $hotel);
            }
        }
        return $hotels;
    }

    /*
    |--------------------------------------------------------------------------
    | home
    |--------------------------------------------------------------------------
    |
    | O objetivo desta função quando solicitada é basicamente:
    | retornar a view principal do sistema.
    |
    */

    public function home() {
        return view('home.index');
    }

    /*
    |--------------------------------------------------------------------------
    | search
    |--------------------------------------------------------------------------
    |
    | O objetivo desta função quando solicitada é basicamente:
    | receber os dados do formulário de busca e oraganizá-los,
    | obter o array com todas as informações dos hotéis necessárias,
    | filtrá-las baseando-se na solicitação do usuário,
    | e retornar a view com o resultado da pesquisa.
    |
    */

    public function search(Request $request) {
        $dataForm = $request -> all();

        $latitude = $dataForm['latitude'];
        $longitude = $dataForm['longitude'];

        $qtdResult = $dataForm['qtdResult'];
        $orderBy = $dataForm['orderBy'];

        $resultArray = $this->getNearbyHotels($latitude, $longitude);

        /*
        |
        | prioridade: filtrar os hotéis pela distância, ou seja, os mais próximos primeiro.
        |
        */

        usort($resultArray, function ($a, $b) {
            return (($a['distance'] != $b['distance']) ? (($a['distance'] < $b['distance']) ? -1 : 1) : 0);
        });

        /*
        |
        |exibir apenas a quantidade solicitada pelo usuário.
        |"all" = todos cadastrados
        |
        */

        if($qtdResult != "all") {
            $resultArray = array_slice($resultArray, 0, $dataForm['qtdResult']);
        }

        /*
        |
        |caso solicitado, ordenar por preço.
        |
        */

        if($orderBy == "price") {
            usort($resultArray, function ($a, $b) {
                return (($a['price'] != $b['price']) ? (($a['price'] < $b['price']) ? -1 : 1) : 0);
            });
        }

        return view('search.index', compact('resultArray'));
    }
}