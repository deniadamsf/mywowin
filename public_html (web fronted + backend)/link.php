<?php


$exitCode = shell_exec('php artisan storage:link 2>&1');
echo "<pre>$exitCode</pre>";