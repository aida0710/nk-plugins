<?php

namespace deceitya\miningtools\tools\upgrade\expansion;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class ExpansionConfirmForm extends SimpleForm {

    public function __construct(Player $player) {
        $upgrade = null;
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion') !== null) {//MiningTools_Expansionがあるかどうか
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion")) {
                1 => "上位ツールにアップグレードしますか？\n\n費用は600万円\n範囲は7x7になります",
                2 => "最上位ツールにアップグレードしますか？\n\n費用は1500万円\n範囲は9x9になります",
                3 => "最上位ツールの為アップグレードできません",
                default => Server::getInstance()->broadcastMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました"),
            };
        } elseif ($namedTag->getTag('MiningTools_3') !== null) { //MiningTools_3があるかどうか
            $upgrade = "上位ツールにアップグレードしますか？\n\n費用は350万円\n範囲は5x5になります";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップグレード"));
    }

    public function handleClosed(Player $player): void {
        $player->sendMessage('§bMiningTools §7>> §aアップグレードをキャンセルしました');
    }

    public function handleSubmit(Player $player): void {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_3') !== null || ('MiningTools_Expansion') !== null) {
            $radius = 0;
            $user = $player->getName();
            $item = $player->getInventory()->getItemInHand();
            if ($item->getNamedTag()->getTag('MiningTools_Expansion') !== null) {
                $nbt = $item->getNamedTag();
                switch ($namedTag->getInt("MiningTools_Expansion")) {
                    case 1:
                        $price = 6000000;
                        $radius = 2;
                        $this->onReduceMoney($player, $price);
                        break;
                    case 2:
                        $price = 15000000;
                        $radius = 3;
                        $this->onReduceMoney($player, $price);
                        break;
                    default:
                        Server::getInstance()->broadcastMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
                $tag = "MiningTools_Expansion";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion', $radius);
                $item->setNamedTag($nbt);//$player->getInventory()->getItemInHand()->setCustomName()
                $itemName = match ($namedTag->getInt("MiningTools_Expansion")) {
                    1 => "§aNetheriteMiningPickaxe Ex.Secondary",
                    2 => "§aNetheriteMiningPickaxe Ex.Tertiary",
                    default => Server::getInstance()->broadcastMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました"),
                };
                $item->setCustomName($itemName);
                $player->getInventory()->setItemInHand($item);
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $radius = 1;
                $price = 3500000;
                if (!$this->onReduceMoney($player, $price)) return;
                $nbt = $item->getNamedTag();
                $tag = "MiningTools_3";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion', $radius);
                $item->setNamedTag($nbt);
                $item->setCustomName("§aNetheriteMiningPickaxe Ex.Primary");
                $player->getInventory()->setItemInHand($item);
            }
            Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$user}がNetheriteMiningToolsをEx.Rank{$radius}にアップグレードしました");
        } else {
            $player->sendMessage("§bMiningTools §7>> §cこのアイテムはアップグレードに対応していません");
        }
    }

    public function onReduceMoney(Player $player, $price): bool {
        if (EconomyAPI::getInstance()->myMoney($player) <= $price) {
            $player->sendMessage('§bMiningTools §7>> §cお金が足りません');
            return false;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $price);
        return true;
    }

}
