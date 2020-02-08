<?php


namespace Ballen\Plexity\Tests\Implementations;

use Ballen\Plexity\Interfaces\PasswordHistoryInterface;

/**
 * Class MD5PasswordHistoryStore
 * @package Ballen\Plexity\Tests\Implementations
 */
class MD5PasswordHistoryStore implements PasswordHistoryInterface
{

    /**
     * An array of plain and simple MD5 password hashes.
     * (I HIGHLY RECOMMEND YOU USE STRONG ENCRYPTION IN YOUR REAL WORLD APPLICATIONS THOUGH - THIS IS JUST A VERY SIMPLE IMPLEMENTATION EXAMPLE!!!!!)
     * @var array<string>
     */
    private $md5Passwords = [
        'dc647eb65e6711e155375218212b3964', # 'Password'
        '3ce189cedebda85cc8454c8339091e39', # 'R0seBu9'
    ];

    /**
     * Converts the plain password into an MD5 hash before checking against our hashed history array.
     * @param string $plainPassword
     * @return bool
     */
    private function hashAndCheckHistory($plainPassword)
    {
        $hashedPassword = md5($plainPassword);
        return in_array($hashedPassword, $this->md5Passwords);
    }

    /**
     * @inheritDoc
     */
    public function checkHistory($password)
    {
        return $this->hashAndCheckHistory($password);
    }
}