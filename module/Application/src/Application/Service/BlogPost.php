<?php

/* 
 * The blog comment controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service;
use \Application\Service\CommonService;
 
class BlogPost extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'blog_post';
    }
    
    /**
     * Parse the legacy data
     * @return boolean
     */
    public function parseLegacyData()
    {
        //$this->delete(); //delete all existing info
        $this->table      = 'blog_comment';
        //$this->delete(); //delete all existing info
        $this->table      = 'blog_posts';
        $this->primaryKey = 'uid';
        $rs = $this->fetchAll(null, null, true);
        $this->table      = 'blog_posts';
        foreach($rs as $row){
            $this->table = "blog_comments";
            $this->primaryKey = "uid";
            $commentsRs = $this->fetchAll(array('blog_posts_id' => $row['uid']), null, true);
            $this->table = "blog_post";
            $this->primaryKey = 'id';
            $params = array('title'       => $row['title'],
                            'created_by'  => 'Dean Clow',
                            'body'        => $row['label'],
                            'date'        => $row['created'],
                            'brief_body'  => strip_tags(substr($row['label'], 0, 200)),
                            'title_image' => 'No image');
            $id     = $this->insertNoObject($params);
            foreach($commentsRs as $commentRow){
                $this->table = "blog_comment";
                $this->primaryKey = 'id';
                $params = array('title'         => $commentRow['title'],
                                'created_by'    => $commentRow['email_address'],
                                'body'          => $commentRow['label'],
                                'date'          => $commentRow['created'],
                                'blog_post_id'  => $id);
                $this->insertNoObject($params);
            }
        }
        return true;
    }
}