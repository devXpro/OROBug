<?php
namespace Oro\BugBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\OrganizationBundle\Migrations\Data\ORM\LoadOrganizationAndBusinessUnitData;
use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\UserManager;
use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const MANAGER = 'manager';
    const USER = 'user';
    const ADMIN = 'admin';
    /**
     * @var UserManager
     */
    protected $userManager;
    /**
     * @var string[]
     */
    static private $names = [
        'Noah',
        'Emma',
        'Ethan',
        'Oliver',
        'Evelyn',
        'Evelyn',
        'Madison',
        'Emily',
        'Hannah',
        'Zoe',
        'Mason',
        'Elizabeth',
        'Jack',
        'Ella',
        'Jacob',
        'Madison',
        'Oliver',
        'Avery',
        'Jackson',
        'Charlotte',
        'Logan',
        'Alexander',
        'Addison',
        'Aria',
        'Natalie',
    ];
    /**
     * @var string[]
     */
    static private $lastName = [
        'Moore',
        'Anderson',
        'Taylor',
        'Thomas',
        'White',
        'Harris',
        'Garcia',
        'Robinson',
        'Lee',
        'Hall',
        'Allen',
        'Martin',
        'Rodriguez',
        'Lewis',
        'Clark',
        'Martinez',
        'Walker',
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData'];
    }


    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->userManager = $container->get('oro_user.manager');
    }


    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $roles = [LoadRolesData::ROLE_USER, LoadRolesData::ROLE_ADMINISTRATOR, LoadRolesData::ROLE_ADMINISTRATOR];
//        $this->addUser($manager, LoadRolesData::ROLE_ADMINISTRATOR, self::ADMIN);
        $this->addUser($manager, LoadRolesData::ROLE_USER, self::USER);
        $this->addUser($manager, LoadRolesData::ROLE_MANAGER, self::MANAGER);
        for ($i = 0; $i < 10; $i++) {
            $this->addUser($manager, $roles[mt_rand(0, 2)]);
        }
    }

    /**
     * @param ObjectManager $manager
     * @param $role string
     * @param $userName string|null
     */
    private function addUser(ObjectManager $manager, $role, $userName = null)
    {
        $role = $manager->getRepository('OroUserBundle:Role')
            ->findOneBy(['role' => $role]);

        if (!$role) {
            throw new \RuntimeException('role should exist.');
        }

        $businessUnit = $manager
            ->getRepository('OroOrganizationBundle:BusinessUnit')
            ->findOneBy(['name' => LoadOrganizationAndBusinessUnitData::MAIN_BUSINESS_UNIT]);
        /** @var EntityRepository $repo */
        $repo = $manager->getRepository('OroOrganizationBundle:Organization');
        /** @var Organization $organization */
        $organization = $repo->findOneBy(['name' => LoadOrganizationAndBusinessUnitData::MAIN_ORGANIZATION]);
        /** @var User $adminUser */
        $adminUser = $this->userManager->createUser();
        $firstName = self::$names[mt_rand(0, count(self::$names) - 1)];
        $lastName = self::$lastName[mt_rand(0, count(self::$lastName) - 1)];
        if (!$userName) {
            $userName = $firstName.$lastName;
        }
        $adminUser
            ->setUsername($userName)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($userName.'@ukr.net')
            ->setEnabled(true)
            ->setOwner($businessUnit)
            ->setPlainPassword($userName)
            ->addRole($role)
            ->addBusinessUnit($businessUnit)
            ->setOrganization($organization)
            ->addOrganization($organization);
        $this->userManager->updateUser($adminUser);
    }
}
