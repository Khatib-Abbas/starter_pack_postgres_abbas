<?php
namespace App\DataProvider;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\ReservationSoap;
use EasyRdf\Literal\DateTime;
use ProxyManager\Factory\RemoteObject\Adapter\Soap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ReservationSoapDataProvider extends AbstractController implements  ItemDataProviderInterface, CollectionDataProviderInterface,RestrictedDataProviderInterface
{
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ReservationSoap | null
    {
        // vous devez vous même faire votre logique SOAP et éventuellement ajouter votre propre logique et importer votre paginiation
        //https://api-platform.com/docs/core/data-providers/#custom-collection-data-provider
        //https://github.com/api-platform/core/blob/main/src/DataProvider/PaginatorInterface.php
        //https://symfonycasts.com/screencast/api-platform-extending/paginator#pagination-and-your-data-provider

        $client = new \SoapClient( $this->getParameter('url_wsl'), array (	'login' => "Test_SOAP_v1",
            'password' => "Test_SOAP"
        ));
        $authKey = $client->register(array ('certificate' => $this->getParameter('colcal_cert_wsl')));
        $result_4 = $client->findReservations(
            ['login' => [
                'username' => "Test_SOAP_v1",
                'password' => "Test_SOAP",
                'applicationkey' => $authKey->key->applicationkey
            ],
            'begintime' => "20210706T000000",
            'endtime' => "20210707T000000",
            'searchobjects' => [
                 ['extid' => "room_E.W.1.302", 'type' => "room.allocated"],
                 ['extid' => "room_E.W.3.302", 'type' => "room.allocated"]
            ],
            'reservationstatuslist' => [
                'field' => ["B_COMPLETE", "B_INCOMPLETE", "C_CONFIRMED", "C_PRELIMINARY"]
            ],
            'returnfields' => [
                'field' => ["res.contactphone", "res.comment_public", "res.confirmationstatus"]
            ],
            'returntypes' =>[
                ['type' => "department", 'field' => "department.name"],
            ['type' => "reservationtype", 'field' => "activitytype.description"],
                ['type' => "room.allocated", 'field' => "room.name"]
            ]]);
        try {
            $req = json_encode($result_4, JSON_THROW_ON_ERROR | true);
            $req = json_decode($req, true, 512, JSON_THROW_ON_ERROR);
            //dd($req['result']['reservations']['reservation'][0]);
            $startAt = new DateTime($req['result']['reservations']['reservation'][0]['begin']);
            $endAt = new DateTime($req['result']['reservations']['reservation'][0]['end']);
            $department = $req['result']['reservations']['reservation'][0]['objects']['object'][0]['fields']['field']['value'];
            $reservationType = $req['result']['reservations']['reservation'][0]['objects']['object'][1]['fields']['field']['value'];
            $location = $req['result']['reservations']['reservation'][0]['objects']['object'][2]['fields']['field']['value'];
            return  new ReservationSoap(
                'Valentin pro du gif',
                $startAt->format('Y-m-d H:i:s'),
                $endAt->format('Y-m-d H:i:s'),
                $department,
                $reservationType,
                $location
        );
        } catch (\JsonException $e) {
            return  null;
        }
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === ReservationSoap::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null): ?array
    {

        $client = new \SoapClient( $this->getParameter('url_wsl'), array (	'login' => "Test_SOAP_v1",
            'password' => "Test_SOAP"
        ));
        $authKey = $client->register(array ('certificate' => $this->getParameter('colcal_cert_wsl')));
        $result_4 = $client->findReservations(
            ['login' => [
                'username' => "Test_SOAP_v1",
                'password' => "Test_SOAP",
                'applicationkey' => $authKey->key->applicationkey
            ],
                'begintime' => "20210706T000000",
                'endtime' => "20210707T000000",
                'searchobjects' => [
                    ['extid' => "room_E.W.1.302", 'type' => "room.allocated"],
                    ['extid' => "room_E.W.3.302", 'type' => "room.allocated"]
                ],
                'reservationstatuslist' => [
                    'field' => ["B_COMPLETE", "B_INCOMPLETE", "C_CONFIRMED", "C_PRELIMINARY"]
                ],
                'returnfields' => [
                    'field' => ["res.contactphone", "res.comment_public", "res.confirmationstatus"]
                ],
                'returntypes' =>[
                    ['type' => "department", 'field' => "department.name"],
                    ['type' => "reservationtype", 'field' => "activitytype.description"],
                    ['type' => "room.allocated", 'field' => "room.name"]
                ]]);
        try {
            $req = json_encode($result_4, JSON_THROW_ON_ERROR | true);
            $reqArray = json_decode($req, true, 512, JSON_THROW_ON_ERROR);
            $array = [];
            $cpt = 0;
            foreach ($reqArray['result']['reservations']['reservation'] as $item ){
                $startAt = new DateTime($item["begin"]);
                $endAt = new DateTime($item['end']);
                $department = $item['objects']['object'][0]['fields']['field']['value'];
                $reservationType = $item['objects']['object'][1]['fields']['field']['value'];
                $location = $item['objects']['object'][2]['fields']['field']['value'];
                $array[] = new ReservationSoap(
                    'Valentin pro du gif',
                    $startAt->format('Y-m-d H:i:s'),
                    $endAt->format('Y-m-d H:i:s'),
                    $department,
                    $reservationType,
                    $location
                );
                $cpt++;

            }
            //dd($reqArray['result']['reservations']['reservation']);
            return  $array;

        } catch (\JsonException $e) {
            return  null;
        }
    }
}
