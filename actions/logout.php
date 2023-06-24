<?php
const MAIN = true;
require __DIR__.'/../config.php';

auth()->logout();
redirect('/login.php');
