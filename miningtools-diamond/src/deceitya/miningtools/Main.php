<?php

namespace deceitya\miningtools;

use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use ree_jp\stackStorage\api\StackStorageAPI;

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
        $this->getServer()->getCommandMap()->registerAll("miningtools", [
            new DiamondMiningToolCommand(),
            new NetheriteMiningToolCommand(),
        ]);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->flag[$event->getPlayer()->getName()] = false;
    }

    /**
     * @ignoreCancelled
     * @priority HIGH
     */
    public function onBreak(BlockBreakEvent $event) {
        $item = $event->getItem();
        $id = $item->getId();
        if (($id === ItemIds::DIAMOND_SHOVEL || $id === ItemIds::DIAMOND_PICKAXE || $id === ItemIds::DIAMOND_AXE || $id === 744 || $id === 745 || $id === 746) && $item->getNamedTag()->getTag('4mining') !== null) {
            $player = $event->getPlayer();
            $name = $player->getName();
            if (!$this->flag[$name]) {
                $block = $event->getBlock();
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
                if (in_array($block->getId(), $set['lump-id'])) {
                    $this->flag[$name] = true;
                    for ($y = -$item->getNamedTag()->getInt('4mining'); $y < 1; $y++) {
                        for ($x = -1; $x < 2; $x++) {
                            for ($z = -1; $z < 2; $z++) {
                                $pos = $block->getPosition()->add($x, $y, $z);
                                if (!in_array($block->getPosition()->getWorld()->getBlock($pos)->getId(), $set['nobreak-id'])) {
                                    $block->getPosition()->getWorld()->useBreakOn($pos, $item, $player);
                                }
                            }
                        }
                    }
                    $this->flag[$name] = false;
                    foreach ($event->getDrops() as $drop) {
                        StackStorageAPI::$instance->add($player->getXuid(), $drop);
                        $event->setDrops([]);
                    }
                }
            }
        }
    }
}
