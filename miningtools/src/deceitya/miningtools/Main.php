<?php

namespace deceitya\miningtools;

use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private static self $instance;
    public array $config;
    private array $flag = [];

    public static function getInstance(): Main {
        return self::$instance;
    }

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("debugMiningTools", [
            new DiamondMiningToolCommand(),
            new NetheriteMiningToolCommand(),
        ]);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->flag[$event->getPlayer()->getName()] = false;
    }

    /**
     * @ignoreCancelled
     * @priority LOW
     */
    public function block(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId();
        if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
            $player = $event->getPlayer();
            $name = $player->getName();
            $block = $event->getBlock();
            if (!$this->flag[$name]) {
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
                        return;
                }
            }
            //todo 斧は隣接してるブロックを連続破壊できる
            //todo シフトしてると自分より下のブロックが破壊されないように
            //todo 古いマイニングツールを変換する機能を追加
            $level_name = $event->getPlayer()->getWorld()->getDisplayName();
            $world = mb_substr($level_name, 0, null, 'utf-8');
            $startBlock = $block->getPosition()->getWorld()->getBlock($block->getPosition()->asVector3());
            if (str_contains($world, "nature") || str_contains($world, "nether") || str_contains($world, "end") || str_contains($world, "MiningWorld") || str_contains($world, "debug")) {
                if (in_array($block->getId(), $set['lump-id'], true)) {
                    $dropItems = null;
                    $name = $player->getName();
                    $this->flag[$name] = true;
                    for ($y = -1; $y < 2; $y++) {
                        for ($x = -1; $x < 2; $x++) {
                            for ($z = -1; $z < 2; $z++) {
                                $pos = $block->getPosition()->add($x, $y, $z);
                                $targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
                                if (!in_array($targetBlock->getId(), $set['nobreak-id'], true)) {
                                    $dropItems = array_merge($dropItems ?? [], $this->getDrop($player, $targetBlock));
                                    $block->getPosition()->getWorld()->setBlock($pos, VanillaBlocks::AIR());
                                }
                            }
                        }
                    }
                    $this->flag[$name] = false;
                    $this->DropItem($player, $event, $dropItems, $startBlock);
                }
            }
        }
    }

    /**
     * @return item[]
     */
    public function getDrop(Player $player, Block $block): array {
        $item = $player->getInventory()->getItemInHand();
        return $block->getDrops($item);
    }

    public function DropItem(Player $player, Event $event, $dropItems, $startBlock) {
        $dropItems = array_diff($dropItems, array($startBlock));
        $dropItems = array_values($dropItems);
        $dropItems = $player->getInventory()->addItem(...$dropItems);
        if (count($dropItems) === 0) {
            $event->setDropsVariadic(VanillaBlocks::AIR()->asItem());
        } else {
            $event->setDrops($dropItems);
        }
    }
}