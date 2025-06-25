<?php

// Inclut le fichier d'autoload de Composer

use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/vendor/autoload.php';

// Crée une instance de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Exécute la commande Artisan
Artisan::call('panels:check-expired');

echo "Commande exécutée avec succès !";
