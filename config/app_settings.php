<?php

return [

    // All the sections for the settings page
    'sections' => [
        'generalSettings' => [
          'title' => 'General Settings',
            'descriptions' => 'Application General Settings.', // (optional)
            'icon' => 'fa fa-cogs  ml-3', // (optional)
            'id' =>'generalSettings',
            'inputs' => [

                [
                    'name' => 'app_name', // unique key for setting
                    'type' => 'text', // type of input can be text, number, textarea, select, boolean, checkbox etc.
                    'label' => 'App Name', // label for input
                    // optional properties
                    'placeholder' => 'Enter Your App Name', // placeholder for input
                    'class' => 'form-control', // override global input_class
                    'style' => '', // any inline styles
                    'rules' => 'required', // validation rules for this input
                    'value' => '', // any default value
                    'hint' => 'You can set your app name here' // help block text for input
                ]
            ]
        ],
        'bbbServerSettings' => [
            'title' => 'BBB Server  Settings',
            'descriptions' => 'Application BBB Server Settings.', // (optional)
            'icon' => 'fa fa-server ml-3', // (optional)
            'id' =>'bbbServerSettings',

            'inputs' => [

                [
                    'name' => 'bbb_url', // unique key for setting
                    'type' => 'text', // type of input can be text, number, textarea, select, boolean, checkbox etc.
                    'label' => 'BBB Server Url', // label for input
                    // optional properties
                    'placeholder' => 'Enter Your BigBlueButton Server Url', // placeholder for input
                    'class' => 'form-control', // override global input_class
                    'style' => '', // any inline styles
                    'rules' => 'required', // validation rules for this input
                    'value' => '', // any default value
                    'hint' => 'You can set the bbb base Url here' // help block text for input
                ],
                [
                    'name' => 'bbb_secret', // unique key for setting
                    'type' => 'text', // type of input can be text, number, textarea, select, boolean, checkbox etc.
                    'label' => 'BBB Server Secret', // label for input
                    // optional properties
                    'placeholder' => 'Enter Your BigBlueButton Server Secret', // placeholder for input
                    'class' => 'form-control', // override global input_class
                    'style' => '', // any inline styles
                    'rules' => 'required', // validation rules for this input
                    'value' => '', // any default value
                    'hint' => 'You can set the bbb server secret here' // help block text for input
                ],

            ]
        ],
        'email' => [
            'title' => 'Email Settings',
            'descriptions' => 'How app email will be sent.',
            'icon' => 'fa fa-envelope ml-3',
            'id' =>'emailSettings',
            'inputs' => [
                [
                    'name'=>'email_host',
                    'type' =>'text',
                    'label' => 'Email Host',
                    'placeholder' => 'Your Email Host',
                    'rules' => 'required',
                    'hint' => 'Copy email host from your host site & paste here'

                ],
                [
                    'name'=>'email_port',
                    'type' =>'number',
                    'label' => 'Email Port',
                    'placeholder' => 'Your Email Host',
                    'rules' => 'required|integer',
                    'hint' => 'Copy email host from your host site & paste here'

                ],
                [
                    'name'=>'email_username',
                    'type' =>'text',
                    'label' => 'Email Username',
                    'placeholder' => 'Your Email Username',
                    'rules' => 'required',
                    'hint' => 'Copy email host from your host site & paste here'

                ],
                [
                    'name'=>'email_password',
                    'type' =>'text',
                    'label' => 'Email Password',
                    'placeholder' => 'Your Email Password',
                    'rules' => 'required',
                    'hint' => 'Copy email host from your host site & paste here'

                ],



            ]
        ]
    ],



    // Setting page url, will be used for get and post request
    'url' => 'settings',

    // Any middleware you want to run on above route
    'middleware' => ['auth'],

    // View settings
    'setting_page_view' => 'admin.settings.bbb_api_settings',
    'flash_partial' => 'app_settings::_flash',

    // Setting section class setting
    'section_class' => 'card mb-3',
    'section_heading_class' => 'card-header',
    'section_body_class' => 'card-body',

    // Input wrapper and group class setting
    'input_wrapper_class' => 'form-group',
    'input_class' => 'form-control',
    'input_error_class' => 'has-error',
    'input_invalid_class' => 'is-invalid',
    'input_hint_class' => 'form-text text-muted',
    'input_error_feedback_class' => 'text-danger',

    // Submit button
    'submit_btn_text' => 'Save Settings',
    'submit_success_message' => 'Settings has been saved.',

    // Remove any setting which declaration removed later from sections
    'remove_abandoned_settings' => false,

    // Controller to show and handle save setting
    'controller' => '\QCod\AppSettings\Controllers\AppSettingController',

    // settings group
    'setting_group' => function() {
        // return 'user_'.auth()->id();
        return 'default';
    }
];
