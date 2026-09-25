<?php

function GET_default(){
    echo 'MAIN DEFAULT';
}

function GET_one(){
    view('main/one', [
        'var' => $_GET['var'] ?? 4312
    ]);
}

function GET_two(){
    view('main/two');
}