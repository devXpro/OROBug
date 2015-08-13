<?php

namespace Oro\BugBundle\Tests\Unit\Form\Type;

use Doctrine\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractFormTestCase extends TypeTestCase
{
    /**
     *
     * Example $paramsSet :
     * array(
     *      array(new User(),new User()),  'some_form_type', array('multiple'=>true)),
     *      array(new Issue(),new Issue()),'some_another_form_type')
     * )
     * @param array $paramsSet
     * @return array
     */
    protected function getEntityStubs(array $paramsSet)
    {
        $result = [];
        foreach ($paramsSet as $params) {
            if (!isset($params[2])) {
                $params[2] = [];
            }
            $stub = new EntityTypeStub($params[0], $params[1], $params[2]);
            $result[$stub->getName()] = $stub;
        }

        return $result;
    }

    /**
     * @param $obj
     */
    protected function checkSelectors($obj)
    {
        $this->assertTrue(method_exists($obj, 'getName'));
        $this->assertTrue(method_exists($obj, 'configureOptions'));
        $this->assertTrue(method_exists($obj, 'getParent'));
        /** @var AbstractType $obj */

        $this->assertContains($obj->getParent(), ['entity', 'choice']);
        $this->assertInternalType('string', $obj->getName());
        /** @var OptionsResolverInterface | \PHPUnit_Framework_MockObject_MockObject $optionsResolverMock */
        $optionsResolverMock = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $optionsResolverMock->setexpects($this->once())->method('setDefaults')->with(
            $this->logicalAnd(
                $this->isType('array'),
                $this->logicalOr($this->arrayHasKey('class'), $this->arrayHasKey('choices'))
            )
        );
        $obj->setDefaultOptions($optionsResolverMock);
    }

    /**
     * @param $entityName
     * @param $fields
     * @param int $setsQuantity
     * @return Entity[]
     */
    protected function getEntitySet($entityName, $fields, $setsQuantity = 3)
    {
        $result = [];
        for ($i = 1; $i < $setsQuantity; $i++) {
            $entity = new $entityName();
            $idReflection = new \ReflectionProperty(get_class($entity), 'id');
            $idReflection->setAccessible(true);
            $idReflection->setValue($entity, $i);
            foreach ($fields as $field) {
                $setter = 'set'.ucfirst($field);
                $entity->$setter($field.'_'.$i);
            }

            $result[$i] = $entity;
        }

        return $result;
    }

    /**
     * @param $entityName
     * @param $fields
     * @param bool|false $withoutId
     * @return mixed
     */
    protected function getEntity($entityName, $fields, $withoutId = false)
    {
        $entity = new $entityName();
        if (!$withoutId) {
            $idReflection = new \ReflectionProperty(get_class($entity), 'id');
            $idReflection->setAccessible(true);
            $idReflection->setValue($entity, 777);
        }
        foreach ($fields as $field) {
            if (is_scalar($field)) {
                $setter = 'set'.ucfirst($field);
                $entity->$setter($field);
            }
        }

        return $entity;
    }
}
