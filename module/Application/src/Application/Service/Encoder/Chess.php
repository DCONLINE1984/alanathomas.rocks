<?php

/* 
 * The blog post encoder
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service\Encoder;
use \Application\Service\Encoder\CommonEncoder;

class Chess extends CommonEncoder
{
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array('id'             => 'id',
                           'whitePlayer'    => 'white_player',
                           'blackPlayer'    => 'black_player',
                           'whiteRating'    => 'white_rating',
                           'blackRating'    => 'black_rating',
                           'roundNumber'    => 'round_number',
                           'date'           => 'date',
                           'site'           => 'site',
                           'pgnString'      => 'pgn_string',
                           'result'         => 'result');
 
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'chess';
    }
}