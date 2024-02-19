<?php

global $REPO_TO_COMPOSER_NAME, $COMPOSER_NAME_TO_REPO;

if (!check_file_exists('composer.json')) {
    error(module_name() . 'does not have composer.json');
}

// parse composer.json
$contents = read_file('composer.json');
$json = json_decode($contents, true);
if (!$json) {
    error("Failed to parse json in $path");
}
$composerName = $json['name'];
$repo = module_name();
print_r($composerName, $repo);

error($composerName);