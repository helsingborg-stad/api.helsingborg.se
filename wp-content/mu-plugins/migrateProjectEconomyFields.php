<?php

/*
Plugin Name: Migrate Project Economy Fields
Description: Migrate economy fields
Version:     1.0
Author:      Nikolas Ramstedt, Helsingborg Stad
*/

namespace MigrateProjectEconomyFields;

class App
{
    public function __construct()
    {
        if (isset($_GET['migrate'])) {
            add_action('admin_init', array($this, 'migrateData'));
        }
    }

    public function migrateData()
    {
        $projects = get_posts([
            'post_type' => 'project'
        ]);

        if (!empty($projects)) {
            foreach ($projects as $project) {
                $existingValue = get_field('cost_so_far', $project->ID);

                if (!empty($existingValue)) {
                    $repeaterField = get_field('funds_used', $project->ID);
                    $repeaterField[] = [
                        'amount' => $existingValue,
                        'date' => '2022-03-09',
                        'comment' => 'Överflyttat värde från tidigare fält'
                    ];

                    update_field('funds_used', $repeaterField, $project->ID);
                    update_field('cost_so_far', null, $project->ID);
                }
            }
        }
    }
}

new \MigrateProjectEconomyFields\App();
