<?php

function redirect(string $url): void{
    header('Location: '.$url);
    exit;
}

function toBack(): void{
    redirect($_SERVER['HTTP_REFERER']);
}

function hasLoadCorrectFileImage(string $name): bool{
    return 
        isset($_FILES[$name]) &&
        $_FILES[$name]['error'] == 0 &&
        substr($_FILES[$name]['type'], 0, 6) == 'image/';
}