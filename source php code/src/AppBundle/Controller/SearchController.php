<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Factory\SearchFactory;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\AppBundle\Entity\Repository\SearchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SearchController extends Controller
{
    /**
     * @var SearchRepository
     */
    private $searchRepository;

    /**
     * @var SearchFactory
     */
    private $searchFactory;

    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    public function __construct(
        SearchRepository $searchRepository,
        SearchFactory $searchFactory,
        ClassifiedRepository $classifiedRepository
    ) {
        $this->searchRepository = $searchRepository;
        $this->searchFactory = $searchFactory;
        $this->classifiedRepository = $classifiedRepository;
    }

    /**
     * @Route("/cauta", name="search")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function searchAction(Request $request)
    {
        $query = $request->query->get('q');

        $search = $this->searchRepository->findOneByQuery($query);

        if (!$search) {
            $search = $this->searchFactory->create($query);
        }

        $search->incrementViews();
        $this->searchRepository->save($search);

        return [
            'classifieds' => $this->classifiedRepository->findBySearch($query, $request->query->get('page', 1)),
            'query' => $query,
        ];
    }
}
