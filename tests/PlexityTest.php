<?php

namespace Ballen\Plexity\Tests;

use Ballen\Plexity\Plexity;
use Ballen\Plexity\Tests\Implementations\MD5PasswordHistoryStore;

class PlexityTest extends \PHPUnit_Framework_TestCase
{

    public function testSuccessfulRuleSet()
    {
        $password = new Plexity();
        $password->lengthBetween(1, 5);
        $this->assertTrue($password->check('a5$f'));
    }

    public function testValidRequireUpperCase()
    {
        $password = new Plexity();
        $password->requireUpperCase();
        $this->assertTrue($password->check('Trff'));
    }

    public function testInvalidRequireUpperCase()
    {
        $password = new Plexity();
        $password->requireUpperCase();
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the upper case requirements.');
        $password->check('allthelettersarelowercase');
    }

    public function testValidRequireLowerCase()
    {
        $password = new Plexity();
        $password->requireLowerCase();
        $this->assertTrue($password->check('Trff'));
    }

    public function testInvalidRequireLowerCase()
    {
        $password = new Plexity();
        $password->requireLowerCase();
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the lower case requirements.');
        $password->check('THISLOWERCASEEXAMPLEWILLFAIL');
    }

    public function testValidLenghtOnMinimum()
    {
        $password = new Plexity();
        $password->minimumLength(1);
        $this->assertTrue($password->check('Trff'));
    }

    public function testInvalidLenghtOnMinimum()
    {
        $password = new Plexity();
        $password->minimumLength(3);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The length does not meet the minimum length requirements.');
        $password->check('');
    }

    public function testInvalidTypeOnMinimum()
    {
        $password = new Plexity();
        $this->setExpectedException('\InvalidArgumentException', 'The minimum length value must be of type integer.');
        $password->minimumLength('test');
        $password->check('Ab');
    }

    public function testValidLenghtOnMaximum()
    {
        $password = new Plexity();
        $password->maximumLength(5);
        $this->assertTrue($password->check('Trffg'));
    }

    public function testInvalidLengthOnMaximum()
    {
        $password = new Plexity();
        $password->maximumLength(1);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The length exceeds the maximum length requirements.');
        $password->check('Ab');
    }

    public function testInvalidTypeOnMaximum()
    {
        $password = new Plexity();
        $this->setExpectedException('\InvalidArgumentException', 'The maximum length value must be of type integer.');
        $password->maximumLength('test');
        $password->check('Ab');
    }

    public function testPasswordInArray()
    {
        $password = new Plexity();
        $password->notIn(['Example2', 'An3xampl3', 'MyEx4mp!e']);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string exists in the list of disallowed values requirements.');
        $password->check('An3xampl3');
    }

    public function testPasswordNotInArray()
    {
        $password = new Plexity();
        $password->notIn(['Example2', 'An3xampl3', 'MyEx4mp!e']);
        $this->assertTrue($password->check('AC0mpl3telyD1fferentOne'));
    }

    public function testPasswordHasNumericCharacters()
    {
        $password = new Plexity();
        $password->requireNumericChataters();
        $this->assertTrue($password->check('A#4%%^'));
    }

    public function testPasswordNotHasNumericCharacters()
    {
        $password = new Plexity();
        $password->requireNumericChataters();
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the numeric character requirements.');
        $password->check('AnExample');
    }

    public function testPasswordHasAmountOfNumericChars()
    {
        $password = new Plexity();
        $password->requireNumericChataters(5);
        $this->assertTrue($password->check('/n3%33m2l£7?'));
    }

    public function testPasswordNotHasAmountOfNumericChars()
    {
        $password = new Plexity();
        $password->requireNumericChataters(5);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the numeric character requirements.');
        $password->check('An3%a*pl3');
    }

    public function testPasswordHasSpecialCharacters()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters();
        $this->assertTrue($password->check('A#4%%^'));
    }

    public function testPasswordNotHasSpecialCharacters()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters();
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the special character requirements.');
        $password->check('An3xampl3');
    }

    public function testPasswordHasAmountOfSpecialChars()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters(5);
        $this->assertTrue($password->check('/n3%am^l£?:'));
    }

    public function testPasswordNotHasAmountOfSpecialChars()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters(5);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the special character requirements.');
        $password->check('An3%a*pl3');
    }

    public function testPasswordIsBetweenCharacters()
    {
        $password = new Plexity();
        $password->lengthBetween(1, 5);
        $this->assertTrue($password->check('An3a'));
    }

    public function testPasswordIsNotBetweenCharactersMinFail()
    {
        $password = new Plexity();
        $password->lengthBetween(4, 10);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The length does not meet the minimum length requirements.');
        $password->check('An3');
    }

    public function testPasswordIsNotBetweenCharactersMaxFail()
    {
        $password = new Plexity();
        $password->lengthBetween(1, 5);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The length exceeds the maximum length requirements.');
        $password->check('An3akkkk');
    }

    public function testPasswordContainsANumberOfLowercaseCharacters()
    {
        $password = new Plexity();
        $password->requireLowerCase(3);
        $this->assertTrue($password->check('ABCDEfgh'));
    }

    public function testPasswordContainsANumberOfLowerCharactersFail()
    {
        $password = new Plexity();
        $password->requireLowerCase(4);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the lower case requirements.');
        $password->check('ABCDEfgh');
    }

    public function testPasswordContainsANumberOfUppercaseCharacters()
    {
        $password = new Plexity();
        $password->requireUpperCase(5);
        $this->assertTrue($password->check('ABCDEfgh'));
    }

    public function testPasswordContainsANumberOfUppercaseCharactersFail()
    {
        $password = new Plexity();
        $password->requireUpperCase(6);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string failed to meet the upper case requirements.');
        $password->check('ABCDEfgh');
    }

    public function testPasswordDoesExistInPasswordHistoryStore()
    {
        $password = new Plexity();
        $passwordHistoryStore = new MD5PasswordHistoryStore;
        $password->notIn($passwordHistoryStore);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException',
            'The string exists in the list of disallowed values requirements.');
        $password->check('R0seBu9');
    }

    public function testPasswordDoesNotExistInPasswordHistoryStore()
    {
        $password = new Plexity();
        $passwordHistoryStore = new MD5PasswordHistoryStore;
        $password->notIn($passwordHistoryStore);
        $this->assertTrue($password->check('Bingo!'));
    }
}
