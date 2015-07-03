<?php namespace Ballen\Plexity\Support;

use \Ballen\Plexity\Plexity;

class Validator
{

    /**
     * The Plexity object (contains the validation configuration)
     * @var \Ballen\Plexity\Plexity
     */
    private $configuration;

    /**
     * Numeric values list
     * @var array
     */
    protected $numbers = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 0
    ];

    /**
     * Special Character list
     * @see https://www.owasp.org/index.php/Password_special_characters
     * @var array
     */
    protected $special_characters = [
        ' ', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '.',
        '/', ':', ';', '<', '=', '>', '?', '@', '[', ']', '\\', '^', '_', '`',
        '{', '|', '}', '~',
    ];

    /**
     * Validates all the configured rules and responds as requested.
     * @return boolean
     * @throws \Ballen\Plexity\Exceptions\ValidationException
     */
    public function validate(Plexity $configuration)
    {
        $this->configuration = $configuration;

        if ($this->configuration->rules()->get(Plexity::RULE_LENGTH_MIN) > 0) {
            if (!$this->validateLengthMin()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The length does not meet the minimum length requirements.');
            }
        }

        if ($this->configuration->rules()->get(Plexity::RULE_LENGTH_MAX) > 0) {
            if (!$this->validateLengthMax()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The length exceeds the maximum length requirements.');
            }
        }

        if ($this->configuration->rules()->get(Plexity::RULE_LOWER)) {
            if (!$this->validateLowerCase()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the lower case requirements.');
            }
        }

        if ($this->configuration->rules()->get(Plexity::RULE_UPPER)) {
            if (!$this->validateUpperCase()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the upper case requirements.');
            }
        }

        if ($this->configuration->rules()->get(Plexity::RULE_NUMERIC) > 0) {
            if (!$this->validateNumericCharacters()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the numeric character requirements.');
            }
        }

        if ($this->configuration->rules()->get(Plexity::RULE_SPECIAL) > 0) {
            if (!$this->validateSpecialCharacters()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the special character requirements.');
            }
        }
        if (count($this->configuration->rules()->get(Plexity::RULE_NOT_IN)) > 0) {
            if (!$this->validateNotIn()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string exists in the list of disallowed values requirements.');
            }
        }
        return true;
    }

    /**
     * Validates the upper case requirements.
     * @return boolean
     */
    private function validateUpperCase()
    {
        return (bool) preg_match("/[A-Z]/", $this->configuration->checkString());
    }

    /**
     * Validates the lower case requirements.
     * @return boolean
     */
    private function validateLowerCase()
    {
        return (bool) preg_match("/[a-z]/", $this->configuration->checkString());
    }

    /**
     * Validates the special character requirements.
     * @return boolean
     */
    private function validateSpecialCharacters()
    {
        if ($this->countOccurences($this->special_characters, $this->configuration->checkString()) >= $this->configuration->rules()->get(Plexity::RULE_SPECIAL)) {
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
        if ($this->countOccurences($this->numbers, $this->configuration->checkString()) >= $this->configuration->rules()->get(Plexity::RULE_NUMERIC)) {
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
     * Validates the not_in requirements.
     * @return boolean
     */
    private function validateNotIn()
    {
        if (in_array($this->configuration->checkString(), $this->configuration->rules()->get(Plexity::RULE_NOT_IN))) {
            return false;
        }
        return true;
    }

    /**
     * Count the number of occurences of a character or string in a string.
     * @param array $needles The character/string to count occurences of.
     * @param string $haystack The string to check against.
     * @return int The number of occurences.
     */
    private function countOccurences(array $needles, $haystack)
    {
        $count = 0;
        foreach ($needles as $char) {
            $count += substr_count($haystack, $char);
        }
        return $count;
    }
}
