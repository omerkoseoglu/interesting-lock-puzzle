<?php

require __DIR__ . '/vendor/autoload.php';

$lock = new \App\Lock();

$lock->process();
