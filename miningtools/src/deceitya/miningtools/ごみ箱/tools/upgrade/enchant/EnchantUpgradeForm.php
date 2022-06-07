<?php

namespace deceitya\miningtools\tools\upgrade\expansion;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;

class EnchantUpgradeForm extends SimpleForm {

    public function __construct(Player $player) {
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag("MiningTools_MendingEnchant")) {//修繕max1
            switch ($namedTag->getInt("MiningTools_MendingEnchant")) {
                case 1:
                    $player->sendMessage("last level");
                    break;
                default:
                    $player->sendMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                    return;
            }
        }
        if ($namedTag->getTag("MiningTools_EfficiencyEnchant")) {//効率max3
            switch ($namedTag->getInt("MiningTools_EfficiencyEnchant")) {
                case 1:
                    $player->sendMessage("1");
                    break;
                case 2:
                    $player->sendMessage("2");
                    break;
                case 3:
                    $player->sendMessage("last level");
                    break;
                default:
                    $player->sendMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                    return;
            }
        }
        if ($namedTag->getTag("MiningTools_DurabilityEnchant")) {//耐久max5/5だと∞
            switch ($namedTag->getInt("MiningTools_DurabilityEnchant")) {
                case 1:
                    $player->sendMessage("1");
                    break;
                case 2:
                    $player->sendMessage("2");
                    break;
                case 3:
                    $player->sendMessage("3");
                    break;
                case 4:
                    $player->sendMessage("4");
                    break;
                case 5:
                    $player->sendMessage("last level");
                    break;
                default:
                    $player->sendMessage(__DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                    return;
            }
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップデートする", null));
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EnchantConfirmForm($player));
    }
}
