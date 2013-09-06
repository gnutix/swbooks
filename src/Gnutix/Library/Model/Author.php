<?php

namespace Gnutix\Library\Model;

/**
 * Book's author
 */
class Author
{
    /** @var string */
    protected $name;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
