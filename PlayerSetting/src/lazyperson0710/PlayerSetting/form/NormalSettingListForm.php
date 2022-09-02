<?php

namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\CoordinateSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DestructionSoundSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DiceMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DirectDropItemStorageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\EnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\JoinItemsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\LevelUpTitleSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsEnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\OnlinePlayersEffectsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\PayCommandUseSetting;
use lazyperson710\core\packet\CoordinatesPacket;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class NormalSettingListForm extends CustomForm {

    private Toggle $coordinate;
    private Toggle $joinItems;
    private Toggle $directDropItemStorage;
    private Toggle $levelUpTitle;
    private Toggle $enduranceWarning;
    private Toggle $miningToolsEnduranceWarning;
    private Toggle $destructionSound;
    private Toggle $diceMessage;
    private Toggle $payCommandUse;
    private Toggle $onlinePlayersEffects;

    public function __construct(Player $player) {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $this->setTitle("PlayerSettings");
        $this->addElements(
            $this->coordinate = new Toggle("§l> Coordinate§r\n座標を表示するか否か", $setting->getSetting(CoordinateSetting::getName())?->getValue()),
            $this->joinItems = new Toggle("§l> JoinItems§r\n参加時にアイテムを渡すか否か", $setting->getSetting(JoinItemsSetting::getName())?->getValue()),
            $this->directDropItemStorage = new Toggle("§l> DirectDropItemStorage§r\nInventoryが空でもストレージにアイテムを入れるか否か", $setting->getSetting(DirectDropItemStorageSetting::getName())?->getValue()),
            $this->levelUpTitle = new Toggle("§l> LevelUpTitle§r\nレベルアップ時にtitleを表示するか否か", $setting->getSetting(LevelUpTitleSetting::getName())?->getValue()),
            $this->enduranceWarning = new Toggle("§l> EnduranceWarning§r\n耐久値が少なくなったときに警告を出すか否か", $setting->getSetting(EnduranceWarningSetting::getName())?->getValue()),
            $this->miningToolsEnduranceWarning = new Toggle("§l> MiningToolsEnduranceWarning§r\n耐久値が少なくなったときに警告を出すか否か", $setting->getSetting(MiningToolsEnduranceWarningSetting::getName())?->getValue()),
            $this->destructionSound = new Toggle("§l> DestructionSound§r\n破壊時に経験値の音を鳴らすか否か", $setting->getSetting(DestructionSoundSetting::getName())?->getValue()),
            $this->diceMessage = new Toggle("§l> DiceMessage§r\nDiceのメッセージを表示するか否か", $setting->getSetting(DiceMessageSetting::getName())?->getValue()),
            $this->payCommandUse = new Toggle("§l> PayCommandUse§r\nPayコマンド使用時に確認formを表示させるか否か", $setting->getSetting(PayCommandUseSetting::getName())?->getValue()),
            $this->onlinePlayersEffects = new Toggle("§l> OnlinePlayersEffects§r\nオンラインプレイヤーが8人以上の時エフェクトを付与するか否か", $setting->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue()),
        );
    }

    public function handleClosed(Player $player): void {
        SendForm::Send($player, new SelectSettingForm($player, "\n§cFormを閉じたため、設定は保存されませんでした"));
    }

    public function handleSubmit(Player $player): void {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        if ($setting->getSetting(CoordinateSetting::getName())?->getValue() !== $this->coordinate->getValue()) {
            $setting->getSetting(CoordinateSetting::getName())?->setValue($this->coordinate->getValue());
            CoordinatesPacket::CoordinatesPacket($player, $this->coordinate->getValue());
        }
        if ($setting->getSetting(JoinItemsSetting::getName())?->getValue() !== $this->joinItems->getValue()) {
            $setting->getSetting(JoinItemsSetting::getName())?->setValue($this->joinItems->getValue());
        }
        if ($setting->getSetting(DirectDropItemStorageSetting::getName())?->getValue() !== $this->directDropItemStorage->getValue()) {
            $setting->getSetting(DirectDropItemStorageSetting::getName())?->setValue($this->directDropItemStorage->getValue());
        }
        if ($setting->getSetting(LevelUpTitleSetting::getName())?->getValue() !== $this->levelUpTitle->getValue()) {
            $setting->getSetting(LevelUpTitleSetting::getName())?->setValue($this->levelUpTitle->getValue());
        }
        if ($setting->getSetting(EnduranceWarningSetting::getName())?->getValue() !== $this->enduranceWarning->getValue()) {
            $setting->getSetting(EnduranceWarningSetting::getName())?->setValue($this->enduranceWarning->getValue());
        }
        if ($setting->getSetting(MiningToolsEnduranceWarningSetting::getName())?->getValue() !== $this->miningToolsEnduranceWarning->getValue()) {
            $setting->getSetting(MiningToolsEnduranceWarningSetting::getName())?->setValue($this->miningToolsEnduranceWarning->getValue());
        }
        if ($setting->getSetting(DestructionSoundSetting::getName())?->getValue() !== $this->destructionSound->getValue()) {
            $setting->getSetting(DestructionSoundSetting::getName())?->setValue($this->destructionSound->getValue());
        }
        if ($setting->getSetting(DiceMessageSetting::getName())?->getValue() !== $this->diceMessage->getValue()) {
            $setting->getSetting(DiceMessageSetting::getName())?->setValue($this->diceMessage->getValue());
        }
        if ($setting->getSetting(PayCommandUseSetting::getName())?->getValue() !== $this->payCommandUse->getValue()) {
            $setting->getSetting(PayCommandUseSetting::getName())?->setValue($this->payCommandUse->getValue());
        }
        if ($setting->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue() !== $this->onlinePlayersEffects->getValue()) {
            $setting->getSetting(OnlinePlayersEffectsSetting::getName())?->setValue($this->onlinePlayersEffects->getValue());
        }
        SendForm::Send($player, new SelectSettingForm($player, "\n§a設定を保存しました"));
    }

}