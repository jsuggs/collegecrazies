<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findUsersInLeague(League $league)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.leagues', 'l')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $league->getId())
            ->getQuery()
            ->getResult();
    }

    public function findUsersInLeagueWithIncompletePicksets(League $league)
    {
        $numGames = $this
            ->getEntityManager()
            ->createQuery('SELECT COUNT(g.id) FROM CollegeCraziesMainBundle:Game g')
            ->getSingleScalarResult();

        $users = $this->createQueryBuilder('u')
            ->innerJoin('u.leagues', 'l')
            ->innerJoin('l.pickSets', 'lps')
            ->innerJoin('lps.picks', 'lp')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $league->getId())
            ->getQuery()
            ->getResult();

        return array_filter($users, function($user) use ($numGames, $league) {
            $picks = array_filter(iterator_to_array($league->getPickSetForUser($user)->getPicks()), function ($pick) {
                $team = $pick->getTeam();
                return isset($team);
            });
            return count($picks) < $numGames;
        });
    }
}
