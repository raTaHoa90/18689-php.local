<?php

function RunsIsGet(array $variables, int &$guess): int {
    $countAttempts = $variables['countAttempts'] ?? 0;
    $countAttempts--;

    if(isset($_GET['run'])){
        $guess = $_GET['guess'];
        $variant = $_GET['variant'];

        if($guess == $variant)
            echo 'Поздравляем, вы угодали число!!!';
        elseif($countAttempts > 0)
            echo "Увы, было загадано не $variant, у вас осталось $countAttempts попыток";
        else
            echo "Увы, было загадано число $guess, а не $variant";
        echo '<br>Сервер загадал число, попробуйте угадать его.<br>';

    } else
        echo 'Сервер загадал число, попробуйте угадать его.<br>';

    return $countAttempts;
}

function EvenOrOdd(int $guess): void {
    $strNOT = $guess % 2 == 1 ? 'не' : '';
    echo <<<END
    <li>Загаданное число <b>{$strNOT}четное</b>
    END;
}

function CountChars(string $guess): void {
    echo '<li>Загаданное число состоит из '. strlen($guess). ' символов';
}

function HasNumberReverse(string $guess): void {
    switch(strlen($guess)){
        case 1: break;
        case 2: 
            if($guess[0] == $guess[1])
                echo '<li>Загаданное число является Числом-палиндромом';
            break;

        case 3:
            if($guess[0] == $guess[2])
                echo '<li>Загаданное число является Числом-палиндромом';
            break;

        default: 
            echo '<li><b style="color: red">не предусмотренное значение</b>';
            break;
    }
}
