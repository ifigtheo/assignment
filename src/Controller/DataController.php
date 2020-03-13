<?php

namespace App\Controller;

use App\Entity\Data;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class DataController extends AbstractController
{

    /**
     * @Route("/create/{value}/{timestamp}/{routingKey}", methods="GET|POST", name="save_data")
     * @param $value
     * @param $timestamp
     * @param $routingKey
     * @return Response
     */
    public function createApiData(String $value, String $timestamp, String $routingKey): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $data = new Data();
        $data->setValue($value);
        $data->setTimestamp($timestamp);

        $details = explode(".", $routingKey);

        $data->setGatewayEui($details[0]);
        $data->setProfileId($details[1]);
        $data->setEndpointId($details[2]);
        $data->setClusterId($details[3]);
        $data->setAttributeId($details[4]);

        $entityManager->persist($data);

        $entityManager->flush();

        return Response::create()->setContent("Data has been saved successfully");
    }

}
