<?php

namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class RangeBuyForm extends SimpleForm {

    private array $nbt;

    public function __construct(Player $player, array $nbt) {
        $this->nbt = $nbt;
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion_Range")) {
                1 => "上位ツールにアップグレードしますか？\n\n費用は600万円\n範囲は7x7になります",
                2 => "最上位ツールにアップグレードしますか？\n\n費用は1500万円\n範囲は9x9になります",
            };
        } elseif ($namedTag->getTag('MiningTools_3') !== null) {
            $upgrade = "上位ツールにアップグレードしますか？\n\n費用は350万円\n範囲は5x5になります";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップグレード"));
    }

    public function handleSubmit(Player $player): void {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        $radius = 0;
        $item = $player->getInventory()->getItemInHand();
        if (array_key_exists("MiningTools_Expansion_Range", $this->nbt)) {
            if ($this->nbt["MiningTools_Expansion_Range"] !== $namedTag->getInt("MiningTools_Expansion_Range")) {
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
                $nbt = $item->getNamedTag();
                switch ($namedTag->getInt("MiningTools_Expansion_Range")) {
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
                        Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
                $tag = "MiningTools_Expansion_Range";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion_Range', $radius);
                $item->setNamedTag($nbt);
                $itemName = match ($namedTag->getInt("MiningTools_Expansion_Range")) {
                    1 => "§aNetheriteMiningPickaxe Ex.Secondary",
                    2 => "§aNetheriteMiningPickaxe Ex.Tertiary",
                };
                $item->setCustomName($itemName);
                $player->getInventory()->setItemInHand($item);
            }
        }
        if (array_key_exists("MiningTools_3", $this->nbt)) {
            if ($this->nbt["MiningTools_3"] !== $namedTag->getInt("MiningTools_3")) {
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $radius = 1;
                $price = 3500000;
                if (!$this->onReduceMoney($player, $price)) return;
                $nbt = $item->getNamedTag();
                $tag = "MiningTools_3";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion_Range', $radius);
                $item->setNamedTag($nbt);
                $item->setCustomName("§aNetheriteMiningPickaxe Ex.Primary");
                $player->getInventory()->setItemInHand($item);
            }
        }
        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($item));
        $player->getInventory()->setItemInHand($item);
        Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$player->getName()}がNetheriteMiningToolsをEx.Rank{$radius}にアップグレードしました");
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
