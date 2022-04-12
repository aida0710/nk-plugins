<?php

namespace Deceitya\Gatya\Series;

use pocketmine\item\Item;

class Series {

    private int $id;
    private string $name;
    private int $cost;
    /** @var array[float,Item] */
    private array $items;
    private bool $ticket;
    private bool $eventTicket;

    /**
     * @param integer $id
     * @param string $name
     * @param int $cost
     * @param array[float,Item] $items
     * @param bool $ticket
     */
    public function __construct(int $id, string $name, int $cost, array $items, bool $ticket, bool $eventTicket) {
        $this->id = $id;
        $this->name = $name;
        $this->cost = $cost;
        $this->items = $items;
        $this->ticket = $ticket;
        $this->eventTicket = $eventTicket;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCost(): int {
        return $this->cost;
    }

    public function isTicket(): bool {
        return $this->ticket;
    }

    public function isEventTicket(): bool {
        return $this->eventTicket;
    }

    public function getChanceSum(): float {
        $sum = 0.0;
        foreach ($this->items as $data) {
            $sum += $data[0];
        }
        return round($sum, 1);
    }

    public function getItem(float $percent): ?Item {
        $prev = 0.0;
        foreach ($this->items as $item) {
            if ($prev < $percent && $percent <= $prev + $item[0]) {
                return $item[1];
            }
            $prev += $item[0];
        }
        return null;
    }
}
