<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DataConsumerController extends AbstractController
{
    const NUMBER_OF_API_CALLS = 1;
    const MESSAGE_RETRIEVAL_PERIOD_SEC = 5;

    private $apiController;

    private $messageController;

    /**
     * @Route("/data/consumer", name="data_consumer")
     */
    public function index()
    {
        foreach (range(1, DataConsumerController::NUMBER_OF_API_CALLS) as $i) {
            // Consume data from the API
            $message = $this->consumeApiData();
            if ($message) {
                // Send message to the RabbitMQ
                $this->filterApiData($message);
            }
            sleep(DataConsumerController::MESSAGE_RETRIEVAL_PERIOD_SEC);
        }

        // Consume data from the RabbitMQ, store them in the database
        $this->getFilteredData();
    }

    public function getApiController()
    {
        if (is_null($this->apiController)) {
            $this->apiController = new ApiController();
        }
        return $this->apiController;
    }

    public function getMessageController()
    {
        if (is_null($this->messageController)) {
            $this->messageController = new MessageController();
        }
        return $this->messageController;
    }

    public function consumeApiData()
    {
        $apiData = $this->getApiController()->consumeApiData();
        if (!$apiData) {
            return;
        }
        return $this->getApiController()->createMessage($apiData);
    }

    public function filterApiData($message)
    {
        $this->getMessageController()->sendMessage($message);
    }

    public function getFilteredData()
    {
        $this->getMessageController()->receiveMessage();
    }
}
