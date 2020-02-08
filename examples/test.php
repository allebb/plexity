<?php
require_once '../vendor/autoload.php';

use Ballen\Plexity\Plexity as PasswordValidator;

$password = new PasswordValidator();

$password->requireSpecialCharacters() // We want the password to contain special characters.
->requireUpperCase() // Require that our password contains upper case charaters.
->requireLowerCase() // Require that our password contains lower case characters.
->requireNumericChataters(3); // We want to ensure that the password uses atleast 3 numbers!

// An example of passing a password history array, if the password exists in
// here then we'll disallow it!
$password->notIn([
    'piggy',
    'people',
    'mypasSwordh88e8*&|re',
]);

try {
    $password->check('mypasSwordh88e8*&|re');
    echo "Great news! The password passed validation!";
} catch (Ballen\Plexity\Exceptions\ValidationException $exception) {
    die('The validation failed, the error was: ' . $exception->getMessage());
}