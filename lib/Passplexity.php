<?php namespace Ballen\Passplexity;

use Entites\Collection;

/**
 * Passplexity
 *
 * Passplexity (Password Complexity) is a password complexity library that
 * enables you to set "rules" for a password (or any other kind of string) that
 * you can then check against in your application.
 *
 * @author Bobby Allen <ballen@bobbyallen.me>
 * @version 1.0.0
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/bobsta63/passplexity
 * @link http://www.bobbyallen.me
 *
 */
class Passplexity
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
    private $check;

    public function __construct()
    {
        $this->rules = new Entites\Collection([
            self::RULE_UPPER => false,
            self::RULE_LOWER => false,
            self::RULE_SPECIAL => false,
            self::RULE_NUMERIC => false,
            self::RULE_LENGTH_MIN => false,
            self::RULE_LENGTH_MAX => false,
            self::RULE_NOT_IN => false,
        ]);
    }

    /**
     * Requires the password to contain upper case characters.
     * @return \Ballen\Passplexity\Passplexity
     */
    public function requireUpperCase()
    {
        $this->rules->put(self::RULE_UPPER, true);
        return $this;
    }

    /**
     * Requires the password to contain lower case characters.
     * @return \Ballen\Passplexity\Passplexity
     */
    public function requireLowerCase()
    {
        $this->rules->put(self::RULE_LOWER, true);
        return $this;
    }

    /**
     * Requires the password to contain special characters.
     * @param type $amount Optional amount of special characters the string must atleast contain.
     * @return \Ballen\Passplexity\Passplexity
     */
    public function requireSpecialCharacters($amount = 1)
    {
        $this->rules->put(self::RULE_SPECIAL, $amount);
        return $this;
    }

    /**
     * Requires the password to contain numeric characters.
     * @param type $amount Optional amount of numeric characters the string must atleast contain.
     * @return \Ballen\Passplexity\Passplexity
     */
    public function requireNumericChataters($amount = 1)
    {
        $this->rules->put(self::RULE_LENGTH_MAX);
        return $this;
    }

    /**
     * Requires the password/string to be atleast X characters long.
     * @param type $length
     * @return \Ballen\Passplexity\Passplexity
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
     * @param type $length
     * @return \Ballen\Passplexity\Passplexity
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
     * @param integer $minimmum Length must be atleast X characters
     * @param integer $maximum Length must not exceed X characters
     * @return \Ballen\Passplexity\Passplexity
     */
    public function lengthBetween($minimmum, $maximum)
    {
        $this->minimumLength($minimmum);
        $this->maximumLength($maximum);
        return $this;
    }

    /**
     * Requires that the password/string is not found in the collection.
     * @param array The array of passwords/string to check.
     * @return \Ballen\Passplexity\Passplexity
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
        $this->check = $string;
    }

    private function validateUpperCase($string)
    {
        return (bool) preg_match("/[A-Z]/", $this->check);
    }

    private function validateLowerCase()
    {
        return (bool) preg_match("/[a-z]/", $this->check);
    }

    private function validateSpecialCharacters()
    {
        $count = 0;
    }

    private function validateNumericCharacters()
    {
        $count = 0;
        
    }

    private function validateLengthMin()
    {
        if ($this->check >= $this->rules->get(self::RULE_LENGTH_MIN)) {
            return true;
        }
        return false;
    }

    private function validateLengthMax()
    {
        if ($this->check <= $this->rules->get(self::RULE_LENGTH_MAX)) {
            return true;
        }
        return false;
    }

    private function validateNotIn()
    {
        if (in_array($this->check, $this->rules->get(self::RULE_NOT_IN))) {
            return false;
        }
        return true;
    }
}
