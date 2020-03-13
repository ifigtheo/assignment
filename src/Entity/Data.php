<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DataRepository")
 */
class Data
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $value;

    /**
     * @ORM\Column(type="bigint")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="bigint")
     */
    private $gatewayEui;

    /**
     * @ORM\Column(type="bigint")
     */
    private $profileId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $endpointId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $clusterId;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $attributeId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getGatewayEui(): ?string
    {
        return $this->gatewayEui;
    }

    public function setGatewayEui(string $gatewayEui): self
    {
        $this->gatewayEui = $gatewayEui;

        return $this;
    }

    public function getProfileId(): ?string
    {
        return $this->profileId;
    }

    public function setProfileId(string $profileId): self
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getEndpointId(): ?string
    {
        return $this->endpointId;
    }

    public function setEndpointId(string $endpointId): self
    {
        $this->endpointId = $endpointId;

        return $this;
    }

    public function getClusterId(): ?string
    {
        return $this->clusterId;
    }

    public function setClusterId(string $clusterId): self
    {
        $this->clusterId = $clusterId;

        return $this;
    }

    public function getAttributeId(): ?string
    {
        return $this->attributeId;
    }

    public function setAttributeId(?string $attributeId): self
    {
        $this->attributeId = $attributeId;

        return $this;
    }
}
