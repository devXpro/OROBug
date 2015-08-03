<?php


namespace Oro\BugBundle\Services;

class DataConverter
{
    /** @var array */
    private static $typeMap = [1 => 'Bug', 2 => 'Subtask', 3 => 'Task', 4 => 'Story'];

    /**
     * @param $id integer
     * @return string
     */
    public function typeIdToName($id)
    {

        return self::$typeMap[$id];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return self::$typeMap;
    }
}