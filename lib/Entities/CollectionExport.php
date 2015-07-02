<?php namespace Ballen\Plexity\Entites;

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
class CollectionExport
{

    private $collection;

    /**
     * Initiate the CollectionExport object.
     * @param array $collection The collection.
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * The total number of items in the collection.
     * @return integer
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Return the contents of the collection as an array.
     * @return array
     */
    public function toArray()
    {
        return $this->collection;
    }

    /**
     * Return the contents of the collection as an stdClass object.
     * @return stdClass
     */
    public function toObject()
    {
        return (object) $this->toArray();
    }

    /**
     * Return the contents of the collection as a JSON encoded string.
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->collection);
    }

    /**
     * Iterate over the collection
     * @param \Ballen\Plexity\Entites\callable $callback Callback
     */
    public function each(callable $callback)
    {
        foreach ($this->collection as $item) {
            $callback($item);
        }
    }

    /**
     * Default return value on the object, will return the collection array.
     * @return array
     */
    public function __toString()
    {
        return $this->collection;
    }
}
