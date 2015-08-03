<?php


namespace X\InventoryBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("vehicle")
 * @NamePrefix("inventory_api_")
 */
class VehicleController extends RestController
{
    /**
     * @Acl(
     *      id="inventory.vehicle_delete",
     *      type="entity",
     *      class="InventoryBundle:Vehicle",
     *      permission="DELETE"
     * )
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    public function getForm()
    {
    }

    public function getFormHandler()
    {
    }

    public function getManager()
    {
        return $this->get('inventory.vehicle_manager.api');
    }
}