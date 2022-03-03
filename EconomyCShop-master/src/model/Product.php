<?php declare(strict_types=1);
namespace famima65536\EconomyCShop\model;

use famima65536\EconomyCShop\model\exception\InvalidProductException;
use pocketmine\item\Item;

class Product {

    /**
     * @param Item $item
     * @param int $price
     * @throws InvalidProductException when given invalid product information.
     */
    public function __construct(
        private Item $item,
        private int  $price
    ) {
        if ($this->price < 0) {
            throw new InvalidProductException("Price should be equal or greater than zero", InvalidProductException::INVALID_PRICE);
        }
        if ($this->item->getCount() <= 0) {
            throw new InvalidProductException("Count should be greater than zero.", InvalidProductException::INVALID_AMOUNT);
        }
    }

    /**
     * @return Item
     */
    public function getItem(): Item {
        return clone $this->item;
    }

    /**
     * @return int
     */
    public function getPrice(): int {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getAmount(): int {
        return $this->item->getCount();
    }
}