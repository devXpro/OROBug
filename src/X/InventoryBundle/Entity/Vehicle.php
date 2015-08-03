<?php

namespace X\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vehicle")
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     */
    private $seats;

    /**
     * @ORM\Column(name="bought_at", type="date")
     */
    private $boughtAt;

    /**
     * @ORM\Column(name="leased_until", type="date")
     */
    private $leasedUntil;

    public function getId()
    {
        return $this->id;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getSeats()
    {
        return $this->seats;
    }

    public function setSeats($seats)
    {
        $this->seats = $seats;
    }

    public function getBoughtAt()
    {
        return $this->boughtAt;
    }

    public function setBoughtAt($boughtAt)
    {
        $this->boughtAt = $boughtAt;
    }

    public function getLeasedUntil()
    {
        return $this->leasedUntil;
    }

    public function setLeasedUntil($leasedUntil)
    {
        $this->leasedUntil = $leasedUntil;
    }
}
