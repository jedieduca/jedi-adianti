<?php

use Adianti\Database\TRecord;

class DistributionNewsCategory extends TRecord
{
    const TABLENAME  = 'vw_distribuicao_noticias_categoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('categoria');
        parent::addAttribute('fake_qt');
        parent::addAttribute('fake_perc');
        parent::addAttribute('nao_fake_qt');
        parent::addAttribute('nao_fake_perc');
    }
}