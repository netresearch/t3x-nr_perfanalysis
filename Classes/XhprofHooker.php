<?php
/**
 * see class comment
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Steffen Göde <steffen.goede@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
namespace Netresearch\NrPerfanalysis;

/**
 * Handles the HTML-Rendering for XHProf and starts the profiling
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Steffen Göde <steffen.goede@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class XhprofHooker
{
    /**
     * Name of the get parameter that is used to activate the profiler
     */
    const PROFILE_PARAM = 'profile';


    /**
     * Initialises the profiler
     *
     * @return void
     */
    public function profilingInit()
    {
        if (!$this->doProfiling() || !$this->isXhprofAvailable()) {
            return;
        }

        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }


    /**
     * Generate HTML with statistics
     *
     * @return string HTML code
     */
    public function genHtml()
    {
        if (!$this->isXhprofAvailable()) {
            return '';
        }

        $profileParam = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP(self::PROFILE_PARAM);
        if (empty($profileParam)) {
            // Start profiling
            $profilerUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
            $profilerUrl .= (strpos($profilerUrl, '?') !== false ? '&' : '?') . self::PROFILE_PARAM . '=1';
            $target = '';
            $linkText = 'START PROFILING';
        } else {
            $this->profilingFinish();

            $profilerUrl = sprintf(
                Config::getXhprofUrl() . 'xhprof_html/index.php?run=%s&source=%s',
                $GLOBALS['RUN_ID'],
                $GLOBALS['STR_ID']
            );
            $target = ' target="_blank"';
            $linkText = 'OPEN PROFILER OUTPUT';
        }

        return '<a href="' . $profilerUrl . '"' . $target  .
               ' onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true)">' .
               $linkText . '</a>';
    }


    /**
     * Checks if profiling should be executed
     *
     * @return bool
     */
    protected function doProfiling ()
    {
        $profileParam = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP(self::PROFILE_PARAM);
        return !empty($profileParam);
    }


    /**
     * Checks the availability of xhprof
     *
     * @return bool
     */
    protected function isXhprofAvailable ()
    {
        return TYPO3_MODE == 'FE'
               && function_exists('xhprof_enable')
               && $this->includeFiles();
    }



    /**
     * Include all necessary files. Returns true if it was successful
     *
     * @return bool
     */
    protected function includeFiles ()
    {
        $files = array (
            Config::getXhprofDir() . 'xhprof_lib/utils/xhprof_lib.php',
            Config::getXhprofDir() . 'xhprof_lib/utils/xhprof_runs.php',
        );

        foreach ($files as $file) {
            if (!file_exists($file)) {
                return false;
            }
            include_once $file;
        }

        return true;
    }


    /**
     * Finishes the xhprof profiling and saves the run
     *
     * @return void
     */
    protected function profilingFinish()
    {
        $GLOBALS['RUN_ID'] = uniqid();

        $arXhprofData = xhprof_disable();

        if (null !== $arXhprofData) {

            $arId['APP']    = 'TYPO3';
            $arId['SERVER'] = $_SERVER['SERVER_NAME'];

            if (!empty($_REQUEST['eID'])) {
                $arId['eID'] = 'eID_' . $_REQUEST['eID'];
            } elseif (is_object($GLOBALS['TSFE'])) {
                $arId['PID'] = 'PID_' . $GLOBALS['TSFE']->id;
            } else {
                $arId['ID'] = 'UNKNOWN';
            }

            if (!empty($_SERVER['REQUEST_TIME_FLOAT'])) {
                $nStartTime = $_SERVER['REQUEST_TIME_FLOAT'];
            } else {
                $nStartTime = $GLOBALS['TYPO3_MISC']['microtime_start'];
            }
            $arId['TIME'] = round((microtime(true) - $nStartTime) * 1000) . 'ms';
            $arId['MEMORY'] = round(memory_get_peak_usage(true) / 1024 / 1024) . 'MiB';
            $strId = str_replace('.', '_', implode('_', $arId));

            $GLOBALS['STR_ID'] = $strId;

            $xhprof_runs = new \XHProfRuns_Default(Config::getXhprofDir() . '/xhprof_runs');
            $xhprof_runs->save_run($arXhprofData, $strId, $GLOBALS['RUN_ID']);
        }
    }
}

?>
