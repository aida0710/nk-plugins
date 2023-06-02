<?php

declare(strict_types = 0);

namespace lazyperson710\sff\form\land;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\SendFormButton;
use pocketmine\player\Player;

class LandInviteForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle('Login Bonus')
            ->addElements(
                new SendFormButton(new LandInviteAddForm($player), '自分の土地を他プレイヤーに共有', null),
                new SendFormButton(new LandInviteeForm($player), '自分の土地に共有されているプレイヤーをみる', null),
                new SendFormButton(new LandKickForm($player), '自分の土地に共有されているプレイヤーを削除する', null),
            );
    }
}
