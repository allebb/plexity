<?php


namespace Ballen\Plexity\Interfaces;

interface PasswordHistoryInterface
{
    /**
     * Checks that the password does not exist in a password history implementation.
     * @param string $password The password to check the history against.
     * @return boolean Returns true if the password exists in the history.
     */
    public function checkHistory($password);
}
