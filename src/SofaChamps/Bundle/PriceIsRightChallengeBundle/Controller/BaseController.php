<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use Doctrine\ORM\NoResultException;
use SofaChamps\Bundle\CoreBundle\Controller\CoreController;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Form\ConfigFormType;

class BaseController extends CoreController
{
    protected function getGameManager()
    {
        return $this->container->get('sofachamps.pirc.game_manager');
    }

    protected function getPortfolioManager()
    {
        return $this->container->get('sofachamps.pirc.portfolio_manager');
    }

    protected function getUserPortfolio(User $user, $season)
    {
        try {
            return $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Portfolio')->getUserPortfolio($user, $season);
        } catch (NoResultException $e) {
            return $this->getPortfolioManager()->createPortfolio($user, $season);
        }
    }

    protected function getPortfolioForm(Bracket $bracket, Portfolio $portfolio = null)
    {
        $builder = $this->createFormBuilder();
        $teams = $bracket->getTeams();

        foreach (range(1, 16) as $seed) {
            $seedTeams = $teams->filter(function($team) use ($seed) {
                return $team->getRegionSeed() == $seed;
            });

            $choices = array();
            foreach ($seedTeams as $bracketTeam) {
                $team = $bracketTeam->getTeam();
                $choices[$team->getId()] = $team->getName();
            }
            $builder->add(sprintf('seed%d', $seed), 'choice', array(
                'choices' => $choices,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ));
        }

        return $builder->getForm();
    }

    protected function getConfigForm(Config $config)
    {
        return $this->createForm(new ConfigFormType(), $config);
    }
}
