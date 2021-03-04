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
class Counter
{
    /**
     * Counter for events in the different groups.
     * Key is the group (e.g. 'sql'), value an array of
     * events with the event name being the key, the number of calls
     * the value.
     *
     * Example:
     *   $counts['sql']['insert'] = 2;
     *
     * @var array
     */
    public $counts = array();

    /**
     * @var array
     */
    public $timesums = array();

    /**
     * Temporary variable for duration calculations
     *
     * @var array
     */
    protected $timers = array();

    /**
     * Singleton instance
     *
     * @var self
     */
    protected static $instance;


    /**
     * Get singleton instance
     *
     * @return Counter
     */
    public static function get()
    {
        if (self::$instance === null) {
            if (Config::isEnabled()) {
                self::$instance = new self();
            } else {
                //needed so that extensions can use the counter
                // without
                self::$instance = new NullCounter();
            }
        }
        return self::$instance;
    }

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
        if (!isset($this->counts[$group][$event])) {
            $this->counts[$group][$event] = 0;
        }
        ++$this->counts[$group][$event];

        $this->timers[$group][$event] = microtime(true);
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
        $this->timesums[$group][$event] +=
            microtime(true) - $this->timers[$group][$event];
    }
}
?>
