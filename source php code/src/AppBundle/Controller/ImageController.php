<?php
declare(strict_types = 1);

namespace App\AppBundle\Controller;

use App\ImageBundle\Service\ImagePathBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\AppBundle\Entity\City;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageController extends Controller
{
    /**
     * @var ImagePathBuilder
     */
    private $imagePathBuilder;

    public function __construct(ImagePathBuilder $imagePathBuilder)
    {
        $this->imagePathBuilder = $imagePathBuilder;
    }

    /**
     * @Route("/uploads/anunt{id}/thumbs/{file}", name="get.image.thumb")
     *
     * @return Response
     */
    public function imageThumbAction($id, $file)
    {
        $fileImage        = $this->imagePathBuilder->getThumb((int)$id, $file);

        return new BinaryFileResponse($fileImage);
    }

    /**
     * @Route("/uploads/anunt{id}/{file}", name="get.image")
     *
     * @return Response
     */
    public function imageAction($id, $file)
    {
        return new BinaryFileResponse($this->imagePathBuilder->get((int)$id, $file));
    }

    /**
     * Fix old url's
     * This only applies to old classifieds
     *
     * @Route("/uploads/thumbs/{file}", name="get.old.thumb")
     *
     * @return Response
     */
    public function getOldThumb($file)
    {
        $image = $this->get('repository.image')->getOldThumb($file);

        if (!$image->getClassified()->getOldId()) {
            throw new HttpException(404, 'Thumb does not exist.');
        }

        return $this->redirectToRoute("get.image.thumb", [
            'id'   => $image->getClassified()->getOldId(),
            'file' => $file,
        ], 301);
    }
}
