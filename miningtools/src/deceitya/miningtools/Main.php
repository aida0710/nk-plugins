<?php

namespace deceitya\miningtools;

use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\ExpansionMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use deceitya\miningtools\event\CountBlockEvent;
use onebone\economyland\EconomyLand;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Facing;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\world\World;

class Main extends PluginBase implements Listener {

    private static self $instance;
    public array $config;
    private array $flag = [];

    public function onJoin(PlayerJoinEvent $event) {
        $this->flag[$event->getPlayer()->getName()] = false;
    }

    /**
     * @ignoreCancelled
     * @priority LOW
     */
    //todo 優先度要検証
    public function block(BlockBreakEvent $event): void {
        if ($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId();
        $player_y = $event->getPlayer()->getPosition()->getFloorY();
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            $nbt = $item->getNamedTag();
            $tag = "4mining";
            $nbt->removeTag($tag);
            $nbt->setInt('MiningTools_3', 1);
            $item->setNamedTag($nbt);
            $player->getInventory()->setItemInHand($item);
            $player->sendMessage("§bMiningTool §7>> §a所持しているマイニングツールの変換に成功しました");
        }
        if (!($item->getNamedTag()->getTag('MiningTools_3') !== null || $item->getNamedTag()->getTag('MiningTools_Expansion') !== null)) return;
        $player = $event->getPlayer();
        $name = $player->getName();
        $block = $event->getBlock();
        if (!$this->flag[$name]) {
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                switch ($id) {
                    case ItemIds::DIAMOND_SHOVEL:
                        $set = $this->config['diamond_shovel'];
                        break;
                    case ItemIds::DIAMOND_PICKAXE:
                        $set = $this->config['diamond_pickaxe'];
                        break;
                    case ItemIds::DIAMOND_AXE:
                        $set = $this->config['diamond_axe'];
                        break;
                    case 744:
                        $set = $this->config['netherite_shovel'];
                        break;
                    case 745:
                        $set = $this->config['netherite_pickaxe'];
                        break;
                    case 746:
                        $set = $this->config['netherite_axe'];
                        break;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion') !== null) {
                switch ($item->getNamedTag()->getInt("MiningTools_Expansion")) {
                    case 1:
                        switch ($id) {
                            case 744:
                                $set = $this->config['expansion_shovel'];
                                break 2;
                            case 745:
                                $set = $this->config['expansion_pickaxe'];
                                break 2;
                            case 746:
                                $set = $this->config['expansion_axe'];
                                break 2;
                        }
                        break;
                    case 2:
                    case 3:
                        switch ($id) {
                            case 744:
                                $set = $this->config['ex_expansion_shovel'];
                                break 2;
                            case 745:
                                $set = $this->config['ex_expansion_pickaxe'];
                                break 2;
                            case 746:
                                $set = $this->config['ex_expansion_axe'];
                                break 2;
                        }
                        break;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
            }
            if (!isset($set)) {
                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
            }
            $world_name = $event->getPlayer()->getWorld()->getDisplayName();
            $world_search = mb_substr($world_name, 0, null, 'utf-8');
            $startBlock = $block->getPosition()->getWorld()->getBlock($block->getPosition()->asVector3());
            $dropItems = null;
            $blockIds = [];
            if (!(str_contains($world_search, "-c") || str_contains($world_search, "nature") || str_contains($world_search, "nether") || str_contains($world_search, "end") || str_contains($world_search, "MiningWorld") || str_contains($world_search, "debug") || Server::getInstance()->isOp($name))) return;
            $handItem = $player->getInventory()->getItemInHand();
            $haveDurable = $handItem instanceof Durable;
            $maxDurability = $haveDurable ? $handItem->getMaxDurability() : null;
            if ($haveDurable && $handItem->getDamage() >= $maxDurability - 15) {
                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                return;
            }
            if ($item->getId() === 279 || $item->getId() === 746) {
                $dropItems = [];
                $this->breakTree($startBlock, $set, $player, $event, $startBlock, $dropItems);
                $this->DropItem($player, $event, $dropItems, $startBlock);
                return;
            }
            if (!in_array($block->getId(), $set['lump-id'], true)) return;
            $name = $player->getName();
            $radius = 0;
            if ($item->getNamedTag()->getTag('MiningTools_Expansion') !== null) {
                $nbt = $item->getNamedTag();
                $radius = $nbt->getInt("MiningTools_Expansion");
            }
            $this->flag[$name] = true;
            for ($y = -1 - $radius; $y < 2 + $radius; $y++) {
                for ($x = -1 - $radius; $x < 2 + $radius; $x++) {
                    for ($z = -1 - $radius; $z < 2 + $radius; $z++) {
                        $pos = $block->getPosition()->add($x, $y, $z);
                        $targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
                        if ($targetBlock->getPosition()->getFloorY() <= 0) continue;
                        if (in_array($targetBlock->getId(), $set['nobreak-id'], true)) continue;
                        if (EconomyLand::getInstance()->posCheck($pos, $player) === false) continue;
                        switch ($targetBlock->getId()) {
                            case ItemIds::AIR:
                            case ItemIds::BEDROCK:
                            case ItemIds::BARRIER:
                            case ItemIds::WATER:
                            case ItemIds::FLOWING_WATER:
                            case ItemIds::WATER_LILY:
                            case ItemIds::MAGMA:
                                break;
                        }
                        if (!$player->isSneaking()) {
                            $dropItems = array_merge($dropItems ?? [], $this->getDrop($player, $targetBlock));
                            $blockIds[] = $targetBlock->getId();
                            if ($haveDurable) {
                                if ($player->getGamemode() !== GameMode::CREATIVE()){
                                    /** @var Durable $handItem */
                                    $handItem->applyDamage(1);
                                    $player->getInventory()->setItemInHand($handItem);
                                }
                                if ($handItem->getDamage() >= $maxDurability - 15) {
                                    $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                    break 3;
                                }
                            }
                            (new CountBlockEvent($player, $block))->call();
                            $block->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        } elseif ($pos->getFloorY() <= $player_y - 1) {
                            continue;
                        } else {
                            $dropItems = array_merge($dropItems ?? [], $this->getDrop($player, $targetBlock));
                            $blockIds[] = $targetBlock->getId();
                            if ($haveDurable) {
                                if ($player->getGamemode() !== GameMode::CREATIVE()){
                                    /** @var Durable $handItem */
                                    $handItem->applyDamage(1);
                                    $player->getInventory()->setItemInHand($handItem);
                                }
                                if ($handItem->getDamage() >= $maxDurability - 15) {
                                    $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                    break 3;
                                }
                                (new CountBlockEvent($player, $block))->call();
                                $block->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                            }
                        }
                    }
                }
            }
            $this->flag[$name] = false;
            $this->DropItem($player, $event, $dropItems, $startBlock);
        }
    }

    public static function getInstance(): Main {
        return self::$instance;
    }

    /**
     * @param Block $block
     * @param $set
     * @param Player $player
     * @param Event $event
     * @param Block $startBlock
     * @param Item[] $dropItems
     * @return void
     */
    public function breakTree(Block $block, $set, Player $player, Event $event, Block $startBlock, array &$dropItems): void {
        $world = $player->getWorld();
        $startPos = $block->getPosition()->asVector3();
        $handItem = $player->getInventory()->getItemInHand();
        $haveDurable = $handItem instanceof Durable;
        $maxDurability = $haveDurable ? $handItem->getMaxDurability() : null;
        $open = [World::blockHash($startPos->x, $startPos->y, $startPos->z) => $startPos];
        $close = [];
        $drops = [];
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
                $targetblock = $world->getBlock($pos);
                if ($targetblock->getId() !== BlockLegacyIds::LOG && $targetblock->getId() !== BlockLegacyIds::LOG2 && $targetblock->getId() !== BlockLegacyIds::LEAVES && $targetblock->getId() !== BlockLegacyIds::LEAVES2) {
                    $close[$hash] = null;
                    continue;
                }
                if ($haveDurable) {
                    if ($player->getGamemode() !== GameMode::CREATIVE()){
                        /** @var Durable $handItem */
                        $handItem->applyDamage(1);
                        $player->getInventory()->setItemInHand($handItem);
                    }
                    if ($handItem->getDamage() >= $maxDurability - 15) {
                        $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                        break 2;
                    }
                }
                $drops[] = $this->getDrop($player, $targetblock);
                (new CountBlockEvent($player, $targetblock))->call();
                $world->setBlock($pos, VanillaBlocks::AIR());
                $open[$hash] = $pos;
            }
        }
        $dropItems = array_merge($dropItems, array_merge(...$drops));
    }

    /**
     * @return item[]
     */
    public function getDrop(Player $player, Block $block): array {
        $item = $player->getInventory()->getItemInHand();
        return $block->getDrops($item);
    }

    public function DropItem(Player $player, Event $event, $dropItems, $startBlock) {
        if (is_null($dropItems)) {
            return;
        }
        $dropItems = array_diff($dropItems, array($startBlock));
        $dropItems = array_values($dropItems);
        $dropItems = $player->getInventory()->addItem(...$dropItems);
        if (count($dropItems) === 0) {
            $event->setDropsVariadic(VanillaBlocks::AIR()->asItem());
        } else {
            $event->setDrops($dropItems);
        }
    }

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("MiningTools", [
            new DiamondMiningToolCommand(),
            new NetheriteMiningToolCommand(),
            new ExpansionMiningToolCommand(),
        ]);
    }
}
