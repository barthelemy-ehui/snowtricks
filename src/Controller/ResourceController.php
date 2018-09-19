<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResourceController extends Controller
{
    
    /**
     * @var ResourceRepository
     */
    private $resourceRepository;
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    public function __construct(
        ResourceRepository $resourceRepository,
        TrickRepository $trickRepository,
        FileUploader $fileUploader
    )
    {
    
        $this->resourceRepository = $resourceRepository;
        $this->trickRepository = $trickRepository;
        $this->fileUploader = $fileUploader;
    }
    
    /**
     * @Route("/media/delete/{slug}/{id}", name="media_delete")
     */
    public function delete($slug, $id)
    {
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        $resource = $this->resourceRepository->findOneBy(['id' => $id]);
        
        $this->fileUploader->deleteFile($resource->getName());
        $trick->removeResource($resource);
        $this->trickRepository->save($trick);
        
        return $this->redirectToRoute('trick_edit', [
            'slug' => $trick->getSlug()
        ]);
        
    }

    /**
     * @Route("/media/edit/{slug}/{id}", name="media_edit")
     */
    public function edit(Request $request, $slug, $id)
    {
        $file = current($request->files->get('resource'));
        $extension = $file->guessExtension();
        $filename = $this->fileUploader->upload($file);
        $filetype = $this->fileUploader->getFileType($extension);
        
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);

        /** @var Resource $resourceFound */
        $resourceFound = null;

        foreach($trick->getResources() as $resource) {
            if($resource->getId() == $id) {
                $resourceFound = $resource;
                break;
            }
        }
        
        $oldFilename = $resourceFound->getName();
        $resourceFound->setName($filename);
        $resourceFound->setType($filetype);
        $this->fileUploader->deleteFile($oldFilename);
        $this->resourceRepository->save($resourceFound);
        
        return $this->redirectToRoute('trick_edit', [
           'slug' => $trick->getSlug()
        ]);
    }

}
