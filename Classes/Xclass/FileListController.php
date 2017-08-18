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
namespace Netresearch\NrPerfanalysis\Xclass;
use \Netresearch\NrPerfanalysis\HtmlRenderer;

/**
 * Counts events and times
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class FileListController
    extends \TYPO3\CMS\Filelist\Controller\FileListController
{
    /**
     * Outputting the accumulated content to screen
     *
     * @return void
     */
    public function printContent()
    {
        parent::printContent();

        $hr = new HtmlRenderer();
        echo $hr->genHtml();
    }
}
?>
