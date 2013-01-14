<?php
/**
 * Encryption functions
 */

/**
 * Encrypt the credential.
 *
 * @param string $cred the credential to be encrypted
 *
 * @return string $encrypted the encrypted string
 */
function encryptCred($cred) 
{
    include 'config.php';
    $encrypted = base64_encode(
        mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256, $key, $cred, MCRYPT_MODE_ECB,
            mcrypt_create_iv(
                mcrypt_get_iv_size(
                    MCRYPT_RIJNDAEL_256, 
                    MCRYPT_MODE_ECB
                ), 
                MCRYPT_RAND
            )
        )
    );
    return $encrypted;
}

/**
 * Decrypt the credential from the database.
 *
 * @param string $encrypted the encrypted string
 *
 * @return string $decrypted the decrypted string
 */
function decryptCred($encrypted) 
{
    include 'config.php';
    $decrypted = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_256,
        $key,
        base64_decode($encrypted),
        MCRYPT_MODE_ECB,
        mcrypt_create_iv(
            mcrypt_get_iv_size(
                MCRYPT_RIJNDAEL_256, 
                MCRYPT_MODE_ECB
            ), 
            MCRYPT_RAND
        )
    );
    return $decrypted;
}
?>