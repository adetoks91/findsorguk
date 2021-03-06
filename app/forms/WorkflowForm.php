<?php
/** Form for editing workflow stages
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new WorkflowForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/TerminologyController.php
 * @version 1
*/

class WorkflowForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('workflow');

	$workflowstage = new Zend_Form_Element_Text('workflowstage');
	$workflowstage->setLabel('Work flow stage title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Workflow stage is currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');
	
	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of workflow stage: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Basic')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

    $cancel = new Zend_Form_Element_Button('cancel');
    $cancel->setAttrib('id', 'cancel')
        ->setLabel('Cancel change')
        ->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);		
	
	$this->addElements(array(
            $workflowstage, $valid, $termdesc,
            $submit, $cancel, $hash));

	$this->addDisplayGroup(array('workflowstage','termdesc','valid'), 'details');
	
	$this->details->setLegend('HER details: ');

	$this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
	
	parent::init();
	}
}