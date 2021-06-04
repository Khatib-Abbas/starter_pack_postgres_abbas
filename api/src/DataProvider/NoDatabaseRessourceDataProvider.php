<?php
namespace App\DataProvider;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\NoDatabaseRessource;
use EasyRdf\Literal\DateTime;
use ProxyManager\Factory\RemoteObject\Adapter\Soap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use App\Service\NoDatabaseStatsHelper;

class NoDatabaseRessourceDataProvider extends AbstractController implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface,CollectionDataProviderInterface,RestrictedDataProviderInterface
{

    private NoDatabaseStatsHelper $statsHelper;
    /**
     * @var Pagination
     */
    private Pagination $pagination;

    public function  __construct(NoDatabaseStatsHelper $statsHelper,Pagination $pagination){
        $this->statsHelper =$statsHelper;
        $this->pagination = $pagination;
    }
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): NoDatabaseRessource
    {
        return $this->statsHelper->fetchOne();
    }


    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        // add we add the context in this method etc https://symfonycasts.com/screencast/api-platform-extending/pagination-context
        [$page, $offset, $limit] = $this->pagination->getPagination($resourceClass, $operationName,$context);
        return new NoDatabaseRessourceDataPaginator($this->statsHelper, $page,$limit);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === NoDatabaseRessource::class;
    }
}
