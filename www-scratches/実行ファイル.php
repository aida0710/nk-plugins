<?php

declare(strict_types = 1);
$nextExp = 80;
foreach (range(1, 811) as $lv) {
	if ($lv === 1) {
		$nextExp = 80;
		echo "[LV.$lv] " . $nextExp . PHP_EOL;
		continue;
	}
	$nextExp += $lv - 1;
	echo "[LV.$lv] " . $nextExp . PHP_EOL;
}
