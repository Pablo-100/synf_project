<?php

namespace App\Service;

/**
 * Service de validation et sanitization des données
 * Protection contre XSS et injections
 */
class SecurityService
{
    /**
     * Nettoie une chaîne de caractères pour prévenir les attaques XSS
     * 
     * @param string|null $input La chaîne à nettoyer
     * @return string|null La chaîne nettoyée
     */
    public function sanitizeString(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        // Supprime les balises HTML et PHP
        $cleaned = strip_tags($input);
        
        // Encode les caractères spéciaux HTML
        $cleaned = htmlspecialchars($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Trim les espaces
        $cleaned = trim($cleaned);
        
        return $cleaned;
    }

    /**
     * Nettoie un tableau de données
     * 
     * @param array $data Le tableau à nettoyer
     * @return array Le tableau nettoyé
     */
    public function sanitizeArray(array $data): array
    {
        $cleaned = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleaned[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $cleaned[$key] = $this->sanitizeString($value);
            } else {
                $cleaned[$key] = $value;
            }
        }
        
        return $cleaned;
    }

    /**
     * Valide un email
     * 
     * @param string $email L'email à valider
     * @return bool True si l'email est valide
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un numéro de téléphone (format français)
     * 
     * @param string $phone Le numéro à valider
     * @return bool True si le numéro est valide
     */
    public function validatePhone(string $phone): bool
    {
        // Nettoie le numéro
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Vérifie le format
        return preg_match('/^(?:(?:\+|00)33|0)[1-9](?:[0-9]{8})$/', $phone) === 1;
    }

    /**
     * Valide une URL
     * 
     * @param string $url L'URL à valider
     * @return bool True si l'URL est valide
     */
    public function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Génère un token CSRF
     * 
     * @return string Le token généré
     */
    public function generateCsrfToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Valide un token CSRF
     * 
     * @param string $token Le token à valider
     * @param string $expectedToken Le token attendu
     * @return bool True si le token est valide
     */
    public function validateCsrfToken(string $token, string $expectedToken): bool
    {
        return hash_equals($expectedToken, $token);
    }

    /**
     * Nettoie une chaîne pour une recherche SQL (protection supplémentaire)
     * 
     * @param string $query La requête de recherche
     * @return string La requête nettoyée
     */
    public function sanitizeSearchQuery(string $query): string
    {
        // Supprime les caractères dangereux
        $query = preg_replace('/[^\p{L}\p{N}\s\-\_]/u', '', $query);
        
        // Limite la longueur
        $query = substr($query, 0, 100);
        
        return trim($query);
    }

    /**
     * Vérifie si une chaîne contient des tentatives d'injection SQL
     * 
     * @param string $input La chaîne à vérifier
     * @return bool True si une tentative d'injection est détectée
     */
    public function detectSqlInjection(string $input): bool
    {
        $patterns = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|\bCREATE\b|\bALTER\b)/i',
            '/--/',
            '/\/\*/',
            '/;\s*$/',
            '/\bOR\b.*=.*\bOR\b/i',
            '/\bAND\b.*=.*\bAND\b/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si une chaîne contient des tentatives XSS
     * 
     * @param string $input La chaîne à vérifier
     * @return bool True si une tentative XSS est détectée
     */
    public function detectXss(string $input): bool
    {
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
            '/<object\b[^>]*>(.*?)<\/object>/is',
            '/<embed\b[^>]*>(.*?)<\/embed>/is',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Valide et nettoie une entrée utilisateur de manière complète
     * 
     * @param string $input L'entrée à valider
     * @param int $maxLength Longueur maximale autorisée
     * @return string|null La chaîne nettoyée ou null si dangereuse
     */
    public function validateAndSanitize(string $input, int $maxLength = 255): ?string
    {
        // Vérifie les tentatives d'attaque
        if ($this->detectSqlInjection($input) || $this->detectXss($input)) {
            return null;
        }

        // Nettoie l'entrée
        $cleaned = $this->sanitizeString($input);

        // Vérifie la longueur
        if (strlen($cleaned) > $maxLength) {
            $cleaned = substr($cleaned, 0, $maxLength);
        }

        return $cleaned;
    }

    /**
     * Génère un hash sécurisé pour un fichier uploadé
     * 
     * @param string $filename Le nom du fichier
     * @return string Le nom hashé
     */
    public function generateSecureFilename(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $hash = bin2hex(random_bytes(16));
        
        return $hash . '.' . strtolower($extension);
    }

    /**
     * Valide une extension de fichier
     * 
     * @param string $filename Le nom du fichier
     * @param array $allowedExtensions Extensions autorisées
     * @return bool True si l'extension est autorisée
     */
    public function validateFileExtension(string $filename, array $allowedExtensions): bool
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        return in_array($extension, array_map('strtolower', $allowedExtensions), true);
    }
}
