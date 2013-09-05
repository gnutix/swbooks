<?php

namespace Gnutix\Library\Model;

/**
 * Books Editor
 */
class Editor
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $preferredLanguage;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['code'];
        $this->name = $data['name'];
        $this->preferredLanguage = $data['lang'];
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

    /**
     * @return mixed
     */
    public function getPreferredLanguage()
    {
        return $this->preferredLanguage;
    }
}
