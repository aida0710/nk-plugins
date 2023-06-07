<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\StepSlider;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
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
use lazyperson710\core\packet\CoordinatesPacket;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\network\mcpe\protocol\types\BossBarColor;
use pocketmine\player\Player;

class NormalSettingListForm extends CustomForm {

    private Toggle $coordinate;
    private Toggle $joinItems;
    private Toggle $directDropItemStorage;
    private Toggle $enduranceWarning;
    private Toggle $miningToolsEnduranceWarning;
    private Toggle $destructionSound;
    private Toggle $diceMessage;
    private Toggle $payCommandUse;
    private Toggle $onlinePlayersEffects;
    private Toggle $moveWorldMessage;
    private Toggle $gachaEjectMessage;
    private Toggle $gachaEjectForm;
    private StepSlider $levelUpDisplay;
    private StepSlider $miningToolsDestructionEnabledWorlds;
    private StepSlider $bossBarColor;

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $this->setTitle('PlayerSettings');
        $this->addElements(
            new Label('設定項目を選んでください' . PHP_EOL . '一番下の送信ボタンを押さないとセーブされない為ご注意ください' . PHP_EOL . '設定はfalse / trueで切り替えられます(白くなってたらtrue)'),
            $this->coordinate = new Toggle("§l> Coordinate§r\n左上に座標を表示するか", $setting->getSetting(CoordinateSetting::getName())?->getValue()),
            $this->joinItems = new Toggle("§l> JoinItems§r\n参加時に専用アイテムを渡すか", $setting->getSetting(JoinItemsSetting::getName())?->getValue()),
            $this->directDropItemStorage = new Toggle("§l> DirectDropItemStorage§r\n採掘時にインベントリに空きがあってもストレージにアイテムを入れるか", $setting->getSetting(DirectDropItemStorageSetting::getName())?->getValue()),
            $this->enduranceWarning = new Toggle("§l> NormalToolsWarning§r\n通常ツール使用時に耐久値が少なくなったときに警告を出すか", $setting->getSetting(NormalToolsWarningSetting::getName())?->getValue()),
            $this->miningToolsEnduranceWarning = new Toggle("§l> MiningToolsWarning§r\n耐久値が少なくなったときに警告を出すか", $setting->getSetting(MiningToolsWarningSetting::getName())?->getValue()),
            $this->destructionSound = new Toggle("§l> DestructionSound§r\n破壊時に経験値の音を鳴らすか", $setting->getSetting(DestructionSoundSetting::getName())?->getValue()),
            $this->diceMessage = new Toggle("§l> DiceMessage§r\nDiceのメッセージを表示するか", $setting->getSetting(DiceMessageSetting::getName())?->getValue()),
            $this->gachaEjectForm = new Toggle("§l> GachaEjectForm§r\nGachaの排出時に表示されるFormを表示するか(オフにすると実行完了時に完了をお知らせするTipメッセージが表示されます)", $setting->getSetting(GachaEjectFormSetting::getName())?->getValue()),
            $this->gachaEjectMessage = new Toggle("§l> GachaEjectMessage§r\nGachaのレジェンダリーなどのメッセージを表示するか(他人の表示や排出音なども消えます)", $setting->getSetting(GachaEjectMessageSetting::getName())?->getValue()),
            $this->payCommandUse = new Toggle("§l> PayCommandUse§r\nPayコマンド使用時に確認formを表示させるか", $setting->getSetting(PayCommandUseSetting::getName())?->getValue()),
            $this->onlinePlayersEffects = new Toggle("§l> OnlinePlayersEffects§r\nオンラインプレイヤーが8人以上の時エフェクトを付与するか", $setting->getSetting(OnlinePlayersEffectsSetting::getName())?->getValue()),
            $this->moveWorldMessage = new Toggle("§l> MoveWorldMessage§r\nワープ時にワールドの説明を表示するか(§l§c非表示は非推奨です§r)", $setting->getSetting(MoveWorldMessageSetting::getName())?->getValue()),
            $this->levelUpDisplay = new StepSlider("§l> LevelUpDisplay§r\nレベルアップ時に何を表示するか", [
                'タイトル表示',
                '実績表示',
                '表示無し',
            ],
                match ($setting->getSetting(LevelUpDisplaySetting::getName())?->getValue()) {
                    'toast' => 1,
                    'none' => 2,
                    default => 0
                }),
            $this->miningToolsDestructionEnabledWorlds = new StepSlider("§l> MiningToolsDestructionEnabledWorlds§r\nマイニングツールで破壊出来るワールドを制限", [
                '全てのワールドで有効',
                '生活ワールドでのみ有効',
                '天然資源系ワールドでのみ有効',
                '全てのワールドで無効',
            ],
                match ($setting->getSetting(MiningToolsDestructionEnabledWorldsSetting::getName())?->getValue()) {
                    'life' => 1,
                    'nature' => 2,
                    'none' => 3,
                    default => 0
                }),
            $this->bossBarColor = new StepSlider("§l> BossBarColor§r\nレベル表示用のボスバーの色を変更(リログ必須)", [
                'ピンク',
                'ブルー',
                'レッド',
                'グリーン',
                'イエロー',
                'パープル',
                'ホワイト',
            ],
                match ($setting->getSetting(BossBarColorSetting::getName())?->getValue()) {
                    BossBarColor::BLUE => 1,
                    BossBarColor::RED => 2,
                    BossBarColor::GREEN => 3,
                    BossBarColor::YELLOW => 4,
                    BossBarColor::PURPLE => 5,
                    BossBarColor::WHITE => 6,
                    default => 0
                }),
        );
    }

    public function handleClosed(Player $player) : void {
        SendForm::Send($this->player, new SelectSettingForm($player, "\n§cFormを閉じたため、設定は保存されませんでした"));
        SoundPacket::Send($player, 'dig.chain');
    }

    public function handleSubmit(Player $player) : void {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($this->player);
        $setting->getSetting(CoordinateSetting::getName())?->setValue($this->coordinate->getValue());
        CoordinatesPacket::Send($this->player, $this->coordinate->getValue());
        $setting->getSetting(JoinItemsSetting::getName())?->setValue($this->joinItems->getValue());
        $setting->getSetting(DirectDropItemStorageSetting::getName())?->setValue($this->directDropItemStorage->getValue());
        $setting->getSetting(NormalToolsWarningSetting::getName())?->setValue($this->enduranceWarning->getValue());
        $setting->getSetting(MiningToolsWarningSetting::getName())?->setValue($this->miningToolsEnduranceWarning->getValue());
        $setting->getSetting(DestructionSoundSetting::getName())?->setValue($this->destructionSound->getValue());
        $setting->getSetting(DiceMessageSetting::getName())?->setValue($this->diceMessage->getValue());
        $setting->getSetting(PayCommandUseSetting::getName())?->setValue($this->payCommandUse->getValue());
        $setting->getSetting(GachaEjectMessageSetting::getName())?->setValue($this->gachaEjectMessage->getValue());
        $setting->getSetting(GachaEjectFormSetting::getName())?->setValue($this->gachaEjectForm->getValue());
        $setting->getSetting(OnlinePlayersEffectsSetting::getName())?->setValue($this->onlinePlayersEffects->getValue());
        $setting->getSetting(MoveWorldMessageSetting::getName())?->setValue($this->moveWorldMessage->getValue());
        $levelUpTitle = match ($this->levelUpDisplay->getValue()) {
            1 => 'toast',
            2 => 'none',
            default => 'title'
        };
        $setting->getSetting(LevelUpDisplaySetting::getName())?->setValue($levelUpTitle);
        $miningToolsDestructionEnabledWorlds = match ($this->miningToolsDestructionEnabledWorlds->getValue()) {
            1 => 'life',
            2 => 'nature',
            3 => 'none',
            default => 'all'
        };
        $setting->getSetting(MiningToolsDestructionEnabledWorldsSetting::getName())?->setValue($miningToolsDestructionEnabledWorlds);
        $bossBarColor = match ($this->bossBarColor->getValue()) {
            1 => BossBarColor::BLUE,
            2 => BossBarColor::RED,
            3 => BossBarColor::GREEN,
            4 => BossBarColor::YELLOW,
            5 => BossBarColor::PURPLE,
            6 => BossBarColor::WHITE,
            default => BossBarColor::PINK
        };
        $setting->getSetting(BossBarColorSetting::getName())?->setValue($bossBarColor);
        SendForm::Send($player, new SelectSettingForm($this->player, "\n§a設定を保存しました"));
        SoundPacket::Send($player, 'item.spyglass.use');
    }

}
