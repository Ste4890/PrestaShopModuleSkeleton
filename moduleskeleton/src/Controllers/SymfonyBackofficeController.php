<?php

namespace StefanoPelagotti\Module\Controllers;

/**
 * Copyright (c) of  Prestart
 *
 * Example of Symfony Backoffice controller with a grid.
 * @see config/routes.yml
 * @see config/services.yml
 *
 */


use StefanoPelagotti\PrestartEkoSync\Constraints\Locations;


use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

/**
 * Backoffice Controller Class.
 * todo: grid boilerplate
 * @see https://devdocs.prestashop.com/1.7/development/components/grid/
 */
class SymfonyBackofficeController extends FrameworkBundleAdminController {

    public function displayAction() : Response {
        return $this->render(Locations::TWIG_ADMIN_TEMPLATES . 'page.html.twig', [ ]);
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function editAction(Request $request) : Response {

        return $this->render(Locations::TWIG_ADMIN_TEMPLATES . 'page.html.twig', [ ]);
    }

    public function submitAction(Request $request) {

        $data = $request->get('a_submitted_key');
        return $this->editAction($request);

    }
}
