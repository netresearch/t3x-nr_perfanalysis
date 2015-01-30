<?php
/**
 * Extension configuration
 * Part of nr_perfanalysis
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
defined('TYPO3_MODE') or die('Access denied.');

$extConf = unserialize(
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nr_perfanalysis']
);
if ($extConf['enable']) {
    //do nothing
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_db.php']
        ['queryProcessors'][]
        = 'Netresearch\NrPerfanalysis\QueryHooker';
    if (TYPO3_MODE == 'FE') {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']
            ['contentPostProc-output'][]
            = 'Netresearch\NrPerfanalysis\HtmlRenderer->contentPostProcOutput';
    } else if (TYPO3_MODE == 'BE') {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']
            ['TYPO3\\CMS\\Filelist\\Controller\\FileListController']
            = array(
                'className' => 'Netresearch\\NrPerfanalysis\\Xclass\\FileListController'
            );
    }
}
?>
