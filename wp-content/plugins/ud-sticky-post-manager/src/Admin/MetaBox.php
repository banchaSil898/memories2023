<?php

namespace UDStickyPostManager\Admin;

use UDStickyPostManager\Core\PostType;

if (! defined('ABSPATH')) {
    exit;
}

class MetaBox
{
    private $screens = array(
        PostType::POST_TYPE_SLUG
    );

    public $meta_schema = array(
        PostType::META_KEY => array(
            array(
                'id'      => 'post_id',
                'label'   => 'Post ID',
                'type'    => 'select_post',
                'default' => ''
            ),
            array(
                'id'      => 'loop_ids',
                'label'   => 'Loop IDs',
                'type'    => 'select_category',
                'default' => ''
            ),
            array(
                'id'      => 'position_number',
                'label'   => 'Position Number',
                'type'    => 'positive_number_without_zero',
                'default' => ''
            ),
            array(
                'id'      => 'start_time',
                'label'   => 'Start time',
                'type'    => 'start_time',
                'default' => ''
            ),
            array(
                'id'      => 'end_time',
                'label'   => 'End time',
                'type'    => 'time',
                'default' => ''
            ),
            array(
                'id'      => 'on_start_post_status',
                'label'   => 'Force setting post status on start time',
                'type'    => 'post_status_dropdown',
                'default' => 'publish'
            ),
            array(
                'id'      => 'on_end_post_status',
                'label'   => 'Force setting post status on end time',
                'type'    => 'post_status_dropdown',
                'default' => 'draft'
            ),
        )
    );

    public function __construct()
    {
        add_action('ud_trash_expired_post', array($this, 'udTrashExpiredPostHook'), 10, 1);
        add_action('future_to_publish', array($this, 'futureToPublishHook'), 10, 1);
        add_filter('wp_insert_post_data', array($this, 'wpInsertPostDataHook'), 10, 2);
        add_action('save_post_' . PostType::POST_TYPE_NAME, array($this, 'saveScheduleHook'), 10, 3);

        if (is_admin()) {
            add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsAndStyles'));

            //disable auto save
            add_action('admin_print_scripts', array($this, 'disableAutosave'));

            //columns
            add_action('manage_edit-' . PostType::POST_TYPE_NAME . '_columns', array($this, 'manageColumns'));
            add_action('manage_' . PostType::POST_TYPE_NAME . '_posts_custom_column', array($this, 'manageCustomColumn'), 10, 2);
            add_action('manage_edit-' . PostType::POST_TYPE_NAME . '_sortable_columns', array($this, 'sortableColumns'), 10, 1);
        }
    }

    public function enqueueScriptsAndStyles($hook)
    {
        $screen = get_current_screen();
        if ('post.php' == $hook || 'post-new.php' == $hook) {
            if (! empty($screen->post_type) && $screen->post_type === PostType::POST_TYPE_NAME) {
                wp_enqueue_script(
                    "udspm-meta-admin",
                    plugins_url('assets/js/udspm-metabox.js', UD_STICKY_POST_MANAGER_FILE),
                    array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-autocomplete')
                );
            }
        }
    }

    public function udTrashExpiredPostHook($post_id)
    {
        $post = get_post($post_id);

        if (empty($post) or PostType::POST_TYPE_NAME !== $post->post_type) {
            return;
        }

        $schedule = $post;

        if ('publish' != $schedule->post_status) {
            return;
        }

        // Uh oh, someone jumped the gun!
        if (! $this->isExpiredSchedule($schedule)) {
            $this->recreateExpireEventForSchedule($schedule);

            return;
        }

        $this->changeStickyPostStatusOnExpire($schedule);
        wp_trash_post($schedule->ID);
    }

