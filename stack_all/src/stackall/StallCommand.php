<?php

namespace stackall;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use ree_jp\stackstorage\api\StackStorageAPI;

class StallCommand extends Command {

    private Config $config;

    public function __construct(Config $config) {
        parent::__construct("stall", "Inventory内のHotbar, Armorスロット以外の持ち物をストレージに収納する");
        $this->setPermission("stackall.command.stall");
        $this->config = $config;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("ゲーム内で使用してください");
            return;
        }
        $inventory = $sender->getInventory();
        $xuid = $sender->getXuid();
        $count = 0;
        $armor_count = 0;
        $inventoryconfig = $this->config->get("inventory", "yes");
        $armorconfigconfig = $this->config->get("armorinventory", "no");
        $hand = $this->config->get("hand", "yes");
        $hotbarSlot = $this->config->get("hotbarSlot", "no");
        $handitemindex = $inventory->getHeldItemIndex();
        $hotbarSlotSize = $inventory->getHotbarSize() - 1;
        if ($this->istrue($inventoryconfig)) {
            for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
                if ($this->istrue($hand) && $i === $handitemindex) {
                    continue;
                }
                if ($this->istrue($hotbarSlot) && $i >= 0 && $i <= $hotbarSlotSize) {
                    continue;
                }
                $item = clone $inventory->getItem($i);
                StackStorageAPI::$instance->add($xuid, $item);
                $inventory->clear($i);
                $count += $item->getCount();
            }
            $inventory->getContents();
        }
        if ($this->istrue($armorconfigconfig)) {
            $armorinventory = $sender->getArmorInventory();
            for ($i = 0, $size = $armorinventory->getSize(); $i < $size; ++$i) {
                $item = clone $armorinventory->getItem($i);
                StackStorageAPI::$instance->add($xuid, $item);
                $armorinventory->clear($i);
                $armor_count += $item->getCount();
            }
            $armorinventory->getContents();
        }
        $sender->sendMessage("§bStorage §7>> §a合計" . ($count + $armor_count) . "個のアイテムを仮想ストレージに収納しました");
    }

    /**
     * @param string|bool $value
     * @return bool
     */
    private function istrue($value): bool {
        return $value === "yes" || $value === true;
    }
}