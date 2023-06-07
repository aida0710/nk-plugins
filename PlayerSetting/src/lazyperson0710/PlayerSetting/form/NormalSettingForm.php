<?php

declare(strict_types = 1);

namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\PlayerSetting\form\element\SendNormalSettingFormButton;
use lazyperson0710\PlayerSetting\form\normal\SettingDisplayForm;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\BossBarColorSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\CoordinateSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DestructionSoundSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DiceMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DirectDropItemStorageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\GachaEjectFormSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\GachaEjectMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\JoinItemsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\LevelUpDisplaySetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsDestructionEnabledWorldsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MoveWorldMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\NormalToolsWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\OnlinePlayersEffectsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\PayCommandUseSetting;
use pocketmine\player\Player;

class NormalSettingForm extends SimpleForm {

    private const COORDINATE_TITLE = 'Coordinate' . PHP_EOL . '座標の表示に関する設定';
    private const JOIN_ITEMS_TITLE = 'JoinItems' . PHP_EOL . '参加時のアイテム付与に関する設定';
    private const DIRECT_DROP_ITEM_STORAGE_TITLE = 'DirectDropItemStorage' . PHP_EOL . '採掘時のアイテム取得に関する設定';
    private const NORMAL_TOOLS_WARNING_TITLE = 'NormalToolsDamageWarning' . PHP_EOL . '通常ツールの耐久値警告に関する設定';
    private const MINING_TOOLS_WARNING_TITLE = 'MiningToolsDamageWarning' . PHP_EOL . 'マイニングツールの耐久値警告に関する設定';
    private const DESTRUCTION_SOUND_TITLE = 'DestructionSound' . PHP_EOL . '採掘時のサウンドに関する設定';
    private const DICE_MESSAGE_TITLE = 'DiceMessage' . PHP_EOL . 'ダイスを回したときのメッセージに関する設定';
    private const GACHA_EJECT_FORM_TITLE = 'GachaEjectForm' . PHP_EOL . 'ガチャの排出時のフォームに関する設定';
    private const GACHA_EJECT_MESSAGE_TITLE = 'GachaEjectMessage' . PHP_EOL . 'ガチャの排出時のメッセージに関する設定';
    private const PAY_COMMAND_USE_TITLE = 'PayCommandUse' . PHP_EOL . '送金時の確認画面に関する設定';
    private const ONLINE_PLAYERS_EFFECTS_TITLE = 'OnlinePlayersEffects' . PHP_EOL . 'プレイヤー8人以上の時に付与されるエフェクトに関する設定';
    private const MOVE_WORLD_MESSAGE_TITLE = 'MoveWorldMessage' . PHP_EOL . 'ワールド移動時に表示されるメッセージに関する設定';
    private const LEVEL_UP_DISPLAY_TITLE = 'LevelUpDisplay' . PHP_EOL . 'レベルアップ表示に関する設定';
    private const DESTRUCTION_ENABLED_WORLDS_TITLE = 'DestructionEnabledWorlds' . PHP_EOL . 'マイニングツールの範囲破壊に関する設定';
    private const BOSS_BAR_COLOR_TITLE = 'BossBarColor' . PHP_EOL . 'ボスバーの色に関する設定';

    public function __construct(Player $player) {
        $this
            ->setTitle('Normal Setting')
            ->setText('設定したい項目を選択してください');
        foreach ($this->getNormalSettingList($player) as $key => $setting) {
            if (!$setting instanceof SettingDisplayForm) return;
            $this->addElements(new SendNormalSettingFormButton($setting, $key));
        }
    }

    private function getNormalSettingList(Player $player) : array {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        return [
            self::COORDINATE_TITLE => new SettingDisplayForm($player, $setting->getSetting(CoordinateSetting::getName())?->getValue()),
            self::JOIN_ITEMS_TITLE => new SettingDisplayForm($player, $setting->getSetting(JoinItemsSetting::getName())?->getValue()),
            self::DIRECT_DROP_ITEM_STORAGE_TITLE => new SettingDisplayForm($player, $setting->getSetting(DirectDropItemStorageSetting::getName())?->getValue()),
            self::NORMAL_TOOLS_WARNING_TITLE => new SettingDisplayForm($player, $setting->getSetting(NormalToolsWarningSetting::getName())?->getValue()),
            self::MINING_TOOLS_WARNING_TITLE => new SettingDisplayForm($player, $setting->getSetting(MiningToolsWarningSetting::getName())?->getValue()),
            self::DESTRUCTION_SOUND_TITLE => new SettingDisplayForm($player, $setting->getSetting(DestructionSoundSetting::getName())?->getValue()),
            self::DICE_MESSAGE_TITLE => new SettingDisplayForm($player, $setting->getSetting(DiceMessageSetting::getName())?->getValue()),
            self::GACHA_EJECT_FORM_TITLE => new SettingDisplayForm($player, $setting->getSetting(GachaEjectFormSetting::getName())?->getValue()),
            self::GACHA_EJECT_MESSAGE_TITLE => new SettingDisplayForm($player, $setting->getSetting(GachaEjectMessageSetting::getName())?->getValue()),
            self::PAY_COMMAND_USE_TITLE => new SettingDisplayForm($player, $setting->getSetting(PayCommandUseSetting::getName())?->getValue()),
            self::ONLINE_PLAYERS_EFFECTS_TITLE => new SettingDisplayForm($player, $setting->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue()),
            self::MOVE_WORLD_MESSAGE_TITLE => new SettingDisplayForm($player, $setting->getSetting(MoveWorldMessageSetting::getName())?->getValue()),
            self::LEVEL_UP_DISPLAY_TITLE => new SettingDisplayForm($player, $setting->getSetting(LevelUpDisplaySetting::getName())?->getValue()),
            self::DESTRUCTION_ENABLED_WORLDS_TITLE => new SettingDisplayForm($player, $setting->getSetting(MiningToolsDestructionEnabledWorldsSetting::getName())?->getValue()),
            self::BOSS_BAR_COLOR_TITLE => new SettingDisplayForm($player, $setting->getSetting(BossBarColorSetting::getName())?->getValue()),
        ];
    }

}