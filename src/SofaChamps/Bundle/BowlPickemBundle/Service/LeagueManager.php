<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\BowlPickemBundle\Entity\League;
use SofaChamps\Bundle\BowlPickemBundle\Event\LeagueEvents;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * LeagueManager
 *
 * @DI\Service("sofachamps.bp.league_manager")
 */
class LeagueManager
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "session" = @DI\Inject("session")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function createLeague($season)
    {
        $league = new League();
        $league->setSeason($season);

        $this->om->persist($league);

        return $league;
    }

    public function addUserToLeague(League $league, User $user)
    {
        $league->addUser($user);
    }

    public function addCommissionerToLeague(League $league, User $user)
    {
        $this->addUserToLeague($league, $user);
        $league->addCommissioner($user);
    }

    public function removeUserFromLeague(League $league, User $user)
    {
        $this->getLeagueRepository()->removeUser($league, $user);
    }

    private function getLeagueRepository()
    {
        return $this->om->getRepository('SofaChampsBowlPickemBundle:League');
    }
}
