<?php

namespace Concrete\Tests\Validator;

use Concrete\Core\Validator\ValidatorManagerInterface;
use Concrete\TestHelpers\CreateClassMockTrait;
use Concrete\Tests\TestCase;

class ValidatorManagerTest extends TestCase
{
    use CreateClassMockTrait;

    /** @var ValidatorManagerInterface */
    protected $manager;

    public function setUp():void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->manager = new \Concrete\Core\Validator\ValidatorManager();
    }

    public function TearDown():void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub

        $this->manager = null;
    }

    public function testAddValidator()
    {
        $manager = $this->manager;

        $this->assertEmpty($manager->getValidators(), 'Manager should not initialize with validators.');
        $manager->setValidator('test', $mock = $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface'));

        $this->assertEquals(['test' => $mock], $manager->getValidators(), 'Unable to set validator to manager');
    }

    public function testHas()
    {
        $manager = new \Concrete\Core\Validator\ValidatorManager();

        $this->assertFalse($manager->hasValidator('test'), 'Manager should not initialize with validators.');
        $manager->setValidator('test', $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface'));

        $this->assertTrue($manager->hasValidator('test'), 'Manager does not properly report set validator');
    }

    public function testGetRequirements()
    {
        $mock_1 = $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface');
        $mock_2 = $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface');

        $mock_1->method('getRequirementStrings')->willReturn([
            1 => 'string 1',
        ]);
        $mock_2->method('getRequirementStrings')->willReturn([
            1 => 'string 2',
        ]);

        $manager = $this->manager;
        $manager->setValidator('mock_1', $mock_1);
        $manager->setValidator('mock_2', $mock_2);

        $this->assertEquals(['string 1', 'string 2'], $manager->getRequirementStrings());
    }

    public function testValidation()
    {
        $mock_1 = $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface');
        $mock_2 = $this->createMockFromClass('\Concrete\Core\Validator\ValidatorInterface');

        $mock_1->expects($this->once())->method('isValid')->willReturn(false);
        $mock_2->expects($this->once())->method('isValid')->willReturn(true);

        $manager = $this->manager;
        $manager->setValidator('mock_1', $mock_1);
        $manager->setValidator('mock_2', $mock_2);

        $this->assertFalse($manager->isValid(''));
    }
}
