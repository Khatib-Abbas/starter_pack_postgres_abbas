<?php
namespace App\DataProvider;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use Exception;
use Traversable;
use App\Service\NoDatabaseStatsHelper;

class NoDatabaseRessourceDataPaginator  implements PaginatorInterface, \IteratorAggregate
{
    private $noDatabaseIterator;
    private $statsHelper;
    private int $currentPage;
    private int $maxResults;

    public function __construct(NoDatabaseStatsHelper $statsHelper , int $currentPage, int $maxResults)
    {
        $this->statsHelper = $statsHelper;
        $this->currentPage = $currentPage;
        $this->maxResults = $maxResults;
    }
    public function getLastPage(): float
    {
        return ceil($this->getTotalItems() / $this->getItemsPerPage()) ?: 1.;
    }
    public function getTotalItems(): float
    {
        return $this->statsHelper->count();
    }
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }
    public function getItemsPerPage(): float
    {
        return $this->maxResults;
    }
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    public function getIterator(): Traversable|\ArrayIterator
    {
        if ($this->noDatabaseIterator === null) {
            $offset = (($this->getCurrentPage() - 1) * $this->getItemsPerPage());
            //$this->noDatabaseIterator = new \ArrayIterator($this->statsHelper->fetchMany());
            // todo - actually go "load" the stats
            $this->noDatabaseIterator = new \ArrayIterator(
                $this->statsHelper->fetchMany(
                    $this->getItemsPerPage(),
                    $offset
            ));
        }
        return $this->noDatabaseIterator;
    }
}
