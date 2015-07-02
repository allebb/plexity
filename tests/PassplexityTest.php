<?php
use \PHPUnit_Framework_TestCase;
use Ballen\Plexity\Plexity;

class PassplexitylTest extends PHPUnit_Framework_TestCase
{

    const PASSWORD1 = "mytestpassword";
    const PASSWORD2 = "APassword123!";
    const PASSWORD3 = "Mu171Ple@5%%";
    const PASSWORD4 = "";

    public function testSuccessfulRuleSet()
    {
        $password = new Plexity();
        $password->lengthBetween(1, 5);
        $this->assertTrue($password->check('a5$f'));
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
        $password->minimumLength(1);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The length does not meet the minimum length requirements.');
        $password->check('');
    }
    
    public function testValidLenghtOnMaximu()
    {
        $password = new Plexity();
        $password->maximumLength(5);
        $this->assertTrue($password->check('Trffg'));
    }

    public function testInvalidLengthOnMaximum()
    {
        $password = new Plexity();
        $password->maximumLength(1);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The length exceeds the maximum length requirements.');
        $password->check('Ab');
    }

    public function testPasswordInArray()
    {
        $password = new Plexity();
        $password->notIn(['Example2', 'An3xampl3', 'MyEx4mp!e']);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The string exists in the list of disallowed values requirements.');
        $password->check('An3xampl3');
    }

    public function testPasswordNotInArray()
    {
        $password = new Plexity();
        $password->notIn(['Example2', 'An3xampl3', 'MyEx4mp!e']);
        $this->assertTrue($password->check('AC0mpl3telyD1fferentOne'));
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
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The string failed to meet the special character requirements.');
        $password->check('An3xampl3');
    }

    public function testPasswordHasAmountOfSpecialChars()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters(5);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The string failed to meet the special character requirements.');
        $password->check('/n3%am^l£?');
    }

    public function testPasswordNotHasAmountOfSpecialChars()
    {
        $password = new Plexity();
        $password->requireSpecialCharacters(5);
        $this->setExpectedException('Ballen\Plexity\Exceptions\ValidationException', 'The string failed to meet the special character requirements.');
        $password->check('An3%a*pl3');
    }
}
