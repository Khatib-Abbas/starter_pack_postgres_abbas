<?php
namespace App\Service;
use App\Entity\NoDatabaseRessource;
use EasyRdf\Literal\DateTime;

class NoDatabaseStatsHelper
{

    // https://symfonycasts.com/screencast/api-platform-extending/custom-resource-item
    public function fetchMany(int $limit = null, int $offset = null, array $criteria = []): array
    {
        //dd($offset,$limit,$criteria);
        // faire votre logique
        // implémenter les critères (int $limit = null, int $offset = null, array $criteria = []
        $i = 0;
        $stats = [];
                foreach ($this->fetchStatsData() as $statData){
                    $i++;
                    if ($offset >= $i) {
                        continue;
                    }
                    $stats[] = $statData;
                    if (count($stats) >= $limit) {
                        break;
                    }
                }
        return $stats;
    }
    public function fetchOne(): ?NoDatabaseRessource
    {
        return new NoDatabaseRessource(
            'akha0004',
            ["data"=>'bloc'],
        );
    }
    private function fetchStatsData(): array
    {
        $statsData = [];
        foreach (["ak0001","ak0002","ak0003","ak0004","ak0005","ak0006","ak0007","ak0008","ak0009","ak00010","ak0011"] as $item){
            $statsData[] = new NoDatabaseRessource(
                $item,
                ["data"=>'bloc'],
            );
        }
        return $statsData;
    }
    public function count(): int
    {
        // avoir le count total des data
        return count($this->fetchStatsData());
    }



}
