<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sendingCurrencyId;

    /**
     * @ORM\Column(type="integer")
     */
    private $receivedCurrencyId;

    /**
     * @ORM\Column(type="float")
     */
    private $sendingRate;

    /**
     * @ORM\Column(type="float")
     */
    private $receivedRate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendingCurrencyId(): ?int
    {
        return $this->sendingCurrencyId;
    }

    public function setSendingCurrencyId(int $sendingCurrencyId): self
    {
        $this->sendingCurrencyId = $sendingCurrencyId;

        return $this;
    }

    public function getReceivedCurrencyId(): ?int
    {
        return $this->receivedCurrencyId;
    }

    public function setReceivedCurrencyId(int $receivedCurrencyId): self
    {
        $this->receivedCurrencyId = $receivedCurrencyId;

        return $this;
    }

    public function getSendingRate(): ?float
    {
        return $this->sendingRate;
    }

    public function setSendingRate(float $sendingRate): self
    {
        $this->sendingRate = $sendingRate;

        return $this;
    }

    public function getReceivedRate(): ?float
    {
        return $this->receivedRate;
    }

    public function setReceivedRate(float $receivedRate): self
    {
        $this->receivedRate = $receivedRate;

        return $this;
    }
}
