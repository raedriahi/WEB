<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;


    class studentController extends AbstractController
    {

        #[Route("/student", name:"list_students")]
    public function index(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->render('showstudent.html.twig', [
            'students' => $students,
        ]);
    }


    }