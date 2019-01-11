<?php

function cdd($var) {
    echo (new \Symfony\Component\VarDumper\Dumper\CliDumper())
        ->dump((new \Symfony\Component\VarDumper\Cloner\VarCloner())->cloneVar($var), true);
    die;
}
