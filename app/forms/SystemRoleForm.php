<?php
/** Form for setting up system roles
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class SystemRoleForm extends Pas_Form {
	
public function __construct(array $options) {

	parent::__construct($options);
		  
	$this->setName('systemroles');
	
	
	$role = new Zend_Form_Element_Text('role');
	$role->setLabel('Staff role title: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',60)
	->addErrorMessage('Choose title for the role.');
	
	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Role description: ')
	->setRequired(true)
	->setAttribs(array('rows' => 10, 'cols' => 80))
	->addFilters(array('StringTrim','WordChars','BasicHtml','EmptyParagraph'));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array($hash, $role, $description, $submit));
	
	$this->addDisplayGroup(array('role','description','valid'), 'details')->removeDecorator('HtmlTag');
	$this->details->setLegend('Activity details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
	}
}