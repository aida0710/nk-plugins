<?php

namespace Deceitya\MyWarp\Form;

use Deceitya\MiningLevel\MiningLevelAPI;
use Deceitya\MyWarp\Database;
use Deceitya\MyWarp\MyWarpPlugin;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class MakingForm implements Form {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $cost = MyWarpPlugin::$cost->get($player->getName(), null) ?? 1500;
        $climit = MyWarpPlugin::$count->get($player->getName(), null);
        if ($climit === null) {
            $level = MiningLevelAPI::getInstance()->getLevel($player);
            if ($level >= 25 && $level < 50) {
                $climit = 10;
            } elseif ($level >= 50) {
                $climit = 15;
            } else {
                $climit = 5;
            }
        }
        if (count(Database::getInstance()->getWarpPositions($player)) < $climit) {
            foreach (Database::getInstance()->getWarpPositions($player) as $warp) {
                if ($warp['name'] === $data[0]) {
                    $player->sendMessage("§bMyWarp §7>> §c同じ名前のワープ地点があるため作成できませんでした");
                    return;
                }
            }
            if (EconomyAPI::getInstance()->reduceMoney($player, $cost) === EconomyAPI::RET_SUCCESS) {
                Database::getInstance()->createWarpPosition($player, $data[0]);
                $player->sendMessage("§bMyWarp §7>> §aワープ地点を作成しました");
            } else {
                $player->sendMessage("§bMyWarp §7>> §cお金が足りません");
            }
        } else {
            $player->sendMessage("§bMyWarp §7>> §cワープ地点は {$climit}個までしか作成できません");
        }
    }

    public function jsonSerialize() {
        $cost = MyWarpPlugin::$cost->get($this->player->getName(), null) ?? 1500;
        return [
            'type' => 'custom_form',
            'title' => 'MyWarp',
            'content' => [
                [
                    'type' => 'input',
                    'text' => "ワープ地点の名前を入力\n作成には{$cost}円かかります。\n\nワープ地点の作成制限は\n1レベル以上24レベル以下 : 5\n25レベル以上49レベル以下 : 10\n50レベル以上 : 15\nとなっております",
                    'placeholder' => '',
                    'default' => '',
                ],
            ],
        ];
    }
}
