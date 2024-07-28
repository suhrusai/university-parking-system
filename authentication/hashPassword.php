<?php
function hashPassword($password) {
    // Use the bcrypt algorithm (default)
    $options = [
        'cost' => 12, // This cost parameter determines the computational cost
    ];

    // Generate a hash with a salt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

    return $hashedPassword;
}

?>

