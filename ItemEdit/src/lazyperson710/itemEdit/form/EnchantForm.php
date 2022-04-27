<?php

namespace lazyperson710\itemEdit\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantForm extends CustomForm {

    private Dropdown $setEnchant;
    private Input $setValue;

    public function __construct() {
        $enchants = null;
        foreach (VanillaEnchantments::getAll() as $enchant){
            if(is_string($enchant->getName())){
            $enchants[] .= $enchant->getName();
            }else{
                $enchants[] .= Server::getInstance()->getLanguage()->translate($enchant->getName());
            }
        }
        $this->setEnchant = new Dropdown("Enchants", $enchants);
        $this->setValue = new Input(
            "setLevel",
            "int",
        );
        $this
            ->setTitle("Item Edit")
            ->addElements(
                $this->setEnchant,
                $this->setValue,
            );
    }

    public function handleSubmit(Player $player): void {
        if (!is_numeric($this->setValue->getValue())) return;
        $enchant = $this->setEnchant->getSelectedOption();
        foreach (VanillaEnchantments::getAll() as $enchant){
            $vanilla_enchant = VanillaEnchantments::$enchant;
        }
        $enchantInstance = new EnchantmentInstance($vanilla_enchant, $this->setValue->getValue());
        $item = $player->getInventory()->getItemInHand();
        $item->addEnchantment($enchantInstance);
        $player->getInventory()->setItemInHand($item);
    }
}