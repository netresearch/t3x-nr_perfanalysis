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
class TrafficCounter
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
     * Singleton instance
     *
     * @var TrafficCounter
     */
    protected static $instance;


    /**
     * Get singleton instance
     *
     * @return TrafficCounter
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
    public function start($type, $strGroup)
    {
        if (!is_array($this->counts[$type])) {
            $this->counts[$type] = [];
            $this->counts[$type][$strGroup] = [];
            $this->counts[$type][$strGroup]['count'] = 0;
            $this->counts[$type][$strGroup]['size'] = 0;
        }

        $this->counts[$type][$strGroup]['count']++;
    }

    /**
     * Stops an event (e.g. finish SQL query)
     *
     * @param string $group Group name, e.g. "sql"
     * @param string $event Event in that group, e.g. "select"
     *
     * @return void
     */
    public function finish($type, $strGroup, $data)
    {
        $this->counts[$type][$strGroup]['size'] += strlen($data);
    }

    /**
     * Returns the html
     *
     * @return string
     */
    public function getHtml()
    {
        $content = '<div class="traffic-tracker" style="text-align: left"> Traffic Tracking';

        $sumTraffic = 0;
        $sumConnections = 0;

        foreach ($this->counts as $type => $groups) {
            $content .= '<div class="traffic-tracker-entry">';
            $content .= '<span class="traffic-name">' . $type . '</span>';
            $content .= '<span class="traffic-counts">';
            foreach ($groups as $group => $data) {
                $sumTraffic += $data['size'];
                $sumConnections += $data['count'];

                $content .= '<div class="traffic-group">';
                $content .= ' ' . $group . ': ';
                $content .= '<span class="traffic-count">x ' . $data['count'] . '</span> | ';
                $content .= '<span class="traffic-size">' .round(($data['size'] / 1024) , 2) . ' Kb</span>';
                $content .= '</div>';
            }
            $content .= '</span>';
            $content .= '</div>';
        }

        $sumTraffic = round(($sumTraffic / 1024) , 2);
        $content .= "Total Traffic: $sumTraffic Kb, Total Connections: $sumConnections";
        $content .= '</div>';

        return $content;
    }
}