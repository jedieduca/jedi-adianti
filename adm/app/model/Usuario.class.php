<?php
/**
 * Active Record for table usuario2
 * @author  Claudio A. Passos
 */
class Usuario extends TRecord
{
    const TABLENAME = 'usuario2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('login');
        parent::addAttribute('senha');
        parent::addAttribute('administrador');
        parent::addAttribute('codColegio');
        parent::addAttribute('idturma');
        parent::addAttribute('criador');
    }

   /**
     * @return Collection of SystemGroup
     */
    /*public function getAdm()
    {
        $groups = array();
        $document_groups = SystemDocumentGroup(seria uma classe)::where('document_id', '=', $this->id)->load();
        if ($document_groups)
        {
            TTransaction::open('adianti_cadjogos');
            foreach ($document_groups as $document_group)
            {
                $groups[] = new SystemGroup( $document_group->system_group_id );
            }
            TTransaction::close();
        }
        return $groups;
    }

    public function getAdmId()
    {
        $adms = $this->getAdm();
        $adm_ids = array();
        if ($adms)
        {
            foreach ($adms as $adm)
            {
                $adm_ids[] = $adm->id;
            }
        }
        return $adm_ids;
    }*/

    public static function authenticate($login, $password)
    {
        $user = self::newFromLogin($login);
        /*if (!hash_equals($user->password, md5($password)))
        {
            throw new Exception(_t('Wrong password'));
        }*/
        
        return $user;
    }

    public static function validate($login)
    {
        $user = self::newFromLogin($login);       
        return $user;
    }
    static public function newFromLogin($login)
    {
        /*$conn = TTransaction::get();
        // run query
        $sql='select login FROM usuario2 ';
        $sql.='WHERE login="'.$login.'"';

        echo '<pre>'; print_r($sql);
        $result = $conn->query($sql);
        return $result->rowCount();*/


        return self::where('login', '=', $login)->first();
    }


}
?>
