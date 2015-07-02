<?php namespace Ballen\Passplexity\Entites;

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
class Collection
{

    /**
     * The collection data.
     * @var array
     */
    private $items = [];

    public function __construct($items = null)
    {
        if (!is_null($items) && is_array($items)) {
            $this->push($items);
        }
    }

    /**
     * Put a set of items to the collection (resetting the current collection)
     * @param array $items
     * @return \Ballen\Passplexity\Entites\Collection
     */
    public function put(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Push a new item (or collection of items) into the collection onto the end
     * of the collection.
     * @param array $items
     * @return \Ballen\Passplexity\Entites\Collection
     */
    public function push(array $items)
    {
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Get all items from the collection.
     * @return \Ballen\Passplexity\Entites\CollectionExport
     */
    public function all()
    {
        return new CollectionExport($this->items);
    }

    /**
     * Get a specific item from the collection.
     * @param string $key The collection (array) key to return.
     * @param string $default Optional default value if the key doesn't exist (defaulted to false)
     * @return string
     */
    public function get($key, $default = false)
    {
        if (!isset($this->items[$key])) {
            return $default;
        }
        return (string) $this->items[$key];
    }

    /**
     * The total number of items in the collection.
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
