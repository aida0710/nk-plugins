<?php

namespace deceitya\debugMiningTools;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
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
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で使用してください。');
            return true;
        }
        $sender->sendForm(new ToolForm());
        return true;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->flag[$event->getPlayer()->getName()] = false;
    }

    /**
     * @ignoreCancelled
     * @priority HIGH
     */
    public function block(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $vector3 = $event->getBlock()->getPosition()->asVector3();
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId();
        if ($item->getNamedTag()->getTag('MiningTools_Debug') !== null) {
            $player = $event->getPlayer();
            $name = $player->getName();
            if (!$this->flag[$name]) {
                $block = $event->getBlock();
                switch ($id) {
                    case 745:
                        $set = $this->config['pickaxe'];
                        break;
                    default:
                        return;
                }
            }
            if (in_array($block->getId(), $set['lump-id'], true)) {
                $dropItems = null;
                $targetBlock = null;
                $name = $player->getName();
                $this->flag[$name] = true;
                for ($y = -1; $y < 2; $y++) {
                    for ($x = -1; $x < 2; $x++) {
                        for ($z = -1; $z < 2; $z++) {
                            $pos = $block->getPosition()->add($x, $y, $z);
                            $targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
                            if (!in_array($targetBlock->getId(), $set['nobreak-id'], true)) {
                                $dropItems = array_merge($dropItems ?? [], $this->getDrop($player, $targetBlock));//$this->getDrop($player, $block, $dropItems);
                                //$block->getPosition()->getWorld()->useBreakOn($pos, $item, $player);
                                $block->getPosition()->getWorld()->setBlock($pos, VanillaBlocks::AIR());
                            }
                        }
                    }
                }
                //アイテム追加処理
                $dropItems = $player->getInventory()->addItem(...$dropItems);
                $this->flag[$name] = false;
                if (count($dropItems) === 0) {
                    $event->setDropsVariadic(VanillaBlocks::AIR()->asItem());
                } else {
                    $event->setDrops($dropItems);
                }
            }
        }
    }

    /**
     * @return item[]
     */
    public function getDrop(Player $player, Block $block): array {//, &$dropItems
        $item = $player->getInventory()->getItemInHand();
        return $block->getDrops($item);
    }
}