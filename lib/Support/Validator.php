<?php

namespace Ballen\Plexity\Support;

use Ballen\Plexity\Interfaces\PasswordHistoryInterface;
use Ballen\Plexity\Plexity;
use Ballen\Plexity\Exceptions\ValidationException;

/**
 * Plexity
 *
 * Plexity (Password Complexity) is a password complexity library that
 * enables you to set "rules" for a password (or any other kind of string) that
 * you can then check against in your application.
 *
 * @author Bobby Allen <ballen@bobbyallen.me>
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/allebb/plexity
 * @link https://bobbyallen.me
 *
 */
class Validator
{

    /**
     * RegEx for uppercase character detection.
     */
    const REGEX_UPPER_CASE = "/[A-Z]/";

    /**
     * RegEx for lowercase character detection.
     */
    const REGEX_LOWER_CASE = "/[a-z]/";

    /**
     * The Plexity object (contains the validation configuration)
     * @var Plexity
     */
    private $configuration;

    /**
     * Message list of defined iso code
     */
    private $locale = 'en';

    /**
     * messages list
     */
    private $messages;

    /**
     * Numeric values list
     * @var array<int>
     */
    protected $numbers = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0
    ];

    /**
     * Special Character list
     * @see https://www.owasp.org/index.php/Password_special_characters
     * @var array<string>
     */
    protected $specialCharacters = [
        ' ',
        '!',
        '"',
        '#',
        '$',
        '%',
        '&',
        '\'',
        '(',
        ')',
        '*',
        '+',
        ',',
        '.',
        '/',
        ':',
        ';',
        '<',
        '=',
        '>',
        '?',
        '@',
        '[',
        ']',
        '\\',
        '^',
        '_',
        '-',
        '`',
        '{',
        '|',
        '}',
        '~',
    ];

    /**
     * Validates all the configured rules and responds as requested.
     * @return boolean
     * @throws ValidationException
     */
    public function validate(Plexity $configuration)
    {

        $tmpMessageObj = 'Ballen\\Plexity\\Data\\Locale\\Validator\\' . $this->locale;
        if(!class_exists($tmpMessageObj))
            throw new ValidationException('Locale class not found.');

        $this->messages = $tmpMessageObj::$messages;

        $this->configuration = $configuration;
        $this->checkMinimumLength();
        $this->checkMaximumLength();
        $this->checkLowerCase();
        $this->checkUpperCase();
        $this->checkNumericCharacters();
        $this->checkSpecialCharacters();
        $this->checkNotIn();
        return true;
    }

    /**
     * Checks the minimum length requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkMinimumLength()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_LENGTH_MIN) > 0) {
            if (!$this->validateLengthMin()) {
                throw new ValidationException('The length does not meet the minimum length requirements.');
            }
        }
    }

    /**
     * Checks the minimum maximum length requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkMaximumLength()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_LENGTH_MAX) > 0) {
            if (!$this->validateLengthMax()) {
                throw new ValidationException($this->messages[__FUNCTION__]);
            }
        }
    }

    /**
     * Checks the lowercase character(s) requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkLowerCase()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_LOWER) > 0) {
            if (!$this->validateLowerCase()) {
                throw new ValidationException($this->messages[__FUNCTION__]);
            }
        }
    }

    /**
     * Checks the upper case character(s) requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkUpperCase()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_UPPER) > 0) {
            if (!$this->validateUpperCase()) {
                throw new ValidationException($this->messages[__FUNCTION__]);
            }
        }
    }

    /**
     * Checks the numeric character(s) requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkNumericCharacters()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_NUMERIC) > 0) {
            if (!$this->validateNumericCharacters()) {
                throw new ValidationException($this->messages[__FUNCTION__]);
            }
        }
    }

    /**
     * Checks the special character(s) requirement.
     * @return void
     * @throws ValidationException
     */
    public function checkSpecialCharacters()
    {
        if ($this->configuration->rules()->get(Plexity::RULE_SPECIAL) > 0) {
            if (!$this->validateSpecialCharacters()) {
                throw new ValidationException($this->messages[__FUNCTION__]);
            }
        }
    }

    /**
     * Validates if a string is not in a array (password history database).
     * @return void
     * @throws ValidationException
     */
    public function checkNotIn()
    {

        if ($this->configuration->rules()->get(Plexity::RULE_NOT_IN) === null) {
            return;
        }

        if ($this->configuration->rules()->get(Plexity::RULE_NOT_IN) instanceof PasswordHistoryInterface) {
            $this->validateNotInPasswordHistoryImplementation();
        }

        if (is_array($this->configuration->rules()->get(Plexity::RULE_NOT_IN)) && count($this->configuration->rules()->get(Plexity::RULE_NOT_IN)) > 0) {
            $this->validateNotInArray();
        }

    }

    /**
     * Validates the upper case requirements.
     * @return boolean
     */
    private function validateUpperCase()
    {
        $occurences = preg_match_all(self::REGEX_UPPER_CASE, $this->configuration->checkString());

        if ($occurences >= $this->configuration->rules()->get(Plexity::RULE_UPPER)) {
            return true;
        }

        return false;
    }

    /**
     * Validates the lower case requirements.
     * @return boolean
     */
    private function validateLowerCase()
    {
        $occurrences = preg_match_all(self::REGEX_LOWER_CASE, $this->configuration->checkString());

        if ($occurrences >= $this->configuration->rules()->get(Plexity::RULE_LOWER)) {
            return true;
        }

        return false;
    }

    /**
     * Validates the special character requirements.
     * @return boolean
     */
    private function validateSpecialCharacters()
    {
        if ($this->countOccurrences($this->specialCharacters,
                $this->configuration->checkString()) >= $this->configuration->rules()->get(Plexity::RULE_SPECIAL)) {
            return true;
        }
        return false;
    }

    /**
     * Validates the numeric case requirements.
     * @return boolean
     */
    private function validateNumericCharacters()
    {
        if ($this->countOccurrences($this->numbers,
                $this->configuration->checkString()) >= $this->configuration->rules()->get(Plexity::RULE_NUMERIC)) {
            return true;
        }
        return false;
    }

    /**
     * Validates the minimum string length requirements.
     * @return boolean
     */
    private function validateLengthMin()
    {
        if (strlen($this->configuration->checkString()) >= $this->configuration->rules()->get(Plexity::RULE_LENGTH_MIN)) {
            return true;
        }
        return false;
    }

    /**
     * Validates the maximum string length requirements.
     * @return boolean
     */
    private function validateLengthMax()
    {
        if (strlen($this->configuration->checkString()) <= $this->configuration->rules()->get(Plexity::RULE_LENGTH_MAX)) {
            return true;
        }
        return false;
    }

    /**
     * Validates the not_in requirements against a simple array.
     * @return void
     * @throws ValidationException
     */
    private function validateNotInArray()
    {
        if (in_array($this->configuration->checkString(),
            (array)$this->configuration->rules()->get(Plexity::RULE_NOT_IN))) {
            throw new ValidationException($this->messages[__FUNCTION__]);
        }
    }

    /**
     * Validates the not_in requirements against an implementation of PasswordHistoryInterface.
     * @return void
     * @throws ValidationException
     */
    private function validateNotInPasswordHistoryImplementation()
    {
        if (($this->configuration->rules()->get(Plexity::RULE_NOT_IN))->checkHistory($this->configuration->checkString())) {
            throw new ValidationException($this->messages[__FUNCTION__]);
        }
    }

    /**
     * Count the number of occurrences of a character or string in a string.
     * @param array<mixed> $needles The character/string to count occurrences of.
     * @param string $haystack The string to check against.
     * @return int The number of occurrences.
     */
    private function countOccurrences(array $needles, $haystack)
    {
        $count = 0;
        foreach ($needles as $char) {
            $count += substr_count($haystack, $char);
        }
        return $count;
    }

    /**
     * Defines the locale property.
     * @param string $isoCode This data refers to the language ISO 639-1 Code.
     * @return void
     */
    public function setLocale(string $isoCode) {
        $this->locale = $isoCode;
    }
}
