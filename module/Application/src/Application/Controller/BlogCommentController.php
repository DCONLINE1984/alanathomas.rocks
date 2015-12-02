<?php

/* 
 * The blog comment controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Controller;
use \Application\Controller\CommonController;

class BlogCommentController extends CommonController
{
    /**
     * The index action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $service = $this->getServiceLocator()->get("Application\Service\BlogComment");
        $results = $service->fetchAll();
        print '<pre>';
        print_r($results);
        exit;
        return $this->acceptableViewModelSelector($this->acceptCriteria);
    }
}