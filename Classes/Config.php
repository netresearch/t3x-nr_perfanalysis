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
     * Key for the value for cookie protection
     */
    const KEY_COOKIEPROTECTION = 'cookieprotection';

    /**
     * Key for xhprof directory
     */
    const KEY_XHPROF_DIR = 'xhprofDir';

    /**
     * Key for xhprof url
     */
    const KEY_XHPROF_URL = 'xhprofUrl';

    /**
     * the extension configuration array
     *
     * @var array
     */
    protected static $arExtConf = null;

    /**
     * Returns the extension configuration array.
     *
     * @return array
     */
    protected static function loadExtensionConfiguration()
    {
        if (null != self::$arExtConf) {
            return self::$arExtConf;
        }

        if (empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nr_perfanalysis'])) {
            self::$arExtConf = array();
        } else {
            self::$arExtConf = unserialize(
                $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nr_perfanalysis']
            );
        }

        return self::$arExtConf;
    }



    /**
     * If statistics collection is enabled
     *
     * @return boolean
     */
    public static function isEnabled()
    {
        self::loadExtensionConfiguration();

        return (bool) self::$arExtConf['enable'];
    }

    /**
     * Returns true if cookie protection is enabled in the extension
     * configuration.
     *
     * @return bool
     */
    public static function isCookieProtectionEnabled()
    {
        self::loadExtensionConfiguration();

        if (empty(self::$arExtConf[self::KEY_COOKIEPROTECTION])) {
            return false;
        }

        return (bool) self::$arExtConf[self::KEY_COOKIEPROTECTION];
    }



    /**
     * Returns the configured xhprof directory
     *
     * @return string
     */
    public static function getXhprofDir ()
    {
        self::loadExtensionConfiguration();

        if (empty(self::$arExtConf[self::KEY_XHPROF_DIR])) {
            return '';
        }

        return self::$arExtConf[self::KEY_XHPROF_DIR];
    }



    /**
     * Returns the configured xhprof url
     * 
     * @return string
     */
    public static function getXhprofUrl ()
    {
        self::loadExtensionConfiguration();

        if (empty(self::$arExtConf[self::KEY_XHPROF_URL])) {
            return '';
        }

        return self::$arExtConf[self::KEY_XHPROF_URL];
    }


}
?>
