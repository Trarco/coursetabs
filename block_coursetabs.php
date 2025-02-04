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
        $links = [];
        $activity_icons = [
            'tab2' => '', // Attività per Tab 2
            'courseTabContent' => 'page', // Attività per Tab 1 (contenuto del corso)
            'tab3' => 'quiz', // Attività per Tab 3
            'tab4' => 'scorm', // Attività per Tab 4
            'tab5' => 'glossary', // Attività per Tab 5
        ];

        foreach ($tabtitles as $tabid => $title) {
            // Verifica se esiste un'attività associata
            if (!empty($activity_icons[$tabid])) {
                // Recupera l'URL dell'icona dal modulo
                $icon_url = new moodle_url('/theme/image.php', [
                    'theme' => $PAGE->theme->name,
                    'component' => 'mod_' . $activity_icons[$tabid],
                    'image' => 'icon'
                ]);
                $icon_html = html_writer::empty_tag('img', ['src' => $icon_url, 'alt' => '', 'class' => 'icon']);
            } else {
                // Icona predefinita nel caso l'attività non sia definita
                $default_icon_url = new moodle_url('/custom/icon/arguments.png');
                $icon_html = html_writer::empty_tag('img', [
                    'src' => $default_icon_url,
                    'alt' => '',
                    'class' => 'icon'
                ]);
            }

            // Costruisce il contenuto del link con l'icona e il titolo
            $link_content = $icon_html . ' ' . $title;

            $links[] = html_writer::link(
                new moodle_url('/course/view.php', ['id' => $courseid, 'tab' => $tabid]),
                $link_content,
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
            'course-view' => true,   // Pagine principali del corso
            'course-view-*' => true, // Qualsiasi sottopagina del corso
            'mod' => true,           // Attività e risorse
            'site' => false,         // Non mostrare nella home del sito
            'my' => false            // Non mostrare nella dashboard personale
        ];
    }
}
