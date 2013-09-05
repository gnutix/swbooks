<?php

namespace Gnutix\StarWarsLibrary\Model;

/**
 * Chronological Era
 */
class Era
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
        $this->id = $data['id'];
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
