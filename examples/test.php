<?php
require_once '../lib/plexity.inc.php';
use Ballen\Plexity\Plexity as PasswordValidator;

$password = new PasswordValidator();

$password->notIn([
    'piggy',
    'people',
    //'MyPasswordHere',
]);

$password->requireLowerCase()
    ->requireUpperCase()
    ->requireNumericChataters()
    ->requireSpecialCharacters();


try {
    $password->check('mypasSwordhere');
    echo "Password passed validation!";
} catch (Ballen\Plexity\Exceptions\ValidationException $exception) {
    die('The validation failed, the error was: ' . $exception->getMessage());
}