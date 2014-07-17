<?php
/** Controller for Iron Age period's index page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class IronAgeCoins_IndexController extends Pas_Controller_Action_Admin {
	
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
 	$this->_helper->_acl->allow(null);
    }

	/** Set up data for the index page
	*/
	public function indexAction() {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('ironagecoins');
	}
}
