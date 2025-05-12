<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GedDocument;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Session\Session; // Utilisation de la classe Session
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GedUtilities
{
    private string $appSecret;
    private EntityManagerInterface $entityManager;
	private Filesystem $filesystem;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function encryptFile($filePath, $password, $outputFilePath) {
        $plaintext = file_get_contents($filePath);
        if ($plaintext === false) {
            return false; // Échec de la lecture du fichier
        }
    
        $method = 'aes-256-cbc'; // Méthode de chiffrement
        $key = hash('sha256', $password, true); // Clé dérivée du mot de passe
        $ivlen = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivlen);
    
        $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            return false; // Échec du chiffrement
        }
    
        // Préfixer le IV au texte chiffré pour le déchiffrement
        $encrypted = $iv . $ciphertext;
    
        return file_put_contents($outputFilePath, $encrypted); // Écrit le fichier chiffré
    }
    public function decryptFile($filePath, $password) {
        $ciphertextWithIv = file_get_contents($filePath);
        if ($ciphertextWithIv === false) {
            return false;
        }
    
        $method = 'aes-256-cbc';
        $key = hash('sha256', $password, true);
        $ivlen = openssl_cipher_iv_length($method);
        $iv = substr($ciphertextWithIv, 0, $ivlen);
        $ciphertext = substr($ciphertextWithIv, $ivlen);
    
        $plaintext = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            return false;
        }
		return $plaintext;
    }
    



}
