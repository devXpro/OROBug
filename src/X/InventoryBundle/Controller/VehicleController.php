<?php

namespace X\InventoryBundle\Controller;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use X\InventoryBundle\Entity\Vehicle;

/**
 * @RouteResource("vehicle")
 * @NamePrefix("inventory_api_")
 */
class VehicleController extends Controller
{
    /**
     * @Route("/gr", name="inventory.vehicle_index")
     * @Template
     * @Acl(
     *     id="inventory.vehicle_view",
     *     type="entity",
     *     class="InventoryBundle:Vehicle",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return array('entity_class' => 'X\InventoryBundle\Entity\Vehicle');
    }

    /**
     * @Route("/create", name="inventory.vehicle_create")
     * @Template("InventoryBundle:Vehicle:update.html.twig")
     * @Acl(
     *     id="inventory.vehicle_create",
     *     type="entity",
     *     class="InventoryBundle:Vehicle",
     *     permission="CREATE"
     * )
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->update(new Vehicle(), $request);
    }

    /**
     * @Route("/update/{id}", name="inventory.vehicle_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="inventory.vehicle_update",
     *     type="entity",
     *     class="InventoryBundle:Vehicle",
     *     permission="EDIT"
     * )
     * @param Vehicle $vehicle
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Vehicle $vehicle, Request $request)
    {
        return $this->update($vehicle, $request);
    }


    private function update(Vehicle $vehicle, Request $request)
    {
        $form = $this->get('form.factory')->create('inventory_vehicle', $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'inventory.vehicle_update',
                    'parameters' => array('id' => $vehicle->getId()),
                ),
                array('route' => 'inventory.vehicle_index'),
                $vehicle
            );
        }

        return array(
            'entity' => $vehicle,
            'form' => $form->createView(),
        );
    }


    /**
     * @Route("inv/{id}", name="inventory.vehicle_view", requirements={"id"="\d+"})
     * @Template
     * @AclAncestor("inventory.vehicle_view")
     * @param Vehicle $vehicle
     * @return array
     */
    public function viewAction(Vehicle $vehicle)
    {
        return array('entity' => $vehicle,'vehicle'=>$vehicle);
    }
}

