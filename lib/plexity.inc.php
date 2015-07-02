<?php
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
 * @link https://github.com/bobsta63/passplexity
 * @link http://www.bobbyallen.me
 *
 */
/* * *****************************************************************************
 * THIS FILE SHOULD BE USED FOR AUTOMATICALLY LOADING THIS LIBRARY WHEN YOU ARE
 *  USING IT "STANDALONE" AND NOT USING COMPOSER OR ANOTHER PACKAGE MANAGER.
 */

$includes = array(
    'Plexity.php',
    'Entities/Collection.php',
    'Entities/CollectionExport.php',
    'Entities/PreviousPasswordsCollection.php',
    'Exceptions/ValidationException.php'
);

foreach ($includes as $file) {
    require_once dirname(__FILE__) . '/' . $file;
}