<?php
require_once __DIR__ . '/app/helpers/Session.php';
Session::start();
Session::destroy();
header('Location: /wanbubu/');
exit;