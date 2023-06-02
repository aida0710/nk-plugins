<?php

declare(strict_types = 0);

namespace lazyperson710\edit\form\item;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;

class AddEnchantments extends CustomForm {

    private Dropdown $enchantmentsSelect;
    private Input $enchantmentsLevel;

    public function __construct(?string $message = '選択してください') {
        $enchantments = [
            'Protection',
            'FireProtection',
            'FeatherFalling',
            'BlastProtection',
            'ProjectileProtection',
            'Thorns',
            'Respiration',
            'Sharpness',
            'Knockback',
            'FireAspect',
            'Efficiency',
            'SilkTouch',
            'Unbreaking',
            'Fortune',
            'Power',
            'Punch',
            'Flame',
            'Infinity',
            'Mending',
        ];
        $this->enchantmentsSelect = new Dropdown('付与したいエンチャントを選択してください', $enchantments);
        $this->enchantmentsLevel = new Input('エンチャントのレベルを入力してください', '1');
        $this
            ->setTitle('Item Edit')
            ->addElements(
                new Label($message),
                $this->enchantmentsSelect,
                $this->enchantmentsLevel,
            );
    }

    public function handleSubmit(Player $player) : void {
        $enchantments = $this->enchantmentsSelect->getSelectedOption();
        $enchantmentsLevel = $this->enchantmentsLevel->getValue();
        if ($enchantmentsLevel < 1) {
            SendForm::Send($player, new AddEnchantments('エンチャントのレベルは1以上で入力してください'));
            return;
        }
        if ($enchantmentsLevel > 32767) {
            SendForm::Send($player, new AddEnchantments('エンチャントのレベルは32767以下で入力してください'));
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        $vanillaEnchantments = match ($enchantments) {
            'Protection' => VanillaEnchantments::PROTECTION(),
            'FireProtection' => VanillaEnchantments::FIRE_PROTECTION(),
            'FeatherFalling' => VanillaEnchantments::FEATHER_FALLING(),
            'BlastProtection' => VanillaEnchantments::BLAST_PROTECTION(),
            'ProjectileProtection' => VanillaEnchantments::PROJECTILE_PROTECTION(),
            'Thorns' => VanillaEnchantments::THORNS(),
            'Respiration' => VanillaEnchantments::RESPIRATION(),
            'Sharpness' => VanillaEnchantments::SHARPNESS(),
            'Knockback' => VanillaEnchantments::KNOCKBACK(),
            'FireAspect' => VanillaEnchantments::FIRE_ASPECT(),
            'Efficiency' => VanillaEnchantments::EFFICIENCY(),
            'SilkTouch' => VanillaEnchantments::SILK_TOUCH(),
            'Unbreaking' => VanillaEnchantments::UNBREAKING(),
            'Fortune' => EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE),
            'Power' => VanillaEnchantments::POWER(),
            'Punch' => VanillaEnchantments::PUNCH(),
            'Flame' => VanillaEnchantments::FLAME(),
            'Infinity' => VanillaEnchantments::INFINITY(),
            'Mending' => VanillaEnchantments::MENDING(),
        };
        $item->addEnchantment(new EnchantmentInstance($vanillaEnchantments, (int) $enchantmentsLevel));
        $player->getInventory()->setItemInHand($item);
        SendForm::Send($player, new AddEnchantments("{$enchantments}を{$enchantmentsLevel}レベルで付与しました"));
    }

}
