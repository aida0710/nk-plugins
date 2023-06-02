<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyland\EconomyLand;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use function mb_substr;
use function str_contains;

class AirBlock {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
        $event->cancel();
        $player = $event->getPlayer();
        $world_search = mb_substr($event->getPlayer()->getWorld()->getDisplayName(), 0, null, 'utf-8');
        if (!(str_contains($world_search, '-c') || str_contains($world_search, 'nature') || str_contains($world_search, 'nether') || str_contains($world_search, 'end') || str_contains($world_search, 'MiningWorld') || str_contains($world_search, 'debug'))) {
            SendMessage::Send($player, '使用可能なワールドは資源系ワールドと生活ワールドのみとなります', 'AirBlock', false);
            return;
        }
        $pos = $player->getPosition()->add(0, -1, 0);
        if ($player->getPosition()->getWorld()->getBlock($pos)->getId() != BlockLegacyIds::AIR) {
            SendMessage::Send($player, '下のブロックが空気ブロックの場合のみ設置できます', 'AirBlock', false);
            return;
        }
        if (EconomyLand::getInstance()->posCheck($pos, $player) === false) {
            SendMessage::Send($player, '他人の土地には設置できません', 'AirBlock', false);
            return;
        }
        if ($pos->getFloorY() <= 0) {
            SendMessage::Send($player, '0以下には設置できません', 'AirBlock', false);
            return;
        }
        $heightLimit = WorldManagementAPI::getInstance()->getHeightLimit($event->getPlayer()->getWorld()->getFolderName());
        if ($pos->getFloorY() >= $heightLimit) {
            SendTip::Send($event->getPlayer(), "現在のワールドではY.{$heightLimit}以上でブロックを設置することは許可されていません", 'Protect', true);
            return;
        }
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $player->getPosition()->getWorld()->setBlock($pos, $item->getBlock());
        SoundPacket::Send($player, 'dig.stone');
    }
}
