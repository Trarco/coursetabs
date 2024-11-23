<?php
class block_coursetabs extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_coursetabs');
    }

    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }

        global $PAGE, $DB;

        // Costruisci il percorso del file CSS
        $css_file_path = '/blocks/' . $this->instance->blockname . '/styles/styles.css';
        $css_full_path = __DIR__ . '/styles/styles.css';

        // Verifica se il file esiste
        if (file_exists($css_full_path)) {
            $PAGE->requires->css(new moodle_url($css_file_path));
        }

        // Verifica che il contesto corrente sia un corso
        if (!$PAGE->course || $PAGE->course->id == SITEID) {
            $this->content = new stdClass();
            $this->content->text = get_string('not_in_course', 'block_coursetabs');
            return $this->content;
        }

        // ID del corso corrente
        $courseid = $PAGE->course->id;

        // Recupera i titoli delle tab dalla tabella config_plugins
        $tabtitles = [
            'courseTabContent' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab1']),
            'tab2' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab2']),
            'tab3' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab3']),
            'tab4' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab4']),
            'tab5' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab5']),
        ];

        // Fallback per titoli mancanti
        foreach ($tabtitles as $key => $title) {
            if (empty($title)) {
                $tabtitles[$key] = get_string('default_' . $key, 'block_coursetabs');
            }
        }

        // Costruzione dei link alle tab
        $links = [];
        foreach ($tabtitles as $tabid => $title) {
            $links[] = html_writer::link(
                new moodle_url('/course/view.php', ['id' => $courseid, 'tab' => $tabid]),
                $title,
                ['data-tab' => $tabid]
            );
        }

        // Unisci i link in un elenco HTML
        $content = html_writer::tag('ul', implode('', array_map(function ($link) {
            return html_writer::tag('li', $link);
        }, $links)), ['class' => 'coursetabs-links']);

        // Includi il file JavaScript
        $PAGE->requires->js_call_amd('block_coursetabs/tabs', 'init');

        // Imposta il contenuto del blocco
        $this->content = new stdClass();
        $this->content->text = $content;
        $this->content->footer = '';

        return $this->content;
    }

    public function applicable_formats()
    {
        return [
            'course' => true,      // Mostra il blocco nelle pagine del corso
            'mod' => true,         // Mostra il blocco nelle pagine delle attività e risorse
            'site-index' => false, // Non mostrare nella home del sito
            'my' => false,         // Non mostrare nella dashboard personale
        ];
    }
}
