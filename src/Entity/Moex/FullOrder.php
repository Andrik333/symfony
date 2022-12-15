<?php

namespace App\Entity\Moex;

use App\Repository\Moex\FullOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FullOrderRepository::class)
 */
class FullOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $sec_code;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $buysell;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $time;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_no;

    /**
     * @ORM\Column(type="integer")
     */
    private $action;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $volume;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $trade_no;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $trade_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNo(): ?int
    {
        return $this->no;
    }

    public function setNo(int $no): self
    {
        $this->no = $no;

        return $this;
    }

    public function getSecCode(): ?string
    {
        return $this->sec_code;
    }

    public function setSecCode(string $sec_code): self
    {
        $this->sec_code = $sec_code;

        return $this;
    }

    public function getBuysell(): ?string
    {
        return $this->buysell;
    }

    public function setBuysell(string $buysell): self
    {
        $this->buysell = $buysell;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getOrderNo(): ?int
    {
        return $this->order_no;
    }

    public function setOrderNo(int $order_no): self
    {
        $this->order_no = $order_no;

        return $this;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getTradeNo(): ?int
    {
        return $this->trade_no;
    }

    public function setTradeNo(?int $trade_no): self
    {
        $this->trade_no = $trade_no;

        return $this;
    }

    public function getTradePrice(): ?float
    {
        return $this->trade_price;
    }

    public function setTradePrice(?float $trade_price): self
    {
        $this->trade_price = $trade_price;

        return $this;
    }
}
