<?php

/** Form for suggesting research topics
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $form = new SuggestedForm();
 * ?>
 * </code>
 * @category   Pas
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Periods
 * @uses ProjectTypes
 */
class SuggestedForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        $projecttypes = new ProjectTypes();
        $projectype_list = $projecttypes->getTypes();

        $periods = new Periods();
        $period_options = $periods->getPeriodFrom();

        parent::__construct($options);

        $this->setName('suggested');

        $level = new Zend_Form_Element_Select('level');
        $level->setLabel('Level of research: ')
            ->setRequired(true)
            ->addMultiOptions(array(
                null => 'Please choose a level',
                'Research levels' => $projectype_list
            ))
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addValidator('InArray', false, array(array_keys($projectype_list)))
            ->addFilters(array('StringTrim', 'StripTags'));

        $period = new Zend_Form_Element_Select('period');
        $period->setLabel('Broad research period: ')
            ->setRequired(true)
            ->addMultiOptions(array(
                null => 'Please choose a period',
                'Periods available' => $period_options
            ))
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addValidator('InArray', false, array(array_keys($period_options)))
            ->addFilters(array('StringTrim', 'StripTags'));

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Project title: ')
            ->setRequired(true)
            ->setAttrib('size', 60)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('Choose title for the project.');

        $description = new Pas_Form_Element_CKEditor('description');
        $description->setLabel('Short description of project: ')
            ->setRequired(false)
            ->setAttribs(array('cols' => 80, 'rows' => 10))
            ->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'));

        $valid = new Zend_Form_Element_Checkbox('taken');
        $valid->setLabel('Is the topic taken: ')
            ->setRequired(true)
            ->addValidator('Int');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)
            ->setTimeout(4800);

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
            $title, $level, $period,
            $description, $valid, $submit,
            $hash));

        $this->addDisplayGroup(array(
                'title', 'level', 'period',
                'description', 'taken'),
            'details');
        $this->addDisplayGroup(array('submit'), 'buttons');
        parent::init();
    }
}