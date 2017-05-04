<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Performance analysis',
    'description' => 'Show performance statistics for frontend rendering directly in the frontend.',
    'category' => 'frontend',
    'author' => 'Christian Weiske',
    'author_company' => 'Netresearch GmbH & Co.KG',
    'author_email' => 'typo3@cweiske.de',
    'dependencies' => '',
    'state' => 'stable',
    'clearCacheOnLoad' => '1',
    'version' => '1.1.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-7.99.99',
        )
    )
);
?>
