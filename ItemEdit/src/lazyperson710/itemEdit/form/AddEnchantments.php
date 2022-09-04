<?php

namespace lazyperson710\itemEdit\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
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

    public function __construct() {
        $enchantments = [
            "Protection",
            "FireProtection",
            "FeatherFalling",
            "BlastProtection",
            "ProjectileProtection",
            "Thorns",
            "Respiration",
            "Sharpness",
            "Knockback",
            "FireAspect",
            "Efficiency",
            "SilkTouch",
            "Unbreaking",
            "Fortune",
            "Power",
            "Punch",
            "Flame",
            "Infinity",
            "Mending",
        ];
        $this->enchantmentsSelect = new Dropdown("付与したいエンチャントを選択してください", $enchantments);
        $this->enchantmentsLevel = new Input("エンチャントのレベルを入力してください", "1");
        $this
            ->setTitle("")
            ->addElements(
                $this->enchantmentsSelect,
                $this->enchantmentsLevel,
            );
    }

    public function handleSubmit(Player $player): void {
        $enchantments = $this->enchantmentsSelect->getSelectedOption();
        $enchantmentsLevel = $this->enchantmentsLevel->getValue();
        if ($enchantmentsLevel < 1) {
            $player->sendMessage("エンチャントのレベルは1以上で入力してください");
            SendForm::Send($player, new AddEnchantments());
            return;
        }
        if ($enchantmentsLevel > 32767) {
            $player->sendMessage("エンチャントのレベルは32767以下で入力してください");
            SendForm::Send($player, new AddEnchantments());
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        $vanillaEnchantments = match ($enchantments) {
            "Protection" => VanillaEnchantments::PROTECTION(),
            "FireProtection" => VanillaEnchantments::FIRE_PROTECTION(),
            "FeatherFalling" => VanillaEnchantments::FEATHER_FALLING(),
            "BlastProtection" => VanillaEnchantments::BLAST_PROTECTION(),
            "ProjectileProtection" => VanillaEnchantments::PROJECTILE_PROTECTION(),
            "Thorns" => VanillaEnchantments::THORNS(),
            "Respiration" => VanillaEnchantments::RESPIRATION(),
            "Sharpness" => VanillaEnchantments::SHARPNESS(),
            "Knockback" => VanillaEnchantments::KNOCKBACK(),
            "FireAspect" => VanillaEnchantments::FIRE_ASPECT(),
            "Efficiency" => VanillaEnchantments::EFFICIENCY(),
            "SilkTouch" => VanillaEnchantments::SILK_TOUCH(),
            "Unbreaking" => VanillaEnchantments::UNBREAKING(),
            "Fortune" => EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE),
            "Power" => VanillaEnchantments::POWER(),
            "Punch" => VanillaEnchantments::PUNCH(),
            "Flame" => VanillaEnchantments::FLAME(),
            "Infinity" => VanillaEnchantments::INFINITY(),
            "Mending" => VanillaEnchantments::MENDING(),
        };
        $item->addEnchantment(new EnchantmentInstance($vanillaEnchantments, $enchantmentsLevel));
        $player->sendMessage("{$enchantments}を{$enchantmentsLevel}にしました");
        SendForm::Send($player, new AddEnchantments());
    }

}