<?php
require_once './YMResponseParser.php';

$message1 = <<<STR
Никому не говорите пароль! Его спрашивают только мошенники.
Пароль: 1234
Перевод на счет 41001144631925
Вы потратите 232,13р.
STR;

$message2 = <<<STR
Спишется 4545,73р.
Пароль: 6859
Перевод на счет 410011441232434
STR;

$message3 = <<<STR
Сумма указана неверно.
STR;

$message4 = <<<STR
Недостаточно средств.
STR;

$messages = [$message1, $message2, $message3, $message4, "", null];


$parser = new YMResponseParser();

foreach ($messages as $message) {
    try {
        $parser->parse($message);
        var_dump([
            'wallet' => $parser->getWallet(),
            'code' => $parser->getCode(),
            'sum' => $parser->getSum()
        ]);

    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        echo sprintf("Original message: %s", $message) . PHP_EOL;
    }
    echo "==========" . PHP_EOL;
}
