<?php
/**
 * Created by PhpStorm.
 * User: steffen.goede
 * Date: 19.08.2016
 * Time: 16:44
 */

namespace Netresearch\NrPerfanalysis;


class XhprofHooker
{
    const PROFILE_PARAM = 'profile';
    const XHPROF_INSTALL_DIR = '/srv/www/xhprof/';
    const XHPROF_FRONTEND_URL = 'http://sobol.nr/xhprof/xhprof_html/';

    public function profilingInit()
    {
        if (!$this->isXhprofAvailable() || !$this->doProfiling()) {
            return;
        }

        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }



    /**
     * @ToDo
     */
    public function profilingFinish()
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
            $arId['MEMORY']
                = round(memory_get_peak_usage(true) / 1024 / 1024) . 'MiB';
            $strId = str_replace('.', '_', implode('_', $arId));

            $GLOBALS['STR_ID'] = $strId;

            include_once self::XHPROF_INSTALL_DIR . 'xhprof_lib/utils/xhprof_lib.php';
            include_once self::XHPROF_INSTALL_DIR . 'xhprof_lib/utils/xhprof_runs.php';

            $xhprof_runs = new \XHProfRuns_Default(self::XHPROF_INSTALL_DIR . '/xhprof_runs');
            $xhprof_runs->save_run($arXhprofData, $strId, $GLOBALS['RUN_ID']);
        }
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

            // @ToDo: make url configurable or try to read it
            $profilerUrl = sprintf(self::XHPROF_FRONTEND_URL . 'index.php?run=%s&source=%s', $GLOBALS['RUN_ID'], $GLOBALS['STR_ID']);
            $target = ' target="_blank"';
            $linkText = 'OPEN PROFILER OUTPUT';
        }

        return '<a href="' . $profilerUrl . '"' . $target  . ' onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true)">' . $linkText . '</a>';
    }



    /**
     * @ToDo: implement functionality
     * @return bool
     */
    protected function isXhprofAvailable ()
    {
        return function_exists('xhprof_enable');
    }



    /**
     * @ToDo
     * @return bool
     */
    protected function doProfiling ()
    {
        $profileParam = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP(self::PROFILE_PARAM);
        return !empty($profileParam);
    }
}

?>
