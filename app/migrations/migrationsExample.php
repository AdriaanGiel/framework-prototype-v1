<?php
$migrations = new \Homework\core\helpers\Collection([
    \Homework\migrations\Migration::generate("table",[
        \Homework\migrations\Attribute::generate("row","string",255),
        \Homework\migrations\Attribute::generate("row1", "string", 255),
        \Homework\migrations\Attribute::generate("row2","string",255),
        \Homework\migrations\Attribute::generate("row3","int",11),
        \Homework\migrations\Attribute::generate("row4","string",255)
    ])
]);

// Add file to RunMigrations.php