<?php

namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class RangeConfirmForm extends SimpleForm {

    private array $nbt = [];

    public const Rank1_MoneyCost = 800000;
    public const Rank2_MoneyCost = 6000000;
    public const Rank3_MoneyCost = 15000000;

    public const Rank1_ItemCost = 1;
    public const Rank2_ItemCost = 5;
    public const Rank3_ItemCost = 15;

    public const CostItemId = -302;
    public const CostItemNBT = "MiningToolsRangeCostItem";

    public function __construct(Player $player) {
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
            $this->nbt = ["MiningTools_Expansion_Range" => $namedTag->getInt("MiningTools_Expansion_Range")];
            $upgrade = match ($namedTag->getInt('MiningTools_Expansion_Range')) {
                1 => "上位ツールにアップグレードしますか？\n費用は600万円\n範囲は7x7になります\n\n残りアップグレード回数 2 回",
                2 => "最上位ツールにアップグレードしますか？\n費用は1500万円\n範囲は9x9になります\n\n残りアップグレード回数 1 回",
                3 => "最上位ツールの為アップグレードに対応していません",
                default => "Errorが発生しました",
            };
        } elseif ($namedTag->getTag('MiningTools_3') !== null) {
            $this->nbt = ["MiningTools_3" => $namedTag->getInt("MiningTools_3")];
            $upgrade = "上位ツールにアップグレードしますか？\n費用は80万円\n範囲は5x5になります\n\n残りアップグレード回数 3 回";
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップデートする", null));
    }

    public function handleSubmit(Player $player): void {
        if (empty($this->nbt)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_Range') === 3) {
                return;
            }
        }
        $player->sendForm(new RangeBuyForm($player, $this->nbt));
    }
}
