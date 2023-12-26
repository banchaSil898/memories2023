<?php

namespace UDColumnistManager\Admin;


use UDColumnistManager\ColumnistManager;


class ColumnistTermMeta
{

    public function __construct()
    {
        if (is_admin()) {
            add_action(ColumnistManager::TAXONOMY_NAME . '_add_form_fields', array($this, 'createScreenFields'), 10, 1);
            add_action(ColumnistManager::TAXONOMY_NAME . '_edit_form_fields', array($this, 'editScreenFields'), 10, 2);
        }

        add_action('created_' . ColumnistManager::TAXONOMY_NAME, array($this, 'saveData'), 10, 1);
        add_action('edited_' . ColumnistManager::TAXONOMY_NAME, array($this, 'saveData'), 10, 1);
    }


    public function createScreenFields($taxonomy)
    {

        // Set default values.
        $ud_columnist_profile_id = '';

        // Form fields.
        echo '<div class="form-field term-ud_columnist_profile_id-wrap">';
        echo '	<label for="ud_columnist_profile_id">' . __('Columnist Profile ID', 'text_domain') . '</label>';
        echo '	<input type="number" id="ud_columnist_profile_id" name="ud_columnist_profile_id" placeholder="' . esc_attr__('',
                'text_domain') . '" value="' . esc_attr($ud_columnist_profile_id) . '">';
        echo '</div>';

    }

    public function editScreenFields($term, $taxonomy)
    {

        // Retrieve an existing value from the database.
        $ud_columnist_profile_id = get_term_meta($term->term_id, 'ud_columnist_profile_id', true);

        // Set default values.
        if (empty($ud_columnist_profile_id)) {
            $ud_columnist_profile_id = '';
        }

        // Form fields.
        echo '<tr class="form-field term-ud_columnist_profile_id-wrap">';
        echo '<th scope="row">';
        echo '	<label for="ud_columnist_profile_id">' . __('Columnist Profile ID', 'text_domain') . '</label>';
        echo '</th>';
        echo '<td>';
        echo '	<input type="number" id="ud_columnist_profile_id" name="ud_columnist_profile_id" placeholder="' . esc_attr__('',
                'text_domain') . '" value="' . esc_attr($ud_columnist_profile_id) . '">';
        echo '</td>';
        echo '</tr>';

    }

    public function saveData($term_id)
    {

        // Sanitize user input.
        $ud_columnist_profile_new_id = isset($_POST['ud_columnist_profile_id']) ? floatval($_POST['ud_columnist_profile_id']) : '';

        // Update the meta field in the database.
        update_term_meta($term_id, 'ud_columnist_profile_id', $ud_columnist_profile_new_id);

    }
}