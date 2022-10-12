<?php

for ($i = 1; $i < 50; $i++) {
    $output = $i + (($i * 1.3) * $i);
    var_dump($i . " | " . (int)$output);
}
