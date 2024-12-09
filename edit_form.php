<?php
class block_coursetabs_edit_form extends block_edit_form
{

    protected function specific_definition($mform)
    {

        // Aggiunge un campo per il titolo personalizzato
        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_coursetabs'));
        $mform->setDefault('config_title', get_string('defaulttitle', 'block_coursetabs'));
        $mform->setType('config_title', PARAM_TEXT);
    }
}
