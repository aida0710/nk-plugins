<?php

declare(strict_types = 0);

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use lazyperson710\sff\element\SendFormButton;

class InformationForm extends SimpleForm {

	public function __construct() {
		$this->setTitle('InformationForm');
		$this->setText("見たいコンテンツを選択してください\nこのFormは/infoで使用可能です");
		$this->addElements(
			new CommandDispatchButton('お知らせ', 'announce', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/invite_base.png')),
			new CommandDispatchButton('寄付に関して', 'donation', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/items/book_portfolio.png')),
			new SendFormButton(new TosForm(), "利用規約\nweb版:www.nkserver.net/tos.html", new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/Feedback.png')),
			new Button('閉じる', new ButtonImage(ButtonImage::TYPE_PATH, 'textures/ui/redX1.png')),
		);
	}
}
