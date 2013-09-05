<?php

namespace Gnutix\Library\Model;

/**
 * Book's category
 */
class Category
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['code'];
        $this->name = $data['name'];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
