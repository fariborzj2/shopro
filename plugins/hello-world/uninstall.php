<?php

$db = \App\Core\Database::getConnection();
$db->query("DROP TABLE IF EXISTS hello_messages");
