<?php

namespace deceitya\miningtools\tools\netherite\expansion;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;

class ExpansionToolForm extends SimpleForm {

    public function __construct(Player $player) {
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion') !== null) {//MiningTools_Expansionがあるかどうか
            switch ($namedTag->getInt("MiningTools_Expansion")) {
                case  1:
                    $upgrade = "上位ツールにアップグレードしますか？\n費用は600万円\n範囲は7x7になります\n\n残りアップグレード回数 2 回";
                    break;
                case 2:
                    $upgrade = "最上位ツールにアップグレードしますか？\n費用は1500万円\n範囲は9x9になります\n\n残りアップグレード回数 1 回";
                    break;
            }
        } elseif ($namedTag->getTag('MiningTools_3') !== null) { //MiningTools_3があるかどうか
            $upgrade = "上位ツールにアップグレードしますか？\n費用は350万円\n範囲は5x5になります\n\n残りアップグレード回数 3 回";
        } elseif ($namedTag->getTag('4mining') !== null) {//4miningがあるかどうか
            $upgrade = "一度ブロックを採掘してつるはしを変換してください";
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップデートする", null));
    }
    public function handleSubmit(Player $player): void {
        $player->sendForm(new ExpansionConfirmForm($player));

    }
}
