<?php
declare(strict_types=1);

namespace Pioniro\Pagination\Traits;
/**
 * @codeCoverageIgnore
 */
trait Arrayable
{
    public function current()
    {
        return $this->getIterator()->current();
    }

    public function next()
    {
        return $this->getIterator()->next();
    }

    public function key()
    {
        return $this->getIterator()->key();
    }

    public function valid()
    {
        return $this->getIterator()->valid();
    }

    public function rewind()
    {
        return $this->getIterator()->rewind();
    }

    public function offsetExists($offset)
    {
        return $this->getIterator()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getIterator()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->getIterator()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->getIterator()->offsetUnset($offset);
    }

    public function serialize()
    {
        return $this->getIterator()->serialize();
    }

    public function unserialize($serialized)
    {
        return $this->getIterator()->unserialize($serialized);
    }

    public function count()
    {
        return $this->getIterator()->count();
    }

    public function seek($position)
    {
        return $this->getIterator()->seek($position);
    }
}