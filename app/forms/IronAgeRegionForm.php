<?php
/** Form for editing and creating Iron Age regional data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new IronAgeRegionForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/NumismaticsController.php
 * @uses Tribes
 *
*/
class IronAgeRegionForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        $tribes = new Tribes();
	$tribes_options = $tribes->getTribes();

	parent::__construct($options);

	$this->setAttrib('accept-charset', 'UTF-8');

	$this->setName('ironageregion');

	$area = new Zend_Form_Element_Text('area');
	$area->setLabel('Area: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter an area name.');

	$region = new Zend_Form_Element_Text('region');
	$region->setLabel('Region name: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a region name');

	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Description: ')
		->setRequired(true)
		->setAttrib('rows',5)
		->setAttrib('cols',60)
		->setAttrib('ToolbarSet','Finds')
		->setAttrib('Height',250)
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$tribe = new Zend_Form_Element_Select('tribe');
	$tribe->setLabel('Associated tribe: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultioptions(array(
                    null => 'Choose a tribe',
                    'Available tribes' => $tribes_options
                ))
		->addValidator('inArray', false, array(array_keys($tribes_options)))
		->addErrorMessage('You must enter a tribe from the dropdown.')
		->addValidator('Int')
		->addErrorMessage('You must enter a tribe.');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this area valid: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->addErrorMessage('You must set the validity');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$area, $region, $tribe,
	$valid, $description, $submit)
	);

	$this->addDisplayGroup(array(
	'area', 'region', 'tribe',
	'description','valid','submit'),
	'details');


	parent::init();
    }
}
