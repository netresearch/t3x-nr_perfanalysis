<?php
declare(encoding = 'UTF-8');
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
 * Render the collected data
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class HtmlRenderer
{
    /**
     * Render statistics into HTML after everything is finished
     *
     * @param array  $arParams Parameter from render function
     * @param object $tsfe     TypoScript frontend controller
     *
     * @return void
     */

    public function contentPostProcOutput(
        $arParams,
        \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $tsfe
    ) {
        $tsfe->content = str_replace(
            '</body>',
            $this->genHtml() . '</body>',
            $tsfe->content
        );
    }

    /**
     * Generate HTML with statistics
     *
     * @return string HTML code
     */
    public function genHtml()
    {
        $counter = Counter::get();

        $htmlstr = array();
        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            //only available since PHP 5.4.0
            $pagetime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
            $htmlstr[] = 'Page: ' . number_format($pagetime, 3) . 's';
        }

        $counts = array();
        $times  = array();
        foreach ($counter->counts as $group => $events) {
            foreach ($events as $event => $number) {
                $counts[$group] += $number;
                $times[$group] += $counter->timesums[$group][$event];
            }
            $times[$group] = number_format($times[$group], 3);
        }
        ksort($counts);
        foreach ($counts as $group => $number) {
            $htmlstr[] = $group
                . ': ' . $counts[$group]
                . '⨯, ' . $times[$group] . 's';
        }

        $str = implode(', ', $htmlstr);
        $html = <<<HTM
<style>
  #perfanalysis {
    position:           fixed;
    right:              0;
    bottom:             0;
    background-color:   rgba(0,0,0,0.5);
    color:              #FFFFFF;
    border-top:         1px solid rgba(240,240,240,0.4);
    border-left:        1px solid rgba(240,240,240,0.4);
    padding:            1px 8px 1px 8px;
    font-size:          12px;
    z-index:            90000;
    transition:         background-color 0.25s, font-size 0.15s ease-in-out;
    -moz-transition:    background-color 0.25s, font-size 0.15s ease-in-out;
    -webkit-transition: background-color 0.25s, font-size 0.15s ease-in-out;
  }

  #perfanalysis:hover {
    background-color:   rgba(0,0,0,0.8);
    padding:            2px 8px 2px 8px;
    font-size:          16px;
  }
</style>
<div id="perfanalysis" onclick="document.getElementById('perfanalysis').remove();"><span id="perfanalysisbrowser"></span>
 $str
</div>
<script type="text/javascript">
if (typeof performance != "undefined") {
  function seconds(ms) {
        return (ms / 1000).toFixed(2) + 's, ';
    }
    window.addEventListener('load', function() {
     var t = window.performance.timing,
            interactive = t.domInteractive - t.domLoading,
            dcl = t.domContentLoadedEventStart - t.domLoading,
            complete = t.domComplete - t.domLoading;
        var stats = 'Browser: ' + seconds(interactive);
        //  + 'Complete: ' + seconds(complete) + ', ';
        document.getElementById('perfanalysisbrowser').innerHTML = stats;
        // "Total: " + (new Date().getTime() - performance.timing.navigationStart) / 1000 + "s, " +
    });
}
</script>
HTM;
        return $html;
    }
}
?>
