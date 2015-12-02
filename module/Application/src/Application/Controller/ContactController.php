<?php

/* 
 * The contact controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Controller;
use \Application\Controller\CommonController;
use Zend\Session\Container;
use Zend\Mail;

class ContactController extends CommonController
{
    /**
     * The index action
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $sent = false;
        $session = new Container('captcha');
        // check if $_SESSION['captcha'] created with AJAX exists
        if(!empty($session->qaptchaKey) && $session->qaptchaKey!="not sent"){
            $myVar = $session->qaptchaKey;
            // check if the random input created exists and is empty
            if(isset($_POST[''.$myVar.'']) && empty($_POST[''.$myVar.''])){
                $sent = true;
                $mail = new Mail\Message();
                $mail->setBody($this->params()->fromPost('body'));
                $mail->setFrom($this->params()->fromPost('email'), 'alanathomas.rocks');
                $mail->addTo('alanasimonethomas@gmail.com', 'Alana Thomas');
                $mail->setSubject('alanathomas.rocks Contact Us!');
                try{
                    $transport = new Mail\Transport\Sendmail();
                    $transport->send($mail);
                } catch (\Zend\Mail\Exception\RuntimeException $ex) {
                }   
            }
        }
        $session->getManager()->getStorage()->clear('captcha');
        $view = $this->acceptableViewModelSelector($this->acceptCriteria);
        $view->setVariables(array('sent' => $sent));
        return $view;
    }
    
    /**
     * The captcha action - sets the session var is slider is used
     * @return \Zend\View\Model\JsonModel | \Zend\View\Model\ViewModel
     */
    public function captchaAction()
    {
        $session = new Container('captcha');
        $aResponse['error'] = false;
        if(isset($_POST['action']) && isset($_POST['qaptcha_key'])){
            $session->qaptchaKey = 0;
            if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'qaptcha'){
                $session->qaptchaKey = $_POST['qaptcha_key'];
            }else{
                $aResponse['error'] = true;
            }
        }else{
            $aResponse['error'] = true;
        }
        $view = new \Zend\View\Model\ViewModel(array(
            'output' => json_encode($aResponse)
        ));
        $view->setTerminal(true);
        return $view;
    }
}