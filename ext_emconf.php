<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Performance analysis',
    'description' => 'Show SQL statistics for frontend rendering directly in the frontend.',
    'category' => 'frontend',
    'author' => 'Christian Weiske',
    'author_company' => 'Netresearch',
    'author_email' => 'christian.weiske@netresearch.de',
    'dependencies' => '',
    'state' => 'alpha',
    'clearCacheOnLoad' => '1',
    'version' => '0.0.1',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-7.1.99',
        )
    )
);
?>