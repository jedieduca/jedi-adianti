<?php
error_reporting(0);
/**
 * FormUserTemaList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 * @copyright  Copyright (c) 2021 Memore. (http://www.memore-net.com)
 * @license    http://www.memore-net.com/license
 */

class FormUsuarioTema extends TPage
{
	private $form;
	
	public function __construct($param)
	{
		parent::__construct();
		$this->form = new BootstrapFormBuilder('form_usuario_tema');
        $this->form->setFormTitle('Usuário / Temas');
        $this->form->setFieldSizes('100%');
        $this->form->generateAria();
	//}
	//public function onView($param)
	//{
		$key = $param['key'];

		if ($_GET['method']=='onEdit') 
			TSession::setValue("usuarioSel", $key);
		//echo '<pre>'; print_r('key-> '.$key); echo '</pre>';
		//echo '<pre>'; print_r('metodo-> '.$_GET['method']); echo '</pre>';
		//echo '<pre>'; print_r($GLOBALS); echo '</pre>';
		try
		{
  
			/*TTransaction::open('permission');
            $user=SystemUser::newFromUserId( $key);
			$this->form->addFields( [new TLabel('Usuário')], [$user->name] );
			$this->form->addFields( [new TLabel('Login')], [$user->login], [new TLabel('Email')], [$user->email] );
			$this->form->addFields(  );
			TTransaction::close();*/

			  
			TTransaction::open('jedieduca');
			//$usuario = new SystemUser($key);
            $user=new Usuario($key);
			$this->form->addFields( [new TLabel('Usuário')], [$user->nome] );
			$this->form->addFields( [new TLabel('Login')], [$user->login] );
			$this->form->addFields(  );
			TTransaction::close();


	
			// wrap the page content using vertical box
			$vbox = new TVBox;
			$vbox->style = 'width: 100%';
			$vbox->add(new TXMLBreadCrumb('menu.xml', 'FormUsuarioTemaList'));
			$vbox->add($this->form);
			
			parent::add($vbox);
		}
		catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
		
		$table = new TTable;
		$this->form->addFields([$table]);  		//$this->form->add($table);
		$fields = array();

		TTransaction::open('jedieduca');
        $repositorio = new TRepository('Tema');
        $criteria = new TCriteria();
		if (strlen(array_search(1,TSession::getValue('usergroupids')))==0)
        	$criteria->add(new TFilter("idautor", "=", TSession::getValue('userid')));
        $repositorio->load($criteria);
		$tema = new TDBCheckGroup('tema_list', 'jedieduca', 'Tema', 'id', 'nome','nome', $criteria);
		$fields[] = $tema;
		TTransaction::close();

		$userId = new THidden('userid');
		$userId->setValue($key);
		$fields[] = $userId;

		$table->addRow()->addCell(new TLabel('Temas'));
		$cell = $table->addRow()->addCell($userId);
		$cell->add($tema);

		$table->addRow()->addCell('&nbsp;');

   		$btnSalvar = new TButton('salvar');
		$btnSalvar->class = 'btn btn-sm btn-primary';
		$btnSalvar->setImage('far:save'); 
		$btnSalvar->setAction(new TAction(array($this, 'onSave')), 'Salvar');
		$fields[] = $btnSalvar;

   		$btnCancelar = new TButton('cancelar');
		$btnCancelar->setImage('far:arrow-alt-circle-left blue'); 
		$action1 = new TAction(array('FormUsuarioTemaList','onReload'));
		if (TSession::getValue('usuarioSel')):
			$action1->setParameter('key', TSession::getValue('usuarioSel'));
		endif;
		$btnCancelar->setAction($action1, 'Voltar');
		$fields[] = $btnCancelar;

        $cell = $table->addRow()->addCell($btnSalvar);
        $cell->add($btnCancelar);
        $cell->align = 'right';

		$this->form->setFields($fields);

	}

    public function onSave($param)
    {
		//echo '<pre>'; print_r($param); echo '</pre>';
		$userId = TSession::getValue("usuarioSel");
        try
        {
            TTransaction::open('jedieduca');
            $objeto = $this->form->getData();

            $repos = new TRepository('UsuarioTema');  
	    	$criteria = new TCriteria;
	    	$criteria->add(new TFilter('userid', '=', $userId));
	    	$obj = $repos->load($criteria);
			$jaassociado = array();
			foreach($obj as $oid =>$tema ):
				if (!in_array($tema->idtema, $objeto->tema_list)):
					$tema->delete();
				else:
					$jaassociado[] = $tema->idtema;
				endif;
			endforeach;
			//echo '<pre>'; print_r($objeto); echo '</pre>';
            foreach($objeto->tema_list as $aid => $tema ):
				if (!in_array($tema, $jaassociado)):
					$obj = new UsuarioTema;
					$obj->idtema = $tema;
					$obj->userid = $userId;
					echo '<pre>'; print_r($tema); echo '</pre>';
					$obj->store();
				endif;
			endforeach;

			$action1 = new TAction(array('FormUsuarioTemaList', 'onReload'));
			$action1->setParameter('key', $objeto->userid);
			new TMessage('info', _t('Record saved'), $action1);
			//new TMessage('info', $userId, $action1);
            
            TTransaction::close();
        }
        catch (Exception $e) // em caso de exceção
        {
            new TMessage('error', '<b>Erro</b> ' . $e->getMessage());
            TTransaction::rollback();
        }

    }

	function onEdit($param)
    {
		$key = $param['key'];
        try
        {
            if (isset($param['key']))
            {
                $key=$param['key'];
                TTransaction::open('jedieduca');
                $repos = new TRepository('UsuarioTema');
		    	$criteria = new TCriteria;
		    	$criteria->add(new TFilter('userid', '=', $key));
		    	$obj = $repos->load($criteria);
				$tema_list = array();
				foreach($obj as $tema ):
					$tema_list[] = $tema->idtema;
				endforeach;

				$object = new StdClass();
				$object->tema_list = $tema_list;
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
?>
