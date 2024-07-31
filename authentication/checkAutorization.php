<?php

function checkAuthorization($redirectPath, $allowedRoles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: $redirectPath");
        exit();
    }
}