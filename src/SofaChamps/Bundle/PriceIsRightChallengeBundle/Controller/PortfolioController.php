<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Bracket;
use SofaChamps\Bundle\MarchMadnessBundle\Entity\Game;
use SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio;

/**
 * @Route("/{season}/portfolio/{id}")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/edit", name="pirc_portfolio_edit")
     * @ParamConverter("portfolio", class="SofaChampsPriceIsRightChallengeBundle:Portfolio", options={"id" = "id"})
     * @Secure(roles="ROLE_USER")
     * @SecureParam(name="portfolio", permissions="EDIT")
     * @Method({"GET"})
     * @Template
     */
    public function editAction(Portfolio $portfolio, $season)
    {
        $portfolio = $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Portfolio')->getPopulatedPortfolio($portfolio);
        $form = $this->getPortfolioForm($portfolio);

        // Hack since the form isn't bound to the model
        $form->get('name')->setData($portfolio->getName());

        $game = $portfolio->getGame();
        $config = $game->getConfig();
        $bracket = $game->getBracket();

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'bracket' => $bracket,
            'config' => $game->getConfig(),
        );
    }

    /**
     * @Route("/update", name="pirc_portfolio_update")
     * @ParamConverter("bracket", class="SofaChampsMarchMadnessBundle:Bracket", options={"id" = "season"})
     * @Secure(roles="ROLE_USER")
     * @SecureParam(name="portfolio", permissions="EDIT")
     * @Method({"POST"})
     * @Template("SofaChampsPriceIsRightChallengeBundle:Portfolio:edit.html.twig")
     */
    public function updateAction(Portfolio $portfolio, $season)
    {
        $portfolio = $this->getRepository('SofaChampsPriceIsRightChallengeBundle:Portfolio')->getPopulatedPortfolio($portfolio);
        $form = $this->getPortfolioForm($portfolio);
        $game = $portfolio->getGame();
        $bracket = $game->getBracket();

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $this->getEntityManager()->flush();

            $this->addMessage('success', 'Portfolio Updated');

            return $this->redirect($this->generateUrl('pirc_portfolio_edit', array(
                'season' => $season,
                'id' => $portfolio->getId(),
            )));
        } else {
            $this->addMessage('warning', 'There was an error updating your portfolio');
        }

        return array(
            'portfolio' => $portfolio,
            'season' => $season,
            'form' => $form->createView(),
            'bracket' => $bracket,
            'config' => $game->getConfig(),
        );
    }
}
