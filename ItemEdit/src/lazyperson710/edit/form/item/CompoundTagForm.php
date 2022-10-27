<?php

namespace lazyperson710\edit\form\item;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage;
use pocketmine\player\Player;

class CompoundTagForm extends CustomForm {

    private Input $setTag;
    private Input $setValue;
    private Dropdown $setType;

    public function __construct() {
        $this->setTag = new Input(
            "setTag",
            'string(\nで複数付与可能)',
        );
        $this->setType = new Dropdown("TagType",
            [
                "Int",
                "Float",
                "String",
            ]
        );
        $this->setValue = new Input(
            "setValue",
            "TagTypeと共にすべて共通の為注意",
        );
        $this
            ->setTitle("Item Edit")
            ->addElements(
                $this->setTag,
                $this->setType,
                $this->setValue,
            );
    }

    public function handleSubmit(Player $player): void {
        $itemInHand = $player->getInventory()->getItemInHand();
        if ($this->setTag->getValue() == "") {
            SendMessage::Send($player, "setTagを入力してください", "ItemEdit", false);
            return;
        } else {
            $setTags = explode('\n', $this->setTag->getValue());
        }
        $type = $this->setType->getSelectedOption();
        $value = $this->setValue->getValue();
        $nbt = $itemInHand->getNamedTag();
        switch ($type) {
            case 'Int':
                if (is_numeric($value)) {
                    foreach ($setTags as $setTag) {
                        $nbt->setInt($setTag, $value);
                        SendMessage::Send($player, "{$setTag}を{$type}/{$value}で付与しました", "ItemEdit", true);
                    }
                } else {
                    SendMessage::Send($player, "typeに対して型が違います。type -> {$type}/入力値 -> {$value}", "ItemEdit", false);
                    return;
                }
                break;
            case 'Float':
                if (is_numeric($value) && mb_strpos($value, '.')) {
                    if (mb_substr($value, -1) === '.' && mb_substr($value, 0, 1) === '.') {
                        SendMessage::Send($player, "typeに対して型が違います。type -> {$type}/入力値 -> {$value}", "ItemEdit", false);
                        return;
                    }
                    foreach ($setTags as $setTag) {
                        $nbt->setFloat($setTag, $value);
                        SendMessage::Send($player, "{$setTag}を{$type}/{$value}で付与しました", "ItemEdit", true);
                    }
                } else {
                    SendMessage::Send($player, "typeに対して型が違います。type -> {$type}/入力値 -> {$value}", "ItemEdit", false);
                    return;
                }
                break;
            case 'String':
                foreach ($setTags as $setTag) {
                    $nbt->setString($setTag, $value);
                    SendMessage::Send($player, "{$setTag}を{$type}/{$value}で付与しました", "ItemEdit", true);
                }
                break;
            default:
                return;
        }
        $player->getInventory()->setItemInHand($itemInHand);
    }
}