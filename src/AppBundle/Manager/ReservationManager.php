<?php

namespace AppBundle\Manager;

use AppBundle\Cache\Redis;
use AppBundle\Entity\Reservation;
use AppBundle\Factory\ReservationFactory;
use AppBundle\Provider\DateProvider;
use AppBundle\Repository\ReservationRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class ReservationManager extends BaseManager
{
    /**
     * @var ReservationRepository
     */
    protected $repository;

    /**
     * @var ReservationFactory
     */
    protected $reservationFactory;

    /**
     * @var DateProvider
     */
    protected $dateProvider;

    /**
     * @var Redis
     */
    protected $redis;

    public function setReservationFactory(ReservationFactory $reservationFactory)
    {
        $this->reservationFactory = $reservationFactory;
    }

    public function setDateProvider(DateProvider $dateProvider)
    {
        $this->dateProvider = $dateProvider;
    }

    public function setRedis(Redis $redis)
    {
        $this->redis = $redis;
    }


    public function findReservedDays(\DateTime $dateTime, string $surgery): array
    {
        $query = $this->repository->createReservationDayQuery($dateTime, $surgery);

        return $query->execute();
    }

    public function countByDateInterval(\DateTime $from, \DateTime $to): int
    {
        return count($this->findByDatePeriod($from, $to));
    }

    public function findByDatePeriod( \DateTime $from, \DateTime $to, array $paginatorParams = null): array
    {
        $redisAdapter = RedisAdapter::createConnection('redis://localhost');
        $redisAdapter->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);

        list($beginDateTime, $endDateTime) = $this->dateProvider->calculateBeginAndEndOfSearchDay($from, $to, 'last monday', 'next saturday');

        $keys = $this->dateProvider->generateCacheKeys($beginDateTime, $endDateTime);

        $fromCache = $redisAdapter->mget($keys);
        $redisAdapter->flushAll();
        die;

        $dataFromCache = [];
        $toDb = [];
        foreach ($fromCache as $key => $value){
            $day = $keys[$key];

            if($value !== false){
                $dataFromCache[$day] = $value;
                $hasCachedItem = true;
            }else{
                $toDb[] = $day;
            }
        }

        if(!empty($toDb)){
            $formattedResult = [];
            $queryForDays = $this->repository->createDateIntervalQuery($toDb);
            $result = $queryForDays->execute();

            foreach ($result as $reservation){
                $formattedDay = $reservation->getDay()->format('Y-m-d');
                /** @var Reservation $reservation */
                $formattedResult[$formattedDay][sprintf('%s_%s', $formattedDay, $reservation->getHour())] = $reservation;
            }

            // a nem létező recordokat is cacheljük üres array-jel
            $formattedResult = array_merge($formattedResult, array_fill_keys(array_diff($toDb, array_keys($formattedResult)), []));

            $redisAdapter->mset($formattedResult);

            $fromCache = array_filter($fromCache);
            if(isset($hasCachedItem)){
                $reservations = [];
                foreach ($fromCache as $days){
                    foreach ($days as $reservation){
                        $reservations[$reservation->getDay()->format('Y-m-d')][] = $reservation;
                    }
                }
                return array_filter(array_merge($formattedResult, $reservations));
            }else{
                return array_filter($formattedResult);
            }

        } else{
            return array_filter($dataFromCache);
        }

            if(!empty($paginatorParams)){
                return $this->getResultByPaginator($findResult, $paginatorParams);
            }
    }

    public function doSaveEntity(Reservation $entity)
    {
        $this->save($entity);
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function removeReservationByHour(int $id, int $hour)
    {
        $this->repository->removeReservationByHour($id, $hour);
    }

    protected function getResultByPaginator(Query $query, array $params): array
    {
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(true);

        $paginator
            ->getQuery()
            ->setFirstResult($params['pageSize'] * ($params['currentPage'] - 1))
            ->setMaxResults($params['pageSize']);

        return iterator_to_array($paginator->getIterator());
    }


}