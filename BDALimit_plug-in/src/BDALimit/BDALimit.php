<?php

namespace BDALimit;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class BDALimit extends PluginBase implements Listener {

    private Config $breakProtectConfig;
    private Config $breakBlockConfig;
    private Config $placeProtectConfig;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->breakProtectConfig = new Config($this->getDataFolder() . "BreakProtect.yml", Config::YAML, [
            "resource" => "51",
            "pvp" => "0",
            "生物市-c" => "55",
            "浜松市-f" => "20",
            "nature-1" => "155",
            "nature-2" => "155",
            "java-1" => "205",
        ]);
        $this->placeProtectConfig = new Config($this->getDataFolder() . "PlaceProtect.yml", Config::YAML, [
            "resource" => "0",
            "pvp" => "0",
            "生物市-c" => "50",
            "浜松市-f" => "20",
            "nature-1" => "150",
            "nature-2" => "150",
            "java-1" => "200",
        ]);
        $this->breakBlockConfig = new Config($this->getDataFolder() . "BreakBlock.yml", Config::YAML, [
            "resource" => [
                "1:0",
                "1:1",
                "1:3",
                "1:5",
                "17:0",
                "17:1",
                "17:2",
                "17:3",
                "162:0",
                "162:1",
                "-10:0",
                "-9:0",
                "-8:0",
                "-7:0",
                "-6:0",
                "-5:0",
            ],
        ]);
    }

    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $world = $player->getPosition()->getWorld();
        $worldName = $world->getFolderName();
        $block = $event->getBlock();
        $id = (string)$block->getId();
        $meta = (string)$block->getMeta();
        $y = $block->getPosition()->getY();
        if (!Server::getInstance()->isOp($name)) {
            foreach ($this->breakBlockConfig->getAll() as $b => $a) {
                if ($worldName === $b) {
                    $data = $id . ":" . $meta;
                    if (!in_array($data, $this->breakBlockConfig->get($b))) {
                        $event->cancel();
                        $player->sendTip("§bProtect §7>> §c現在いるワールドでそのブロックを破壊することはできません");
                    }
                }
            }
            foreach ($this->breakProtectConfig->getAll() as $b => $a) {
                if ($worldName === $b) {
                    if ($y >= $a) {//00以上を破壊した場合out
                        $event->cancel();
                        $player->sendtip("§bProtect §7>> §cブロックの破壊が無効になりました");
                    }
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $name = $event->getPlayer()->getName();
        $world = $player->getPosition()->getWorld();
        $worldName = $world->getFolderName();
        $block = $event->getBlockAgainst();
        $y = $block->getPosition()->getY();
        if (!Server::getInstance()->isOp($name)) {
            if ($worldName === "resource") {
                $event->cancel();
                $player->sendTip("§bResource §7>> §c人工資源ワールドではブロックの設置はできません");
            }
            foreach ($this->placeProtectConfig->getAll() as $b => $a) {
                if ($worldName === $b) {
                    if ($y >= $a) {
                        $event->cancel();
                        $player->sendtip("§bProtect §7>> §cブロックの設置が無効になりました");
                    }
                }
            }
        }
    }
}