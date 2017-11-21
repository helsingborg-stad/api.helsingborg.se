<?php

    if(isset($_GET['lang']) && !empty($_GET['lang']))
    {
        $_GET['lang'] = strtok($_GET['lang'], "_");
    }
