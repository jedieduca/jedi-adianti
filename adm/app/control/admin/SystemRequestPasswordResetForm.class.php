<?php
use \Firebase\JWT\JWT;

/**
 * SystemRequestPasswordResetForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemRequestPasswordResetForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        $this->style = 'clear:both';
        // creates the form
        $this->form = new BootstrapFormBuilder('form_login');
        $this->form->setFormTitle( _t('Reset password') );
        
        // create the form fields
        $login = new TEntry('login');
        
        // define the sizes
        $login->setSize('70%', 40);
        
        $login->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        $login->placeholder = _t('User');
        $user = '<span style="float:left;margin-left:44px;height:35px;" class="login-avatar"><span class="fa fa-user"></span></span>';
        $this->form->addFields( [$user, $login] );
        
        $btn = $this->form->addAction(_t('Send'), new TAction(array($this, 'onRequest')), '');
        $btn->class = 'btn btn-primary';
        $btn->style = 'height: 40px;width: 90%;display: block;margin: auto;font-size:17px;';
        
        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; margin-top:100px;max-width:460px;';
        $wrapper->id    = 'login-wrapper';
        $wrapper->add($this->form);
        
        // add the form to the page
        parent::add($wrapper);
    }
    

    /**
     * Authenticate the User
     */
    public static function onRequest($param)
    {
        $ini = AdiantiApplicationConfig::get();
        
        try
        {
            if ($ini['permission']['reset_password'] !== '1')
            {
                throw new Exception( _t('The password reset is disabled') );
            }
            
            if (empty($ini['general']['seed']) OR $ini['general']['seed'] == 's8dkld83kf73kf094')
            {
                throw new Exception(_t('A new seed is required in the application.ini for security reasons'));
            }

            if (empty($ini['mail']))
            {
                throw new Exception('As configurações de e-mail [mail] não foram definidas no application.ini');
            }

            TTransaction::open('jedieduca');

            $login = $param['login'];
            $user  = SystemUser::newFromLogin($login);

            if (!$user instanceof SystemUser)
            {
                // Se não encontrar pelo login, tenta localizar pelo e-mail
                $user = SystemUser::newFromEmail($login);
            }

            if ($user instanceof SystemUser)
            {
                if ($user->active == 'N')
                {
                    throw new Exception(_t('Inactive user'));
                }
                else
                {
                    $key = APPLICATION_NAME . $ini['general']['seed'];
                    
                    $token = array(
                        "user" => $user->login,
                        "expires" => strtotime("+ 3 hours")
                    );
                    
                    $jwt = JWT::encode($token, $key);
                    
                    $referer = $_SERVER['HTTP_REFERER'] ?? '';
                    $pos     = strpos($referer, 'index.php');
                    $url     = ($pos !== false) ? substr($referer, 0, $pos) : '';
                    $url .= 'index.php?class=SystemPasswordResetForm&method=onLoad&jwt='.$jwt;
                    
                    $replaces = [];
                    $replaces['name'] = $user->name;
                    $replaces['link'] = $url;
                    $html = new THtmlRenderer('app/resources/system_reset_password.html');
                    $html->enableSection('main', $replaces);
                    
                    // --- ENVIO VIA TMAIL COM OS DADOS DO APPLICATION.INI ---
                    $mailConfig = $ini['mail'];

                    if (empty($user->email))
                    {
                        throw new Exception('Usuário sem e-mail cadastrado');
                    }

                    $mail = new TMail;
                    $mail->SetUseSmtp( !empty($mailConfig['auth']) );
                    $mail->SetSmtpHost($mailConfig['host'], (int) $mailConfig['port']);
                    $mail->SetSmtpUser($mailConfig['user'], $mailConfig['pass']);

                    // TMail não expõe setter de segurança; usa a instância interna do PHPMailer
                    if (!empty($mailConfig['secure']))
                    {
                        $mail->getInternalInstance()->SMTPSecure = $mailConfig['secure'];
                    }

                    $mail->setFrom($mailConfig['from'], $mailConfig['name'] ?? 'JEDi Educa');
                    $mail->addAddress($user->email, $user->name);
                    $mail->setSubject(_t('Password reset'));
                    $mail->setHtmlBody($html->getContents());

                    $mail->send();

                    TTransaction::close();

                    new TMessage('info', _t('Message sent successfully'));
                }
            }
            else
            {
                throw new Exception(_t('User not found'));
            }
        }
        catch (Throwable $e)
        {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
}
