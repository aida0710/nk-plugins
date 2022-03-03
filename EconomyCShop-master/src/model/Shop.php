<?php declare(strict_types=1);
namespace famima65536\EconomyCShop\model;

/**
 * use world + sign as ShopId.
 */
class Shop {

    public function __construct(
        private string      $owner,
        private string      $world,
        private Product     $product,
        private Coordinate  $sign,
        private Coordinate  $mainChest,
        private ?Coordinate $subChest = null
    ) {
    }

    /**
     * @return string
     */
    public function getOwner(): string {
        return $this->owner;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product {
        return $this->product;
    }

    /**
     * @return Coordinate
     */
    public function getSign(): Coordinate {
        return $this->sign;
    }

    /**
     * @return Coordinate
     */
    public function getMainChest(): Coordinate {
        return $this->mainChest;
    }

    /**
     * @return Coordinate|null
     */
    public function getSubChest(): ?Coordinate {
        return $this->subChest;
    }

    public function getId(): string {
        return "{$this->getWorld()}+{$this->sign->getX()}:{$this->sign->getY()}:{$this->sign->getZ()}";
    }

    /**
     * @return string
     */
    public function getWorld(): string {
        return $this->world;
    }

}