<?php

/* 
 * The blog post controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Controller;
use \Application\Controller\CommonController;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class BlogPostController extends CommonController
{
    /**
     * The index action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $service = $this->getServiceLocator()->get("Application\Service\BlogPost");
        $results = $service->fetchAll(null, 'date DESC', null, 5); //limit 5
        $results = $this->getServiceLocator()->get("Application\Service\BlogComment")->attachComments($results);
        $view = $this->acceptableViewModelSelector($this->acceptCriteria);
        $view->setVariables(array(
            'posts' => $results
        ));
        return $view;
    }
    
    /**
     * The index action for blog pagination
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function pageAction()
    {
        return $this->indexAction();
    }
    
    /**
     * Show a full single post
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function showAction()
    {
        $service = $this->getServiceLocator()->get("Application\Service\BlogPost");
        $results = $service->fetchById($this->params()->fromRoute("id"));
        $results = $this->getServiceLocator()->get("Application\Service\BlogComment")->attachComments(array($results));
        $view = $this->acceptableViewModelSelector($this->acceptCriteria);
        $view->setVariables(array(
            'post' => $results[0]
        ));
        return $view;
    }
    
    /**
     * The add blog post action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $isSubmitted = $this->params()->fromPost('submitted', 
                                                null);
        $model  = new \Application\Model\BlogPost();
        if($isSubmitted){
            $date = $this->params()->fromPost("date");
            $date = new \DateTime($date);
            $date = $date->format('Y-m-d');
            //add a new blog entry
            $model  ->setBody($this->params()->fromPost('body'))
                    ->setCreatedBy("Alana Thomas")
                    ->setDate($date)
                    ->setTitle($this->params()->fromPost('title'))
                    ->setTags($this->params()->fromPost('tags'));
            $model  = $this->getServiceLocator()->get("Application\Service\BlogPost")->insert($model);
            //redirect the user here
            return $this->redirect()->toRoute('blog-post');
        }
        $view = $this->acceptableViewModelSelector($this->acceptCriteria);
        $view->setVariables(array(
            'model' => $model
        ));
        return $view;
    }
    
    /**
     * Edit a blog post
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $id          = $this->params()->fromRoute("id");
        $isSubmitted = $this->params()->fromPost('submitted', 
                                                null);
        $model  = $this->getServiceLocator()->get("Application\Service\BlogPost")->fetchById($id);
        if($isSubmitted){
            $date = $this->params()->fromPost("date");
            $date = new \DateTime($date);
            $date = $date->format('Y-m-d');
            //edit a blog entry
            $model  ->setBody($this->params()->fromPost('body'))
                    ->setTags($this->params()->fromPost('tags'))
                    ->setTitle($this->params()->fromPost('title'))
                    ->setDate($date);
            $model  = $this->getServiceLocator()->get("Application\Service\BlogPost")->update($model);
            //redirect the user here
            return $this->redirect()->toRoute('blog-post');
        }
        $view = $this->acceptableViewModelSelector($this->acceptCriteria);
        $view->setVariables(array(
            'model' => $model
        ));
        return $view;
    }
    
    /**
     * Delete a blog post
     * @return void
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute("id");
        $result = $this->getServiceLocator()->get("Application\Service\BlogPost")->delete($id);
        return $this->redirect()->toRoute('blog-post');
    }
    
    /**
     * Parse the old blog data into the new system (only run once)
     * @return \Zend\View\Model\ViewModel
     */
    public function parseLegacyDataAction()
    {
        $result = $this->getServiceLocator()->get("Application\Service\BlogPost")->parseLegacyData();
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array(
            'result' => (int)$result
        ));
        return $view;
    }
    
    /**
     * Up-vote a blog post
     * @return void
     */
    public function upVoteAction()
    {
        $id = $this->params()->fromRoute("id");
        $model = $this->getServiceLocator()->get("Application\Service\BlogPost")->fetchById($id);
        $currentVotes = $model->getUpVotes();
        $model->setUpVotes(($currentVotes+1));
        $result = $this->getServiceLocator()->get("Application\Service\BlogPost")->update($model);
        return $this->redirect()->toRoute('blog-post');
    }
    
    /**
     * Down-vote a blog post
     * @return void
     */
    public function downVoteAction()
    {
        $id = $this->params()->fromRoute("id");
        $model = $this->getServiceLocator()->get("Application\Service\BlogPost")->fetchById($id);
        $currentVotes = $model->getDownVotes();
        $model->setDownVotes(($currentVotes+1));
        $result = $this->getServiceLocator()->get("Application\Service\BlogPost")->update($model);
        return $this->redirect()->toRoute('blog-post');
    }
    
    /**
     * Load a post on the fly (infinite scrolling)
     * @return void
     */
    public function loadPostAction()
    {
        $id = $this->params()->fromRoute("id");
        $posts = $this->getServiceLocator()->get("Application\Service\BlogPost")->fetchAll(null, 'date DESC');
        $model = $posts[$id];
        $view = new \Zend\View\Model\ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('body'   => $model->getBody(),
                                  'nextId' => ($id+1),
                                  'id'     => $model->getId(),
                                  'title'  => $model->getTitle()));
        return $view;
    }
}