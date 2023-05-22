<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use App\Entity\Usuario;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UsuarioController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class UsuarioController extends AbstractController{

    private $logger;
    private $usuarioRepository;


    public function __construct(LoggerInterface $logger,UsuarioRepository $usuarioRepository){
        $this ->logger = $logger;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @Route("usuario/listar", name="usuario_list", methods={"GET"})
     */
    public function list(){

        $this ->logger -> info('Metodo Listar llamado');

        $response = new JsonResponse();

        $usuarios = $this->usuarioRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellido(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()->format('Y-m-d'), 
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

      /**
     * @Route("usuario", name="usuario", methods={"POST"})
     */
    public function add(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'];
        $apellido = $data['apellidos'];
        $fechaNacimientoStr = $data['fechaNacimiento'];
    
        if (empty($nombre) || empty($apellido)) {
            return new JsonResponse(['errors' => "¡Parámetros obligatorios!"], Response::HTTP_BAD_REQUEST);
        }
        
        $usuario = new Usuario();  
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setFechaNacimiento(new \DateTime($fechaNacimientoStr));
        
        $errors = $validator->validate($usuario);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $this->logger->error($error->getMessage());
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }else{
            $this->usuarioRepository->save($usuario);
            return new JsonResponse(['status' => '¡Usuario creado!'], Response::HTTP_CREATED);
        }        
    }
}