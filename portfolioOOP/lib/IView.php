<?php

namespace lib;

interface IView {
    function render(string $page, array $vars = []);
}