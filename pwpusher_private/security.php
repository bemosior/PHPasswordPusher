<?php
/**
 * Encryption functions
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
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


/**
 * Generates a UUID v5 (SHA-1) 
 *
 * @return string $uniqueId
 */
function getUniqueId() 
{
    uuid_create(&$context);
    uuid_create(&$namespace);

    //Creates a UUID based on a unique ID based on time in milliseconds.
    //The uniqid function is using the more_entropy = true option.
    uuid_make($context, UUID_MAKE_V5, $namespace, uniqid('',true));
    uuid_export($context, UUID_FMT_STR, &$uniqueId);
    return trim($uniqueId);
}

/**
 * Hashes the id via bcrypt
 *
 * @param string $id
 *
 * @return string $hashedId
 */
function hashId($id, $salt) 
{
    $hashedId = crypt($id, '$2a$07$' . $salt . '$');
    return $hashedId;
}

/**
 * Generates a 128-bit salt
 *
 * @return string $salt
 */
function getSalt()
{
    $salt = substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);
    return $salt;
}
?>