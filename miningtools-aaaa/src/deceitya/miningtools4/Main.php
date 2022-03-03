<?php

namespace deceitya\miningtools4;

use deceitya\miningtools4\form4\ToolForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
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
    public function onBreak(BlockBreakEvent $event) {
        $item = $event->getItem();
        $id = $item->getId();
        if (($id === 745) && $item->getNamedTag()->getTag('5mining') !== null) {
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
                $this->flag[$name] = true;
                for ($y = -$item->getNamedTag()->getInt('5mining'); $y < 1; $y++) {
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