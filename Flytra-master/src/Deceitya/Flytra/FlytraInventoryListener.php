<?php

namespace Deceitya\Flytra;

use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryListener;
use pocketmine\item\Item;
use pocketmine\player\Player;

class FlytraInventoryListener implements InventoryListener {

    private FlytraPlugin $plugin;

    public function __construct(FlytraPlugin $plugin) {
        $this->plugin = $plugin;
    }

    public function onSlotChange(Inventory $inventory, int $slot, Item $oldItem): void {
        if ($inventory instanceof ArmorInventory && $slot === ArmorInventory::SLOT_CHEST) {
            $holder = $inventory->getHolder();
            if ($holder instanceof Player) {
                $this->plugin->checkFly($holder, $holder->getWorld(), $inventory->getChestplate());
            }
        }
    }

    public function onContentChange(Inventory $inventory, array $oldContents): void {
    }
}