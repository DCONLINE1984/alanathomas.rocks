<?php

/* 
 * The blog comment controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service;
use \Application\Service\CommonService;

class BlogComment extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'blog_comment';
    }
    
    /**
     * Attach the comments to a blog post
     * @param  array $rs
     * @return array
     */
    public function attachComments($rs)
    {
        $datasource = array();
        foreach($rs as $postModel){
            $postModel = $postModel->toArray();
            $postModel['comments'] = $this->fetchAll(array('blog_post_id' => $postModel['id']));
            $datasource[] = $postModel;
        }
        return $datasource;
    }
}