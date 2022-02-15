<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;

class PropertiesController extends BaseController
{
    protected $baseUrl = 'http://61aec09c2fd8100017ccd65c.mockapi.io/';
    protected $rentEndpoint = 'api/v1/rent';
    protected $buyEndpoint = 'api/v1/buy';
    protected $filters = [];

    public function index(): JsonResponse
    {

        if (Request::has('cost')) {
            $this->filters[] = 'cost=' . Request::get('cost');
        }

        if (Request::has('name')) {
            $this->filters[] = 'name=' . Request::get('name');
        }

        try {
            $collection = collect([
                ["name" => '1', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '2', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '3', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '4', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '5', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '6', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '7', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '8', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '9', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '10', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '11', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '12', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '13', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
                ["name" => '14', "cost" => 1000, "bedrooms" => 2,"bathroom" => 2,"year" => 2020,"slot" => 2,"mts" => 120,"src" => "https://properties-monopolio.directus.app/assets/dba5a4fb-af5a-41c0-bff0-51d6817feddf"],
            ]);

            //            return $this->responseOk('asd');
            $responseBuy = $this->getDataFromApi($this->buyEndpoint);
            $collection = $responseBuy->collect();
            $responseRent = $this->getDataFromApi($this->rentEndpoint);

            return $this->responseOk($collection->merge($responseRent->collect())->paginate(
                Request::get('perPage', 6))->appends(Request::except('page')));


//            return $this->responseOk( $collection->paginate(6)->appends(Request::except('page')));


        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    private function responseOk($data): JsonResponse
    {
        return response()->json($data, 200);
    }

    private function responseError($message): JsonResponse
    {
        return response()->json($message, 500);
    }

    /**
     * @throws Exception
     */
    private function getDataFromApi($endpoint): Response
    {
        $url = $this->baseUrl . $endpoint;

        if (count($this->filters)) {
            $url .= "?" . join($this->filters, '&');
        }

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new Exception('Error while trying to connect to the external API');
        }

        return $response;
    }
}
