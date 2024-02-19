<?php

global $REPO_TO_COMPOSER_NAME, $COMPOSER_NAME_TO_REPO;

// developer-docs won't have json and that is fine
if (check_file_exists('composer.json')) {
    $contents = read_file('composer.json');
    $json = json_decode($contents, true);
    $composerName = $json['name'];
    $repo = module_name();
    $newJson = $json;
    // for each in require and require-dev, increment the version if it is in $COMPOSER_NAME_TO_REPO
    foreach (['require', 'require-dev'] as $key) {
        if (!isset($json[$key])) {
            continue;
        }
        foreach ($json[$key] as $composerName => $version) {
            if (!isset($COMPOSER_NAME_TO_REPO[$composerName])) {
                continue;
            }
            $regexs = [
                '/^\^([0-9]).*$/' => '^$1',
                '/^([0-9])\.x-dev$/' => '$1.x-dev',
            ];
            foreach ($regexs as $regex => $replacement) {
                if (preg_match($regex, $version, $matches)) {
                    $newVersion = $matches[1] + 1;
                    $replacement = str_replace('$1', $newVersion, $replacement);
                    $newJson[$key][$composerName] = preg_replace($regex, $replacement, $version);
                    break;
                }
            }
        }
        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        write_file_even_if_exists('composer.json', json_encode($newJson, $flags));
    }
}