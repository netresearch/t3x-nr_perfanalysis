<?php
/**
 * Part of nr_perfanalysis
 *
 * PHP version 5
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <weiske@mogic.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://mogic.com/
 */
namespace Netresearch\NrPerfanalysis;

/**
 * Provides hooks for the doctrine database connection
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <weiske@mogic.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://mogic.com/
 */
class DoctrineQueryHooker
    implements \Doctrine\DBAL\Logging\SQLLogger
{
    /**
     * @var Counter
     */
    protected $counter;

    /**
     * Initialize the counter variable
     */
    public function __construct()
    {
        $this->counter = Counter::get();
    }

    /**
     * Logs a SQL statement somewhere.
     *
     * @param string     $sql    The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types  The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->counter->start('sql', '');
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        $this->counter->finish('sql', '');
    }
}
?>
