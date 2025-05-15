<?php
$port = getenv('PORT') ?: '8080';
echo "Server running on port $port";
exec("php -S 0.0.0.0:$port -t public");