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
            $responseBuy = $this->getDataFromApi($this->buyEndpoint);
            $responseRent = $this->getDataFromApi($this->rentEndpoint);

            $collection = $responseBuy->collect()
                ->merge($responseRent->collect());

            return $this->responseOk(
                $collection
                    ->paginate(Request::get('perPage', 6))
                    ->appends(Request::except('page'))
            );

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
        $url = env('EXTERNAL_API_URL') . $endpoint;

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
