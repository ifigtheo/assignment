<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class ApiController extends AbstractController
{
    const HTTP_STATUS_OK = 200;

    private $apiHostname;

    public function __construct()
    {
        $this->apiHostname = $_ENV['API_HOSTNAME'];
    }

    public function consumeApiData()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $this->apiHostname);

        if (ApiController::HTTP_STATUS_OK !== $response->getStatusCode()) {
            return;
        }

        return $response->toArray();
    }

    public function createMessage($apiData)
    {
        $gatewayEui = hexdec($apiData['gatewayEui']);
        $apiData['gatewayEui'] = number_format($gatewayEui, 0, '', '');
        $apiData['profileId'] = hexdec($apiData['profileId']);
        $apiData['endpointId'] = hexdec($apiData['endpointId']);
        $apiData['clusterId'] = hexdec($apiData['clusterId']);
        $apiData['attributeId'] = hexdec($apiData['attributeId']);

        $message = array();
        // Creating Routing Key: <gateway eui>.<profile>.<endpoint>.<cluster>.<attribute>
        $message['routingKey'] = $apiData['gatewayEui'] . '.' . $apiData['profileId'] . '.' . $apiData['endpointId'] . '.' . $apiData['clusterId'] . '.' . $apiData['attributeId'];

        $message['body'] = array('value' => $apiData['value'], 'timestamp' => $apiData['timestamp']);
        return $message;
    }

}