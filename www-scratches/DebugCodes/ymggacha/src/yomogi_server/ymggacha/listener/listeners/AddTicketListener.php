<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\listener\listeners;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ymggacha\src\yomogi_server\ymggacha\item\YomogiGachaTicket;
use function mt_rand;

class AddTicketListener implements Listener {

    /**
     * @ignoreCancelled
     */
    public function onBlockBreak(BlockBreakEvent $ev): void {
        if (mt_rand(1, 650) !== 1) return;
        $this->addItem($ev->getPlayer(), (new YomogiGachaTicket())->init());
    }

    /**
     * @ignoreCancelled
     */
    public function onBlockPlace(BlockPlaceEvent $ev): void {
        if (mt_rand(1, 550) !== 1) return;
        $this->addItem($ev->getPlayer(), (new YomogiGachaTicket())->init());
    }

    private function addItem(Player $player, Item $item): void {
        $inv = $player->getInventory();
        $player->sendMessage(TextFormat::GREEN . TextFormat::BOLD . 'よもぎガチャチケットを獲得した！');
        if ($inv->canAddItem($item)) {
            $inv->addItem($item);
            return;
        }
        $loc = $player->getLocation();
        $loc->getWorld()->dropItem($loc, $item);
        $player->sendMessage(TextFormat::YELLOW . 'インベントリがいっぱいのため、チケットを落としてしまった！');
    }
}
