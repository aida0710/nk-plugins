<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyland\EconomyLand;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;

class AirBlock {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $world_search = mb_substr($event->getPlayer()->getWorld()->getDisplayName(), 0, null, 'utf-8');
        if (!(str_contains($world_search, "-c") || str_contains($world_search, "nature") || str_contains($world_search, "nether") || str_contains($world_search, "end") || str_contains($world_search, "MiningWorld") || str_contains($world_search, "debug"))) {
            $player->sendMessage("§bAirBlock §7>> §c使用可能なワールドは資源系ワールドと生活ワールドのみとなります");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $pos = $player->getPosition()->add(0, -1, 0);
        if ($player->getPosition()->getWorld()->getBlock($pos)->getId() != BlockLegacyIds::AIR) {
            $player->sendMessage("§bAirBlock §7>> §c下のブロックが空気ブロックの場合のみ設置できます");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (EconomyLand::getInstance()->posCheck($pos, $player) === false) {
            $player->sendMessage("§bAirBlock §7>> §c他人の土地には設置できません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($pos->getFloorY() <= 0) {
            $player->sendMessage("§bAirBlock §7>> §c0以下には設置できません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $heightLimit = WorldManagementAPI::getInstance()->getHeightLimit($event->getPlayer()->getWorld()->getFolderName());
        if ($pos->getFloorY() >= $heightLimit) {
            $event->getPlayer()->sendTip("§bProtect §7>> §c現在のワールドではY.{$heightLimit}以上でブロックを設置することは許可されていません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $player->getPosition()->getWorld()->setBlock($pos, $item->getBlock());
        SoundPacket::Send($player, "dig.stone");
    }
}
