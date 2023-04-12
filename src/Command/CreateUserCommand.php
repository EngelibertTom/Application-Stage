<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    private $entityManager;
    private $passwordEncoder;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,
                                ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->container = $container;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Créer un nouveau utilisateur.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Création de l\'utilisateur',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');

        $username = $helper->ask($input, $output, new Question('Nom de l\'utilisateur: '));

        $questionEmail =  new Question('Email: ');
        $questionEmail->setValidator(function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException(
                    'Email invalide'
                );
            }

            $user = $this->entityManager->getRepository(User::class)->findByEmail($answer);
            if ($user) {
                throw new \RuntimeException(
                    'Email déjà utilisé'
                );
            }

            return $answer;
        });
        $email = $helper->ask($input, $output, $questionEmail);

        $password = $helper->ask($input, $output, new Question('Mot de passe: '));

        $arrayRoles = array_keys($this->container->getParameter('security.role_hierarchy.roles'));
        $role = $helper->ask($input, $output,  new ChoiceQuestion('Choisir un rôle: ', $arrayRoles, array_search('ROLE_USER', $arrayRoles, true)));

        if ($username && $email && $password && $role) {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setRoles([$role]);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
