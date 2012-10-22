<?php
//Encrypt the credential into the database and generate the access URL.
function EncryptCred($cred) {
require 'config.php';
  $encrypted = base64_encode(
        mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256, $key, $cred, MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
        )
    );
   return $encrypted;
}

function DecryptCred($encrypted) {
  require 'config.php';
      $decrypted = mcrypt_decrypt(
          MCRYPT_RIJNDAEL_256,
          $key,
          base64_decode($encrypted),
          MCRYPT_MODE_ECB,
          mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
      );
      return $decrypted;
}
?>