<?php

/* 
 * The blog comment encoder
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service\Encoder;
use \Application\Service\Encoder\CommonEncoder;

class BlogComment extends CommonEncoder
{
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array('id'         => 'id',
                           'title'      => 'title',
                           'createdBy'  => 'created_by',
                           'body'       => 'body',
                           'upVotes'    => 'up_votes',
                           'downVotes'  => 'down_votes',
                           'blogPostId' => 'blog_post_id');

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'blog_comment';
    }
}