# Plexity

[![Build Status](https://travis-ci.org/allebb/plexity.svg)](https://travis-ci.org/allebb/plexity)
[![Code Coverage](https://scrutinizer-ci.com/g/allebb/plexity/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/allebb/plexity/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/allebb/plexity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/allebb/plexity/?branch=master)
[![Code Climate](https://codeclimate.com/github/allebb/plexity/badges/gpa.svg)](https://codeclimate.com/github/allebb/plexity)
[![Latest Stable Version](https://poser.pugx.org/ballen/plexity/v/stable)](https://packagist.org/packages/ballen/plexity)
[![Latest Unstable Version](https://poser.pugx.org/ballen/plexity/v/unstable)](https://packagist.org/packages/ballen/plexity)
[![License](https://poser.pugx.org/ballen/plexity/license)](https://packagist.org/packages/ballen/plexity)

Plexity (Password Complexity) is a password complexity library that enables you to set "rules" for a password (or any other kind of string) that you can then check against in your application.

This library supports the following kind of complexity settings:

* Upper/lowercase character detection
* Number containment
* Special character containment
* Minimum/maximum character detection
* Password age expiry detection
* Detection of previous use such as against a password history datastore.
* Ability to add and check against a configurable list of common passwords/words etc.

Requirements
------------

This library is developed and tested for PHP 5.4+

This library is unit tested against PHP 5.6, 7.0, 7.1, 7.2 and 7.3!

License
-------

This client library is released under the MIT license, a [copy of the license](https://github.com/allebb/plexity/blob/master/LICENSE) is provided in this package.

Setup
-----

To install the package into your project (assuming you are using the [Composer](https://getcomposer.org/) package manager) you can simply execute the following command from your terminal in the root of your project folder:

```
composer require ballen/plexity
```

Alternatively you can manually add this library to your project using the following steps, simply edit your project's ``composer.json`` file and add the following lines (or update your existing ``require`` section with the library like so):

```json
"require": {
        "ballen/plexity": "~1.0"
}
```

Then install the package like so:

```
composer update ballen/plexity --no-dev
```

Examples
--------

A simple example of how you can use the methods to build up a password complexity rule-set and then validate the password.

```php

use Ballen\Plexity\Plexity as PasswordValidator;

$password = new PasswordValidator();

$password->requireSpecialCharacters() // We want the password to contain (atleast 1) special characters.
    //->requireSpecialCharacters(5), // We could also specify a specific number of special characters.
    ->requireUpperCase() // Requires the password to contains more than one upper case characters.
    ->requireLowerCase(2) // Requires the password to contains atleast 2 lower case characters.
    ->requireNumericChataters(3); // We want to ensure that the password uses at least 3 numbers!

// An example of passing a password history array, if the password exists in here then we'll disallow it!
$password->notIn([
    'test_password',
    'Ros3bud',
    'mypasSwordh88e8*&|re',
]);
// You can optionally pass in an implementation of PasswordHistoryInterface like so:
//$password->notIn(new CustomPasswordHistoryDatastore()); // Must implement Ballen\Plexity\Interfaces\PasswordHistoryInterface

try {
    $password->check('my_password_string_here');
    echo "Great news! The password passed validation!";
} catch (Ballen\Plexity\Exceptions\ValidationException $exception) {
    die('Password was invalid, the error was: ' . $exception->getMessage());
}

```

Tests and coverage
------------------

This library is fully unit tested using [PHPUnit](https://phpunit.de/).

I use [TravisCI](https://travis-ci.org/) for continuous integration, which triggers tests for PHP 5.6, 7.0, 7.1, 7.3 and 7.4 every time a commit is pushed.

If you wish to run the tests yourself you should run the following:

```
# Install the Plexity Library with the 'development' packages this then includes PHPUnit!
composer install

# Now we run the unit tests (from the root of the project) like so:
./vendor/bin/phpunit
```

Code coverage can also be ran but requires XDebug installed...
```
./vendor/bin/phpunit --coverage-html ./report
```

Support
-------

I am happy to provide support via. my personal email address, so if you need a hand drop me an email at: [ballen@bobbyallen.me]().


