<?php namespace Ballen\Plexity\Entites;

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
class PreviousPasswordsCollection extends Collection
{

    public function __construct(array $items)
    {
        parent::__construct($items);
    }
}
