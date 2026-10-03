<?php

// Redirige la raíz del proyecto (http://localhost/terminales) a la aplicación Laravel en public/.
$query = $_SERVER['QUERY_STRING'] ?? '';
header('Location: public/' . ($query !== '' ? '?' . $query : ''), true, 302);
exit;
