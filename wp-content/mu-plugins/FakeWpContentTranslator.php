<?php

namespace FakeContentTranslator;

class fakeWpContentTranslator
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'endpoints'));
    }

    public function endpoints()
    {
        register_rest_route('content-translator/v1', 'available', array(
            'methods' => 'GET',
            'callback' => array($this, 'avabileResponse'),
        ));

        register_rest_route('content-translator/v1', 'default', array(
            'methods' => 'GET',
            'callback' => array($this, 'defaultResponse'),
        ));
    }

    public function avabileResponse()
    {
        return json_decode('{"sv_SE":{"code":"sv_SE","name":"Swedish","nativeName":"Svenska"}}');
    }

    public function defaultResponse()
    {
        return json_decode('{"code":"sv_SE","name":"Swedish","nativeName":"Svenska"}');
    }
}

new fakeWpContentTranslator();
