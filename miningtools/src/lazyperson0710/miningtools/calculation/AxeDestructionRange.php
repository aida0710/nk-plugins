<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\calculation;

use lazyperson0710\miningtools\event\MiningToolsBreakEvent;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Durable;
use pocketmine\math\Facing;
use pocketmine\player\Player;
use pocketmine\world\World;
use RuntimeException;
use function array_key_first;
use function array_merge;
use function count;
use function in_array;

class AxeDestructionRange {

    public function breakTree(Block $block, Player $player) : array {
        $world = $player->getWorld();
        $startPos = $block->getPosition()->asVector3();
        $handItem = $player->getInventory()->getItemInHand();
        $haveDurable = $handItem instanceof Durable;
        $open = [World::blockHash($startPos->x, $startPos->y, $startPos->z) => $startPos];
        $close = [];
        $drops = [];
        $item = $player->getInventory()->getItemInHand();
        if (!$item instanceof Durable) throw new RuntimeException('指定されたクラスはDurableではありません');
        //350(回)*6(方向) = 2100(ブロック(概算))
        for ($i = 1; $i <= 350; $i++) {
            if (count($open) === 0) {
                break;
            }
            $key = array_key_first($open);
            $now = $open[$key];
            $close[$key] = null;
            unset($open[$key]);
            foreach (Facing::ALL as $side) {
                $pos = $now->getSide($side);
                $hash = World::blockHash($pos->x, $pos->y, $pos->z);
                if (isset($open[$hash]) || isset($close[$hash])) continue;
                $targetBlock = $world->getBlock($pos);
                $getBlock = [
                    BlockLegacyIds::LOG,
                    BlockLegacyIds::LOG2,
                    BlockLegacyIds::LEAVES,
                    BlockLegacyIds::LEAVES2,
                ];
                if (!in_array($targetBlock->getId(), $getBlock, true)) {
                    $close[$hash] = null;
                    continue;
                }
                if (!(new PickaxeDestructionRange())->MiningToolsWarningSetting($player, $item, $haveDurable, $targetBlock)) break 2;
                if ($haveDurable) {
                    (new PickaxeDestructionRange())->toolDamageProcessing($player, $item);
                }
                $drops[] = (new ItemDrop())->getDrop($player, $targetBlock);
                (new MiningToolsBreakEvent($player, $targetBlock))->call();
                $world->setBlock($pos, VanillaBlocks::AIR());
                $open[$hash] = $pos;
            }
        }
        return array_merge(...$drops);
    }
}
