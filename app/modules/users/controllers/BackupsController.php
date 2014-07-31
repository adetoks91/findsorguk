<?php
/** Controller for accessing specific user details for IP logins etc
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Users_BackupsController extends Pas_Controller_Action_Admin {

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()  {
	$this->_helper->_acl->allow('flos',null);
        parent::init();
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }
}