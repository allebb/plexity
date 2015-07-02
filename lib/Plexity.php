<?php namespace Ballen\Plexity;

use Ballen\Collection\Collection;

/**
 * Plexity
 *
 * Plexity (Password Complexity) is a password complexity library that
 * enables you to set "rules" for a password (or any other kind of string) that
 * you can then check against in your application.
 *
 * @author Bobby Allen <ballen@bobbyallen.me>
 * @version 1.0.0
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/bobsta63/plexity
 * @link http://www.bobbyallen.me
 *
 */
class Plexity
{

    const RULE_UPPER = 'upper';
    const RULE_LOWER = 'lower';
    const RULE_SPECIAL = 'special';
    const RULE_NUMERIC = 'numeric';
    const RULE_LENGTH_MIN = 'length_min';
    const RULE_LENGTH_MAX = 'length_max';
    const RULE_NOT_IN = 'not_in';

    /**
     * Number lists
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
     * The configured list of rules for the object.
     * @var Entites\Collection
     */
    protected $rules;

    /**
     * The string to validate against
     * @var string
     */
    private $check_string;

    public function __construct()
    {
        $this->rules = new Collection([
            self::RULE_UPPER => false,
            self::RULE_LOWER => false,
            self::RULE_SPECIAL => 0,
            self::RULE_NUMERIC => 0,
            self::RULE_LENGTH_MIN => 0,
            self::RULE_LENGTH_MAX => 0,
            self::RULE_NOT_IN => [],
        ]);
    }

    /**
     * Requires the password to contain upper case characters.
     * @return \Ballen\Plexity\Plexity
     */
    public function requireUpperCase()
    {
        $this->rules->put(self::RULE_UPPER, true);
        return $this;
    }

    /**
     * Requires the password to contain lower case characters.
     * @return \Ballen\Plexity\Plexity
     */
    public function requireLowerCase()
    {
        $this->rules->put(self::RULE_LOWER, true);
        return $this;
    }

    /**
     * Requires the password to contain special characters.
     * @param inte $amount Optional amount of special characters the string must atleast contain.
     * @return \Ballen\Plexity\Plexity
     */
    public function requireSpecialCharacters($amount = 1)
    {
        $this->rules->put(self::RULE_SPECIAL, $amount);
        return $this;
    }

    /**
     * Requires the password to contain numeric characters.
     * @param int $amount Optional amount of numeric characters the string must atleast contain.
     * @return \Ballen\Plexity\Plexity
     */
    public function requireNumericChataters($amount = 1)
    {
        $this->rules->put(self::RULE_NUMERIC, $amount);
        return $this;
    }

    /**
     * Requires the password/string to be atleast X characters long.
     * @param int $length Minimum length that the password/string must be.
     * @return \Ballen\Plexity\Plexity
     */
    public function minimumLength($length)
    {
        if (!is_int($length)) {
            throw new \InvalidArgumentException('The minimum length value must be of type integer.');
        }
        $this->rules->put(self::RULE_LENGTH_MIN, $length);
        return $this;
    }

    /**
     * Requires the password/string to be a maximum of X charaters long.
     * @param int $length Maximum length that the password/string can be.
     * @return \Ballen\Plexity\Plexity
     */
    public function maximumLength($length)
    {
        if (!is_int($length)) {
            throw new \InvalidArgumentException('The maximum length value must be of type integer.');
        }
        $this->rules->put(self::RULE_LENGTH_MAX, $length);
        return $this;
    }

    /**
     * An alias for adding both the minimumLenght() and maximumLenght() methods/rules.
     * @param int $minimmum Length must be atleast X characters long.
     * @param int $maximum Length must not exceed X characters long.
     * @return \Ballen\Plexity\Plexity
     */
    public function lengthBetween($minimmum, $maximum)
    {
        $this->minimumLength($minimmum);
        $this->maximumLength($maximum);
        return $this;
    }

    /**
     * Requires that the password/string is not found in the collection.
     * @param array The array of passwords/strings to check against.
     * @return \Ballen\Plexity\Plexity
     */
    public function notIn(array $array)
    {
        $this->rules->put(self::RULE_NOT_IN, $array);
        return $this;
    }

    /**
     * Check the string against the list of configured rules to ensure that it is valid.
     * @string $string The password/string to validate.
     * @return boolean
     */
    public function check($string)
    {
        $this->check_string = $string;
        return $this->validateRules();
    }

    /**
     * Validates all the configured rules and responds as requested.
     * @return boolean
     * @throws \Ballen\Plexity\Exceptions\ValidationException
     */
    private function validateRules()
    {
        if ($this->rules->get(self::RULE_LENGTH_MIN) > 0) {
            if (!$this->validateLengthMin()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The length does not meet the minimum length requirements.');
            }
        }

        if ($this->rules->get(self::RULE_LENGTH_MAX) > 0) {
            if (!$this->validateLengthMax()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The length exceeds the maximum length requirements.');
            }
        }

        if ($this->rules->get(self::RULE_LOWER)) {
            if (!$this->validateLowerCase()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the lower case requirements.');
            }
        }

        if ($this->rules->get(self::RULE_UPPER)) {
            if (!$this->validateUpperCase()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the upper case requirements.');
            }
        }

        if ($this->rules->get(self::RULE_NUMERIC) > 0) {
            if (!$this->validateNumericCharacters()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the numeric character requirements.');
            }
        }

        if ($this->rules->get(self::RULE_SPECIAL) > 0) {
            if (!$this->validateSpecialCharacters()) {
                throw new \Ballen\Plexity\Exceptions\ValidationException('The string failed to meet the special character requirements.');
            }
        }
        if (count($this->rules->get(self::RULE_NOT_IN)) > 0) {
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
        return (bool) preg_match("/[A-Z]/", $this->check_string);
    }

    /**
     * Validates the lower case requirements.
     * @return boolean
     */
    private function validateLowerCase()
    {
        return (bool) preg_match("/[a-z]/", $this->check_string);
    }

    /**
     * Validates the special character requirements.
     * @return boolean
     */
    private function validateSpecialCharacters()
    {
        $count = 0;
        foreach ($this->special_characters as $characters) {
            $count += substr_count($this->check_string, $characters);
        }
        if ($count >= $this->rules->get(self::RULE_SPECIAL)) {
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
        $count = 0;
        foreach ($this->numbers as $numbers) {
            $count += substr_count($this->check_string, $numbers);
        }
        if ($count >= $this->rules->get(self::RULE_NUMERIC)) {
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
        if ($this->check_string >= $this->rules->get(self::RULE_LENGTH_MIN)) {
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
        if ($this->check_string <= $this->rules->get(self::RULE_LENGTH_MAX)) {
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
        if (in_array($this->check_string, $this->rules->get(self::RULE_NOT_IN))) {
            return false;
        }
        return true;
    }
}
