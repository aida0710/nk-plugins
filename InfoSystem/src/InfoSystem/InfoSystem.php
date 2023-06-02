<?php

declare(strict_types = 0);

namespace InfoSystem;

use Deceitya\MiningLevel\MiningLevelAPI;
use InfoSystem\command\TagCommand;
use InfoSystem\task\ChangeNameTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use function file_exists;
use function in_array;
use function mkdir;
use function strtolower;
use function strtoupper;
use function substr;

class InfoSystem extends PluginBase implements Listener {

    public const owner = [
        'lazyperson710',
        'sloth0710',
    ];
    private static InfoSystem $instance;
    public array $data;
    private Config $config;

    public function onEnable() : void {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML, ['デフォルト称号' => '§b鯖民']);
        $this->getServer()->getCommandMap()->registerAll('infoSystem', [
            new TagCommand(),
        ]);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());
        $folder = $this->getFolder($name);
        $this->data[$name] = new Config($folder, Config::JSON, [
            'tag' => $this->config->get('デフォルト称号'),
        ]);
        $this->data[$name]->save();
        $this->getScheduler()->scheduleDelayedTask(new ChangeNameTask([$player]), 10);
    }

    public function getFolder($name) : string {
        $sub = substr($name, 0, 1);
        $upper = strtoupper($sub);
        $folder = $this->getDataFolder() . $upper . '/';
        if (!file_exists($folder)) mkdir($folder);
        $lower = strtolower($name);
        return $folder .= $lower . '.json';
    }

    public function ChangeTag(Player $player) {
        $name = strtolower($player->getName());
        $tag = $this->data[$name]->get('tag');
        $name = $player->getName();
        $level = MiningLevelAPI::getInstance()->getLevel($player);
        if (in_array($player->getName(), self::owner, true)) {
            if (Server::getInstance()->isOp($player->getName())) {
                $player->setDisplayName('§eOwner§a-§e' . $tag . '§r§a-§f' . $name . '§r§a');
                $player->setNameTag('§eOwner§a-§e' . $tag . '§r§a-§f' . $name . '§r§a');
            } else {
                $player->setDisplayName('§eOwner§a-§eLv.' . $level . '§a-§e' . $tag . '§r§a-§f' . $name . '§r§f');
                $player->setNameTag('§eOwner§a-§eLv.' . $level . '§a-§e' . $tag . '§r§a-§f' . $name . '§r§f');
            }
            return;
        }
        if (Server::getInstance()->isOp($player->getName())) {
            $player->setDisplayName('§eStaff§a-§e' . $tag . '§r§a-§f' . $name . '§r§f');
            $player->setNameTag('§eStaff§a-§e' . $tag . '§r§a-§f' . $name . '§r§f');
        } else {
            $player->setDisplayName('§eLv.' . $level . '§a-§e' . $tag . '§r§a-§r§f' . $name);
            $player->setNameTag('§eLv.' . $level . '§a-§e' . $tag . '§r§a-§r§f' . $name);
        }
    }

    public static function getInstance() : InfoSystem {
        return self::$instance;
    }

}
