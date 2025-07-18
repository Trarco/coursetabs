<?php
class block_coursetabs extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_coursetabs');
    }

    public function specialization()
    {
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        }
    }

    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }

        global $PAGE, $DB, $OUTPUT;

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

        $this->title = html_writer::tag('div', $this->title, ['class' => 'coursetabs-title']);

        // ID del corso corrente
        $courseid = $PAGE->course->id;

        // Recupera i titoli delle tab dalla tabella config_plugins
        $tabtitles = [
            'tab2' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab2']),
            'courseTabContent' => $DB->get_field('config_plugins', 'value', ['plugin' => 'theme_universe', 'name' => 'titlecoursetab1']),
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

        // Costruzione dei link alle tab con icone delle attività
        $tabs = [];
        $activity_icons = [
            'tab2' => '', // Attività per Tab 2
            'courseTabContent' => 'page', // Attività per Tab 1 (contenuto del corso)
            'tab3' => 'quiz', // Attività per Tab 3
            'tab4' => 'scorm', // Attività per Tab 4
            'tab5' => 'glossary', // Attività per Tab 5
        ];

        foreach ($tabtitles as $tabid => $title) {
            if (!empty($activity_icons[$tabid])) {
                $icon = (new moodle_url('/theme/image.php', [
                    'theme' => $PAGE->theme->name,
                    'component' => 'mod_' . $activity_icons[$tabid],
                    'image' => 'icon'
                ]))->out();
            } else {
                // Percorso completo al file custom su disco
                $customiconpath = __DIR__ . '/custom/icon/arguments.png';
                if (file_exists($customiconpath)) {
                    $icon = (new moodle_url('/blocks/' . $this->instance->blockname . '/custom/icon/arguments.png'))->out();
                } else {
                    // Icona di default di Moodle: ad esempio l'icona di un corso
                    $icon = (new moodle_url('/theme/image.php', [
                        'theme' => $PAGE->theme->name,
                        'component' => 'core',
                        'image' => 'i/course'
                    ]))->out();
                }
            }

            $tabs[] = [
                'tabid' => $tabid,
                'title' => $title,
                'icon'  => $icon,
                'url'   => (new moodle_url('/course/view.php', ['id' => $courseid]))->out(false) . '#' . $tabid,
            ];
        }

        $content = $OUTPUT->render_from_template('block_coursetabs/coursetabs', ['tabs' => $tabs]);

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
            'course-view' => true,   // Pagine principali del corso
            'course-view-*' => true, // Qualsiasi sottopagina del corso
            'mod' => true,           // Attività e risorse
            'site' => false,         // Non mostrare nella home del sito
            'my' => false            // Non mostrare nella dashboard personale
        ];
    }
}
