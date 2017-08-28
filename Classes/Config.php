<?php
/**
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
namespace Netresearch\NrPerfanalysis;

/**
 * Counts events and times
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class Config
{
    /**
     * If statistics collection is enabled
     *
     * @return boolean
     */
    public static function isEnabled()
    {
        $extConf = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nr_perfanalysis']
        );
        return (bool) $extConf['enable'];
    }
}
?>
