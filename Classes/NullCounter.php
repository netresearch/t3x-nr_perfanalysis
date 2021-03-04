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
 * Imlements the counter interface, but does not actually do anything
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class NullCounter extends Counter
{
    /**
     * Start an event (e.g. start SQL query)
     *
     * @param string $group Group name, e.g. "sql"
     * @param string $event Event in that group, e.g. "select"
     *
     * @return void
     */
    public function start($group, $event)
    {
    }

    /**
     * Stops an event (e.g. finish SQL query)
     *
     * @param string $group Group name, e.g. "sql"
     * @param string $event Event in that group, e.g. "select"
     *
     * @return void
     */
    public function finish($group, $event)
    {
    }
}
?>
