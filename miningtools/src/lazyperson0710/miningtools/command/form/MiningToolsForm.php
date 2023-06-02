<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\command\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\miningtools\element\SendFormButton;
use lazyperson0710\miningtools\element\SendFormLevelCheckButton;
use lazyperson0710\miningtools\EnablingSetting\SelectEnablingSettings;
use lazyperson0710\miningtools\extensions\ExtensionsMainForm;
use lazyperson0710\miningtools\normal\ConfirmForm;
use pocketmine\player\Player;

class MiningToolsForm extends SimpleForm {

    public const DiamondMiningToolsLevelLimit = 15;
    public const NetheriteMiningToolsLevelLimit = 30;
    public const ExpansionMiningToolsLevelLimit = 250;

    public function __construct(Player $player) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= self::DiamondMiningToolsLevelLimit) {//2
            $diamondLimit = '§a' . self::DiamondMiningToolsLevelLimit . 'レベルの為、開放済み';
        } else {
            $diamondLimit = '§c' . self::DiamondMiningToolsLevelLimit . 'レベル以上で開放されます';
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= self::NetheriteMiningToolsLevelLimit) {//3
            $netheriteLimit = '§a' . self::NetheriteMiningToolsLevelLimit . 'レベルの為、開放済み';
        } else {
            $netheriteLimit = '§c' . self::NetheriteMiningToolsLevelLimit . 'レベル以上で開放されます';
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) >= self::ExpansionMiningToolsLevelLimit) {//4
            $expansionLimit = '§a' . self::ExpansionMiningToolsLevelLimit . 'レベルの為、開放済み';
        } else {
            $expansionLimit = '§c' . self::ExpansionMiningToolsLevelLimit . 'レベル以上で開放されます';
        }
        $this
            ->setTitle('Mining Tools')
            ->setText('見たいコンテンツを選択してください')
            ->addElements(
                new SendFormLevelCheckButton(new ConfirmForm($player, 'diamond'), "Diamond Tools - 範囲採掘ツール\n{$diamondLimit}", self::DiamondMiningToolsLevelLimit),
                new SendFormLevelCheckButton(new ConfirmForm($player, 'netherite'), "Netherite Tools - 範囲採掘ツール\n{$netheriteLimit}", self::NetheriteMiningToolsLevelLimit),
                new SendFormLevelCheckButton(new ExtensionsMainForm($player), "アップグレード - Ex範囲採掘ツール\n{$expansionLimit}", self::ExpansionMiningToolsLevelLimit, true),
                new SendFormButton(new SelectEnablingSettings($player), '設定機能を有効化'),
            );
    }
}
