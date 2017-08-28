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
use TYPO3\CMS\Core\Database as db;

/**
 * Provides hooks for the database connection
 *
 * @category Netresearch
 * @package  Perfanalysis
 * @author   Christian Weiske <christian.weiske@netresearch.de>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 * @link     http://www.netresearch.de/
 */
class QueryHooker
    implements db\PreProcessQueryHookInterface,
    db\PostProcessQueryHookInterface,
    \TYPO3\CMS\Core\SingletonInterface
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
     * Pre-processor for the SELECTquery method.
     *
     * @param string $select_fields Fields to be selected
     * @param string $from_table    Table to select data from
     * @param string $where_clause  Where clause
     * @param string $groupBy       Group by statement
     * @param string $orderBy       Order by statement
     * @param int    $limit         Database return limit
     * @param object $parentObject  DB connection
     *
     * @return void
     */
    public function SELECTquery_preProcessAction(
        &$select_fields, &$from_table, &$where_clause, &$groupBy,
        &$orderBy, &$limit,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'select');
    }

    /**
     * Pre-processor for the INSERTquery method.
     *
     * @param string       $table         Database table name
     * @param array        $fieldsValues  Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function INSERTquery_preProcessAction(
        &$table, array &$fieldsValues, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'insert');
    }

    /**
     * Pre-processor for the INSERTmultipleRows method.
     * BEWARE: When using DBAL, this hook will not be called at all. Instead,
     * INSERTquery_preProcessAction() will be invoked for each row.
     *
     * @param string       $table         Database table name
     * @param array        $fields        Field names
     * @param array        $rows          Table rows
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function INSERTmultipleRows_preProcessAction(
        &$table, array &$fields, array &$rows, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'insert');
    }

    /**
     * Pre-processor for the UPDATEquery method.
     *
     * @param string       $table         Database table name
     * @param string       $where         WHERE clause
     * @param array        $fieldsValues  Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function UPDATEquery_preProcessAction(
        &$table, &$where, array &$fieldsValues, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'update');
    }

    /**
     * Pre-processor for the DELETEquery method.
     *
     * @param string $table        Database table name
     * @param string $where        WHERE clause
     * @param object $parentObject DB connection
     *
     * @return void
     */
    public function DELETEquery_preProcessAction(
        &$table, &$where,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'delete');
    }

    /**
     * Pre-processor for the TRUNCATEquery method.
     *
     * @param string $table        Database table name
     * @param object $parentObject DB connection
     *
     * @return void
     */
    public function TRUNCATEquery_preProcessAction(
        &$table, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->start('sql', 'truncate');
    }

    /**
     * Post-processor for the SELECTquery method.
     *
     * @param string $select_fields Fields to be selected
     * @param string $from_table    Table to select data from
     * @param string $where_clause  Where clause
     * @param string $groupBy       Group by statement
     * @param string $orderBy       Order by statement
     * @param int    $limit         Database return limit
     * @param object $parentObject  DB connection
     *
     * @return void
     */
    public function exec_SELECTquery_postProcessAction(
        &$select_fields, &$from_table, &$where_clause, &$groupBy, &$orderBy,
        &$limit, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'select');
    }

    /**
     * Post-processor for the exec_INSERTquery method.
     *
     * @param string       $table         Database table name
     * @param array        $fieldsValues  Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function exec_INSERTquery_postProcessAction(
        &$table, array &$fieldsValues, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'insert');
    }

    /**
     * Post-processor for the exec_INSERTmultipleRows method.
     *
     * @param string       $table         Database table name
     * @param array        $fields        Field names
     * @param array        $rows          Table rows
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function exec_INSERTmultipleRows_postProcessAction(
        &$table, array &$fields, array &$rows, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'insert');
    }

    /**
     * Post-processor for the exec_UPDATEquery method.
     *
     * @param string       $table         Database table name
     * @param string       $where         WHERE clause
     * @param array        $fieldsValues  Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param object       $parentObject  DB connection
     *
     * @return void
     */
    public function exec_UPDATEquery_postProcessAction(
        &$table, &$where, array &$fieldsValues, &$noQuoteFields,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'update');
    }

    /**
     * Post-processor for the exec_DELETEquery method.
     *
     * @param string $table        Database table name
     * @param string $where        WHERE clause
     * @param object $parentObject DB connection
     *
     * @return void
     */
    public function exec_DELETEquery_postProcessAction(
        &$table, &$where,
        \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'delete');
    }

    /**
     * Post-processor for the exec_TRUNCATEquery method.
     *
     * @param string $table        Database table name
     * @param object $parentObject DB connection
     *
     * @return void
     */
    public function exec_TRUNCATEquery_postProcessAction(
        &$table, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
    ) {
        $this->counter->finish('sql', 'truncate');
    }
}

?>
