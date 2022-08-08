<?php
declare(strict_types=1);

namespace lazyperson0710\PlayerSetting\object;

interface ISetting{
	public function getName():string;
	public function getValue():mixed;
}