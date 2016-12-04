<?php
namespace Ballen\Plexity;

use Ballen\Collection\Collection;
use Ballen\Plexity\Support\Validator;

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
 * @link http://bobbyallen.me
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
     * The configured list of rules for the object.
     * @var \Ballen\Collection\Collection
     */
    private $rules;

    /**
     * The string to validate against
     * @var string
     */
    private $checkString;

    /**
     * Default collection settings
     */
    private $defaultConfiguration = [
        self::RULE_UPPER => false,
        self::RULE_LOWER => false,
        self::RULE_SPECIAL => 0,
        self::RULE_NUMERIC => 0,
        self::RULE_LENGTH_MIN => 0,
        self::RULE_LENGTH_MAX => 0,
        self::RULE_NOT_IN => [],
    ];

    /**
     * The validator instance.
     * @var \Ballen\Plexity\Support\Validator
     */
    private $validator;

    /**
     * Instaniate a new instance of the Plexity class.
     */
    public function __construct()
    {
        $this->rules = new Collection($this->defaultConfiguration);

        $this->validator = new Validator;
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
     * @param int $minLength Minimum length that the password/string must be.
     * @return \Ballen\Plexity\Plexity
     */
    public function minimumLength($minLength)
    {
        if (!is_int($minLength)) {
            throw new \InvalidArgumentException('The minimum length value must be of type integer.');
        }
        $this->rules->put(self::RULE_LENGTH_MIN, $minLength);
        return $this;
    }

    /**
     * Requires the password/string to be a maximum of X characters long.
     * @param int $maxLength Maximum length that the password/string can be.
     * @return \Ballen\Plexity\Plexity
     */
    public function maximumLength($maxLength)
    {
        if (!is_int($maxLength)) {
            throw new \InvalidArgumentException('The maximum length value must be of type integer.');
        }
        $this->rules->put(self::RULE_LENGTH_MAX, $maxLength);
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
        $this->checkString = $string;
        return $this->validator->validate($this);
    }

    /**
     * Returns the configured rule set.
     * @return \Ballen\Collection\Collection
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Returns the password/string of which is to be checked.
     * @return string
     */
    public function checkString()
    {
        return $this->checkString;
    }
}
