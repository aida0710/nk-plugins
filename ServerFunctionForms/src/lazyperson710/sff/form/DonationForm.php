<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\DonationsButton;

class DonationForm extends SimpleForm {

    public function __construct() {
        $msg1 = "なま鯖では寄付を募らせて頂いています\n寄付自体はamazonギフトカードでDiscordにで受け付けさせて頂いております\nもし、よければなまけもののDMまでお願いします";
        $msg2 = "また、寄付してくださった場合寄付額に応じて全員が特典を得ることができます\n特典は寄付額に応じて以下のボタンから寄付を受け取り可能です";
        $msg3 = "特典に関しては寄付していただいた際に寄付してくださった方の要望をある程度反映させていただこうと考えさせていただいています";
        $this
            ->setTitle("Donation Form")
            ->setText("{$msg1}\n\n{$msg2}\n\n{$msg3}")
            ->addElements(
                new DonationsButton("総寄付額 1500円以上\n現在ロックされています", 1500),
                new DonationsButton("総寄付額 3000円以上\n現在ロックされています", 3000),
                new DonationsButton("総寄付額 5000円以上\n現在ロックされています", 5000),
                new DonationsButton("総寄付額 8000円以上\n現在ロックされています", 8000),
                new DonationsButton("総寄付額 10000円以上\n現在ロックされています", 10000),
                new DonationsButton("総寄付額 15000円以上\n現在ロックされています", 15000),
            );
    }
}