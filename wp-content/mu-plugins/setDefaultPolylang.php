<?php

    //Sets fallback langage to swedish until we have english
    add_filter('PolylangFallback/fallbackLanguages', function () {
        return array("sv");
    });
