<?php

namespace Oro\BugBundle\Tests\Unit\Form\Type;

class IssueTypeStub
{
    /** @var  string */
    private $id;

    /** @var  string */
    private $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->id;
    }
}
