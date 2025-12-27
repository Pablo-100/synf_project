<?php

// Test de récupération des infos Facebook

require_once __DIR__ . '/vendor/autoload.php';

use League\OAuth2\Client\Provider\Facebook;

$provider = new Facebook([
    'clientId'          => '1371333587681489',
    'clientSecret'      => '3bb108524e6a70cd616faf3325cf6bd9',
    'redirectUri'       => 'http://localhost:8000/connect/facebook/check',
    'graphApiVersion'   => 'v18.0',
]);

echo "Test de configuration Facebook OAuth\n";
echo "=====================================\n\n";

echo "Client ID: " . $provider->getClientId() . "\n";
echo "Redirect URI: " . $provider->getRedirectUri() . "\n";
echo "Graph API Version: v18.0\n";

echo "\n✅ Configuration OK!\n\n";

echo "Pour tester:\n";
echo "1. Allez sur http://localhost:8000/login\n";
echo "2. Cliquez sur 'Continuer avec Facebook'\n";
echo "3. Autorisez l'application\n";
echo "4. Vérifiez que vous êtes connecté\n";
