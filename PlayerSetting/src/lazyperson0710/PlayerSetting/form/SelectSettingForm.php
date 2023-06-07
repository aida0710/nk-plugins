<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\PlayerSetting\form\element\SendMiningToolsSettingFormButton;
use lazyperson0710\PlayerSetting\form\temp\NormalSettingListForm;
use lazyperson0710\PlayerSetting\form\temp\SendNormalSettingFormButton;
use pocketmine\player\Player;

class SelectSettingForm extends SimpleForm {

    public const LEVEL_LIMIT = 280;

    public function __construct(Player $player, ?string $text = null) {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= SelectSettingForm::LEVEL_LIMIT) {
            $levelMessage = '§a解放済み / 要求レベル -> lv. ' . SelectSettingForm::LEVEL_LIMIT;
        } else {
            $levelMessage = '§c ' . SelectSettingForm::LEVEL_LIMIT . 'レベル以上で開放されます';
        }
        $this
            ->setTitle('PlayerSettings')
            ->setText("設定したい項目を選択してください{$text}")
            ->addElements(
                new SendNormalSettingFormButton(new NormalSettingListForm($player), 'Normal Setting'),
                new SendNormalSettingFormButton(new NormalSettingListForm($player), 'Normal Setting'),
                new SendNormalSettingFormButton(new NormalSettingListForm($player), 'Normal Setting'),
                new SendMiningToolsSettingFormButton($player, new MiningToolsSettingListForm($player), "Mining Tools Setting\n{$levelMessage}"),
            );
    }
}
