<?php

use DevDojo\Themes\Models\Theme;
use Illuminate\Support\Facades\Cookie;

if (! function_exists('theme_field')) {
    function theme_field($type, $key, $title, $content = '', $details = '', $placeholder = '', $required = 0)
    {
        $theme = Theme::where('folder', '=', ACTIVE_THEME_FOLDER)->first();

        if ($option = $theme->options->where('key', '=', $key)->first()) {
            $content = $option->value;
        }

        $row = new class
        {
            public $required;

            public $field;

            public $type;

            public $details;

            public $display_name;

            public function getTranslatedAttribute() {}
        };
        $row->required = $required;
        $row->field = $key;
        $row->type = $type;
        $row->details = $details;
        $row->display_name = $placeholder;

        $dataTypeContent = new class
        {
            public function getKey() {}
        };
        $dataTypeContent->{$key} = $content;

        $label = '<label for="'.$key.'">'.$title.'<span class="how_to">You can reference this value with <code>theme(\''.$key.'\')</code></span></label>';
        $details = '<input type="hidden" value="'.$details.'" name="'.$key.'_details__theme_field">';
        $type = '<input type="hidden" value="'.$type.'" name="'.$key.'_type__theme_field">';

        return $label.app('voyager')->formField($row, '', $dataTypeContent).$details.$type.'<hr>';
    }

}

if (! function_exists('theme')) {
    function theme($key, $default = '')
    {
        if (! $theme = Theme::where('folder', '=', Cookie::get('theme'))->first()) {
            $theme = Theme::where('active', '=', 1)->first();
        }

        return $theme->options->where('key', '=', $key)->first()->value ?? $default;
    }

}

if (! function_exists('theme_folder')) {
    function theme_folder($folder_file = '')
    {
        if (defined('THEME_FOLDER') && THEME_FOLDER) {
            return 'themes/'.THEME_FOLDER.$folder_file;
        }

        if (! $theme = Theme::where('folder', '=', Cookie::get('theme'))->first()) {
            $theme = Theme::where('active', '=', 1)->first();
        }

        define('THEME_FOLDER', $theme->folder);

        return 'themes/'.$theme->folder.$folder_file;
    }
}

if (! function_exists('theme_folder_url')) {
    function theme_folder_url($folder_file = '')
    {
        if (defined('THEME_FOLDER') && THEME_FOLDER) {
            return url('themes/'.THEME_FOLDER.$folder_file);
        }

        if (! $theme = Theme::where('folder', '=', Cookie::get('theme'))->first()) {
            $theme = Theme::where('active', '=', 1)->first();
        }

        define('THEME_FOLDER', $theme->folder);

        return url('themes/'.$theme->folder.$folder_file);
    }
}