    public function wpInsertPostDataHook($data, $postarr)
    {
        if (PostType::POST_TYPE_NAME !== $data['post_type']) {
            return $data;
        }

        if (isset($_POST['ud_sticky_post_schedule_nonce'])) {
            $nonce = $_POST['ud_sticky_post_schedule_nonce'];
            if (! wp_verify_nonce($nonce, 'ud_sticky_post_schedule_data')) {
                return $data;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $data;
            }

            $data = $this->changeScheduleTitleToPostTitle($data);
            $data = $this->changeScheduleStatusFromMetaDataField($data);
        }

        return $data;
    }

    public function saveScheduleHook($schedule_id, $schedule, $update)
    {
        if (wp_is_post_revision($schedule_id)) {
            return;
        }

        //check if from metabox submit or from restoring
        if (isset($_POST['ud_sticky_post_schedule_nonce'])) {
            $nonce = $_POST['ud_sticky_post_schedule_nonce'];
            if (! wp_verify_nonce($nonce, 'ud_sticky_post_schedule_data')) {
                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            $data = $this->sanitizeScheduleMetaDataField($_POST);
            $data = $this->sanitizeStartAndEndTimeFromMetaData($data, $schedule);
        //@todo: may optimize later
            //$old_meta_data = get_post_meta($schedule->ID, PostType::META_KEY, true);
            //$need_recreate_expire_event = $this->needRecreateExpireEventFromMetaData($data, $old_meta_data);
        } else {
            $old_data = array(PostType::META_KEY => get_post_meta($schedule->ID, PostType::META_KEY, true));
            $data = $this->sanitizeStartAndEndTimeFromMetaData($old_data, $schedule);
            // $need_recreate_expire_event = true;
        }

        $this->saveScheduleMetaData($schedule, $data);

        if ('publish' === $schedule->post_status) {
            $this->recreateExpireEventForSchedule($schedule);

            return;
        } elseif ('trash' === $schedule->post_status) {
            $this->clearExpireEventForSchedule($schedule);

            return;
        } else {
            $this->clearExpireEventForSchedule($schedule);

            return;
        }
    }


    public function addMetaBoxes()
    {
        foreach ($this->screens as $screen) {
            add_meta_box('udstickypostmanagerdiv', 'Sticky Post Schedule Infos', array($this, 'renderMetaBox'), $screen, 'normal', 'core');
        }
    }

    public function disableAutosave()
    {
        $screen = get_current_screen();
        if (! empty($screen->post_type) && $screen->post_type === PostType::POST_TYPE_NAME) {
            wp_dequeue_script('autosave');
        }
    }

    public function renderMetaBox($post)
    {
        wp_nonce_field('ud_sticky_post_schedule_data', 'ud_sticky_post_schedule_nonce');
        echo 'Specify all required data';
        $this->generateFields($post);
    }

    public function manageColumns($columns)
    {
        $new_columns = array(
            'udspm_position_number' => __('Position Num.', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'udspm_loop_ids'        => __('Loop IDs', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'udspm_start_time'      => __('Start Time', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'udspm_expire_time'     => __('Expired Time', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'udspm_status'          => __('Status', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
        );

        unset($columns['date']);

        return array_merge($columns, $new_columns);
    }

    public function manageCustomColumn($column, $post_id)
    {
        $post = get_post($post_id);

        $meta = get_post_meta($post_id, PostType::META_KEY, true);

        switch ($column) {
            case 'udspm_expire_time':
                if (! empty($meta['end_time'])) {
                    echo $h_time = mysql2date(__('d M, Y g:i:s a'), $meta['end_time']);
                } else {
                    echo 'Never';
                }
                break;
            case 'udspm_start_time':
                echo mysql2date(__('d M, Y g:i:s a'), $post->post_date);
                break;
            case 'udspm_status':
                echo $post->post_status;
                break;
            case 'udspm_loop_ids':
                if (! empty($meta['loop_ids']) and is_array($meta['loop_ids'])) {
                    $value_text = implode(', ', $meta['loop_ids']);
                    //                    $category = get_category_by_slug($meta['loop_ids']);
                    //                    if (! is_wp_error($category) and ! empty($category)) {
                    //                        $value_text .= ' (' . $category->name . ')';
                    //                    }

                    echo $value_text;
                }
                break;
            case 'udspm_position_number':
                if (! empty($meta['position_number'])) {
                    echo $meta['position_number'];
                }
                break;
            case 'udspm_content':
                $content = '';
                if (! empty($meta['post_id'])) {
                    $post = get_post($meta['post_id']);
                    if (! empty($post)) {
                        $content = 'Post:' . $post->post_title;
                    }
                } elseif (! empty($meta['ad_script'])) {
                    $content = '[ ad script ... ]';
                }

                echo $content;
                break;
        }

        return $column;
    }

    public function sortableColumns($columns)
    {
        $columns['udspm_start_time'] = array('date', true);
        unset($columns['date']);

        return $columns;
    }

    /**
     * Generates the field's HTML for the meta box.
     */
    private function generateFields($post)
    {
        $output = '';

        foreach ($this->meta_schema as $option_id => $fields) {
            $db_value = get_post_meta($post->ID, $option_id, true);
            foreach ($fields as $field) {
                $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
                $value = isset($db_value[$field['id']]) ? $db_value[$field['id']] : $field['default'];
                $id = $option_id . '_' . $field['id'];
                $name = $option_id . '[' . $field['id'] . ']';

                switch ($field['type']) {
                    case 'text':
                        $input = sprintf(
                            '<input class="regular-text" id="%s" name="%s" type="text" value="%s">',
                            $id,
                            $name,
                            $value
                        );
                        break;
                    case 'ad_script':
                        $input = sprintf(
                            '<textarea style="width:100%;" id="%s" name="%s"  rows="10">%s</textarea>',
                            $id,
                            $name,
                            $value
                        );
                        break;
                    case 'positive_number_without_zero':
                        $input = sprintf(
                            '<input class="text" id="%s" name="%s" type="number" value="%s">',
                            $id,
                            $name,
                            $value
                        );
                        break;
                    case 'start_time':
                        $value = (! empty($value)) ? $value : $post->post_date;
                        $input = $this->generateTimeInput($option_id, $field['id'], $value);
                        break;
                    case 'time':
                        $input = $this->generateTimeInput($option_id, $field['id'], $value);
                        break;
                    case 'checkbox':
                        $input = sprintf(
                            '<input %s id="%s" name="%s" type="checkbox" value="1">',
                            $value === '1' ? 'checked' : '',
                            $id,
                            $name
                        );
                        break;
                    case 'select_category':
                        if (empty($value) || ! is_array($value)) {
                            $value = array('');
                        }

                        $input = '<div class="udspm-loop-ids">';
                        foreach ($value as $key => $val) {
                            $val_text = $val;

                            //                            $category = get_category_by_slug($val);
                            //                            if (! is_wp_error($category) and ! empty($category)) {
                            //                                $val_text .= ' (' . $category->name . ')';
                            //                            }

                            $input .= '<div class="udspm-select-loop-id">';
                            $input .= '<input id="' . $id . '-' . $key . '" name="' . $name . '[]" type="text" class="real-input" value="' . $val . '">';
                            $input .= '<input class="regular-text hide-if-no-js fake_input" placeholder="Search Category here!! or put block id on left box" type="text">';
                            if ($key > 0) {
                                $input .= '<button type="button" class="del-button button button-small">Delete</button>';
                            }
                            //                            $input .= '<span class="chosen_item">' . $val_text . '</span>';
                            $input .= '</div>';
                        }
                        $input .= '<button type="button" class="add-button button button-small">Add</button>';
                        $input .= '</div>';

                        break;
                    case 'select_post':
                        $selected_post = null;
                        if (! empty($value)) {
                            $selected_post = get_post($value);
                        }

                        $post_title = '';
                        if (! empty($selected_post)) {
                            $post_title = $selected_post->post_title;
                        }

                        $input = '<div class="udspm-select-post">';
                        $input .= '<input id="' . $id . '" name="' . $name . '" type="text" class="real-input" value="' . $value . '">';
                        $input .= '<input class="regular-text hide-if-no-js fake_input" placeholder="Search here!!" type="text">';
                        $input .= '<div class="chosen_item"><span>' . $post_title . '</span></div>';

                        break;
                    case 'post_status_dropdown':
                        $all_supported_post_stati = array('none' => 'None');
                        $all_supported_post_stati += get_post_stati();
                        $input = '<select id="' . $id . '" name="' . $name . '">';
                        foreach ($all_supported_post_stati as $option_value => $option_label) {
                            $input .= '<option ' . selected($value, $option_value, false) . ' value="' . $option_value . '">' . $option_label . '</option>';
                        }
                        $input .= '</select>';
                        break;
                    default:
                        $input = sprintf(
                            '<input %s id="%s" name="%s" type="%s" value="%s">',
                            $field['type'] !== 'color' ? 'class="regular-text"' : '',
                            $id,
                            $name,
                            $field['type'],
                            $value
                        );
                }
                $output .= $this->rowFormat($label, $input);
            }
        }
        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
    }


    /**
     * Generates the HTML for table rows.
     */
    private function rowFormat($label, $input)
    {
        return sprintf('<tr><th scope="row">%s</th><td>%s</td></tr>', $label, $input);
    }

    private function changeScheduleTitleToPostTitle($data)
    {
        //update post title
        $input_post_id = sanitize_text_field($_POST[PostType::META_KEY]['post_id']);
        if (is_numeric($input_post_id)) {
            $input_post_id = intval($input_post_id);
            $post = get_post($input_post_id);
            if (! empty($post)) {
                $data['post_title'] = $post->post_title;
            }
        }

        return $data;
    }

    private function changeScheduleStatusFromMetaDataField($data)
    {
        $now = current_time('mysql');
        $now_ts = mysql2date('U', $now, false);

        $published_time = $this->sanitizeTimeData($_POST[PostType::META_KEY]['start_time']);
        if (empty($published_time)) {
            $data['post_date'] = $now;
        } else {
            $data['post_date'] = $published_time;
        }
        $data['post_date_gmt'] = get_gmt_from_date($data['post_date']);

        $post_date_ts = mysql2date('U', $data['post_date'], false);

        if ('publish' == $data['post_status']) {
            if ($post_date_ts > $now_ts) {
                $data['post_status'] = 'future';
            }
        } elseif ('future' == $data['post_status']) {
            if ($post_date_ts <= $now_ts) {
                $data['post_status'] = 'publish';
            }
        }

        return $data;
    }

    private function sanitizeScheduleMetaDataField($data)
    {
        $sanitized_data = array();
        foreach ($this->meta_schema as $option_id => $fields) {
            $sanitized_data[$option_id] = array();
            foreach ($fields as $field) {
                if (isset($data[$option_id][$field['id']])) {
                    switch ($field['type']) {
                        case 'email':
                            $sanitized_data[$option_id][$field['id']] = sanitize_email($data[$option_id][$field['id']]);
                            break;
                        case 'ad_script':
                            $sanitized_data[$option_id][$field['id']] = $data[$option_id][$field['id']];
                            break;
                        case 'text':
                            $sanitized_data[$option_id][$field['id']] = sanitize_text_field($data[$option_id][$field['id']]);
                            break;
                        case 'positive_number_without_zero':
                            $value = sanitize_text_field($data[$option_id][$field['id']]);
                            if (! empty($value) && is_numeric($value) && intval($value) > 0) {
                                $sanitized_data[$option_id][$field['id']] = strval($value);
                            }
                            break;
                        case 'start_time':
                        case 'time':
                            $time = $this->sanitizeTimeData($data[$option_id][$field['id']]);
                            if (! empty($time)) {
                                $sanitized_data[$option_id][$field['id']] = $time;
                            }
                            break;
                        case 'select_post':
                            if (is_numeric(sanitize_text_field($data[$option_id][$field['id']]))) {
                                $post = get_post(intval($data[$option_id][$field['id']]));

                                if (! empty($post)) {
                                    $sanitized_data[$option_id][$field['id']] = $post->ID;
                                }
                            }
                            break;
                        case 'select_category':
                            if (! is_array($data[$option_id][$field['id']])) {
                                break;
                            }

                            $sanitized_data[$option_id][$field['id']] = array();

                            foreach ($data[$option_id][$field['id']] as $val) {
                                $val = sanitize_text_field($val);
                                if (! empty($val)) {
                                    array_push($sanitized_data[$option_id][$field['id']], $val);
                                }
                            }
                            $sanitized_data[$option_id][$field['id']] = array_unique($sanitized_data[$option_id][$field['id']]);

                            break;
                        case 'post_status_dropdown':
                            $all_supported_post_stati = array('' => 'None');
                            $all_supported_post_stati += get_post_stati();
                            $sanitized_data[$option_id][$field['id']] = sanitize_text_field($data[$option_id][$field['id']]);
                            if (! in_array($sanitized_data[$option_id][$field['id']], array_keys($all_supported_post_stati))) {
                                $sanitized_data[$option_id][$field['id']] = '';
                            }
                            break;

                    }
                } else {
                    switch ($field['type']) {
                        case 'checkbox':
                            $sanitized_data[$option_id][$field['id']] = '0';
                            break;
                    }
                }
            }
        }

        return $sanitized_data;
    }

    private function sanitizeStartAndEndTimeFromMetaData($data, $schedule)
    {
        if (empty($data[PostType::META_KEY]['start_time'])) {
            $data[PostType::META_KEY]['start_time'] = $schedule->post_date;
        }

        if (! empty($data[PostType::META_KEY]['end_time'])) {
            $now = current_time('mysql');
            $now_ts = mysql2date('U', $now, false);
            $start_time = $data[PostType::META_KEY]['start_time'];
            $start_time_ts = mysql2date('U', $start_time, false);
            $end_time = $data[PostType::META_KEY]['end_time'];
            $end_time_ts = mysql2date('U', $end_time, false);

            if ($end_time_ts > $now_ts and $end_time_ts > $start_time_ts) {
                $data[PostType::META_KEY]['end_time'] = $end_time;
            } else {
                unset($data[PostType::META_KEY]['end_time']);
            }
        }

        return $data;
    }

    private function needRecreateExpireEventFromMetaData($data, $old_meta_data)
    {
        $result = true;

        if (empty($old_meta_data['end_time'])) {
            if (empty($data[PostType::META_KEY]['end_time'])) {
                return false;
            }
        } else {
            if (! empty($data[PostType::META_KEY]['end_time'])) {
                $new_end_time = $data[PostType::META_KEY]['end_time'];
                $new_end_time_ts = mysql2date('U', $new_end_time, false);

                $old_end_time = $old_meta_data['end_time'];
                $old_end_time_ts = mysql2date('U', $old_end_time, false);

                if ($old_end_time_ts === $new_end_time_ts) {
                    return false;
                }
            }
        }

        return $result;
    }

    private function saveScheduleMetaData($schedule, $data)
    {
        foreach ($this->meta_schema as $option_id => $fields) {
            update_post_meta($schedule->ID, $option_id, $data[$option_id]);
        }
    }

    private function handleScheduleSaveMetaData($schedule)
    {
        if (! isset($_POST['ud_sticky_post_schedule_nonce'])) {
            $nonce = $_POST['ud_sticky_post_schedule_nonce'];
            if (! wp_verify_nonce($nonce, 'ud_sticky_post_schedule_data')) {
                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            $data = $this->sanitizeScheduleMetaDataField($_POST);
        } else {
            $data = get_post_meta($schedule->ID, PostType::META_KEY, true);
        }

        $data = $this->sanitizeStartAndEndTimeFromMetaData($data, $schedule);

        $need_recreate_expire_event = $this->needRecreateExpireEventFromMetaData($data, $schedule);

        $this->saveScheduleMetaData($schedule, $data);

        if ('publish' === $schedule->post_status) {
            if ($need_recreate_expire_event) {
                $this->recreateExpireEventForSchedule($schedule);
            }

            return;
        } elseif ('trash' === $schedule->post_status) {
            $this->clearExpireEventForSchedule($schedule);

            return;
        } else {
            $this->clearExpireEventForSchedule($schedule);

            return;
        }
    }

    private function sanitizeTimeData($time_data)
    {
        foreach (array("aa", "mm", "jj", "hh", "mn") as $key) {
            $time_data[$key] = sanitize_text_field($time_data[$key]);
            if (! is_numeric($time_data[$key]) and empty($time_data[$key])) {
                return '';
            }

            $time_data[$key] = intval($time_data[$key]);
        }

        $time_data['aa'] = ($time_data['aa'] <= 0) ? date('Y') : $time_data['aa'];
        $time_data['mm'] = ($time_data['mm'] <= 0) ? date('n') : $time_data['mm'];
        $time_data['jj'] = ($time_data['jj'] <= 0) ? date('j') : $time_data['jj'];
        $time_data['hh'] = ($time_data['hh'] > 23) ? $time_data['hh'] - 24 : $time_data['hh'];
        $time_data['mn'] = ($time_data['mn'] > 59) ? $time_data['mn'] - 60 : $time_data['mn'];

        $result = sprintf(
            "%04d-%02d-%02d %02d:%02d:%02d",
            $time_data['aa'],
            $time_data['mm'],
            $time_data['jj'],
            $time_data['hh'],
            $time_data['mn'],
            0
        );
        $valid_date = wp_checkdate($time_data['mm'], $time_data['jj'], $time_data['aa'], $result);
        if (! $valid_date) {
            return '';
        }

        return $result;
    }

    private function generateTimeInput($option_id, $field_id, $value)
    {
        $id = $option_id . '_' . $field_id;
        $name = $option_id . '[' . $field_id . ']';

        $jj = ! empty($value) ? mysql2date('d', $value, false) : '';
        $mm = ! empty($value) ? mysql2date('m', $value, false) : '';
        $aa = ! empty($value) ? mysql2date('Y', $value, false) : '';
        $hh = ! empty($value) ? mysql2date('H', $value, false) : '';
        $mn = ! empty($value) ? mysql2date('i', $value, false) : '';

        $month_text = array(
            ''   => '',
            '01' => '01-Jan',
            '02' => '02-Feb',
            '03' => '03-Mar',
            '04' => '04-Apr',
            '05' => '05-May',
            '06' => '06-Jun',
            '07' => '07-Jul',
            '08' => '08-Aug',
            '09' => '09-Sep',
            '10' => '10-Oct',
            '11' => '11-Nov',
            '12' => '12-Dec',
        );

        $input = '<select id="' . $id . '_mm" name="' . $name . '[mm]' . '" style="line-height: initial; height: initial;vertical-align: top;">';
        foreach ($month_text as $option_value => $option_label) {
            $input .= '<option ' . selected($mm, $option_value, false) . ' value="' . $option_value . '">' . $option_label . '</option>';
        }
        $input .= '</select>';


        $input .= sprintf(
            '<input id="%s" name="%s" type="text" value="%s" size="2" maxlength="2" autocomplete="off" class="tiny-text">, ',
            $id . '_jj',
            $name . '[jj]',
            $jj
        );

        $input .= sprintf(
            '<input id="%s" name="%s" type="text" value="%s" size="4" maxlength="4" autocomplete="off" class="tiny-text" style="width:4em;"> @ ',
            $id . '_aa',
            $name . '[aa]',
            $aa
        );

        $input .= sprintf(
            '<input id="%s" name="%s" type="text" value="%s" size="2" maxlength="2" autocomplete="off" class="tiny-text">:',
            $id . '_hh',
            $name . '[hh]',
            $hh
        );

        $input .= sprintf(
            '<input id="%s" name="%s" type="text" value="%s" size="2" maxlength="2" autocomplete="off" class="tiny-text">',
            $id . '_mn',
            $name . '[mn]',
            $mn
        );

        return $input;
    }

    private function clearExpireEventForSchedule($schedule)
    {
        wp_clear_scheduled_hook('ud_trash_expired_post', array($schedule->ID)); // clear anything else in the system
    }

    private function recreateExpireEventForSchedule($schedule)
    {
        $meta = get_post_meta($schedule->ID, PostType::META_KEY, true);

        if (empty($meta['end_time'])) {
            return;
        }

        $end_time = $meta['end_time'];
        if (! empty($end_time)) {
            $now = gmdate('Y-m-d H:i:59');
            $end_time_gmt = get_gmt_from_date($end_time);
            if (mysql2date('U', $end_time_gmt, false) > mysql2date('U', $now, false) and mysql2date('U', $end_time_gmt, false) > mysql2date('U', $schedule->post_date_gmt, false)) {
                $time = strtotime(get_gmt_from_date($end_time) . ' GMT');
                wp_clear_scheduled_hook('ud_trash_expired_post', array($schedule->ID)); // clear anything else in the system
                wp_schedule_single_event($time, 'ud_trash_expired_post', array($schedule->ID));
            } else {
                $meta['end_time'] = "";
                update_post_meta($schedule->ID, PostType::META_KEY, $meta);
            }
        }
    }

    private function changeStickyPostStatusOnStart($schedule)
    {
        $this->changeStickyPostStatusByMeta($schedule, 'on_start_post_status');
    }

    private function changeStickyPostStatusOnExpire($schedule)
    {
        $this->changeStickyPostStatusByMeta($schedule, 'on_end_post_status');
    }

    private function changeStickyPostStatusByMeta($schedule, $meta_name)
    {
        $meta = get_post_meta($schedule->ID, PostType::META_KEY, true);

        // update post status
        if (! empty($meta[$meta_name]) and $meta[$meta_name] !== 'none') {
            global $wpdb;
            $post = get_post($meta['post_id']);
            if (! empty($post)) {
                $data = array(
                    'post_status' => $meta[$meta_name]
                );

                $where = array(
                    'ID' => $post->ID
                );


                if ('publish' === $meta[$meta_name]) {
                    $post_date = current_time('mysql');
                    if (empty($post->post_date) || '0000-00-00 00:00:00' == $post->post_date) {
                        $data['post_date'] = $post_date;
                    }

                    if (empty($post->post_date_gmt) || '0000-00-00 00:00:00' == $post->post_date_gmt) {
                        $data['post_date_gmt'] = get_date_from_gmt($post_date);
                    }
                }

                // don't use wp_update_post because post meta will be missing
                if (false === $wpdb->update($wpdb->posts, $data, $where)) {
                    return new \WP_Error('db_update_error', __('Could not update post in the database'), $wpdb->last_error);
                }
            }
        }
    }

    private function isExpiredSchedule($schedule)
    {
        $meta = get_post_meta($schedule->ID, PostType::META_KEY, true);
        if (! empty($meta['end_time'])) {
            $now = current_time('mysql');
            $now_ts = mysql2date('U', $now, false);

            $end_time = $meta['end_time'];
            $end_time_ts = mysql2date('U', $end_time, false);
            if ($end_time_ts < $now_ts) {
                return true;
            }
        }

        return false;
    }

    public function futureToPublishHook($post)
    {
        if (empty($post) or PostType::POST_TYPE_NAME !== $post->post_type) {
            return;
        }

        $schedule = $post;

        $this->changeStickyPostStatusOnStart($schedule);
    }
}
