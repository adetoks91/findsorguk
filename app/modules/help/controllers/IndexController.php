<?php
/** Controller for index of help topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/

class Help_IndexController extends Zend_Controller_Action {
	/** Setup the ACL.
	*/	
	public function init(){
	$this->_helper->acl->allow('public',null);
	}
	/** Display help index 
	*/	
	public function indexAction(){
	$content = new Content();
	$this->view->contents = $content->getFrontContent('help');
	}

}