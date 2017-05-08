<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Model\User;

use \FOS\UserBundle\Command\CreateUserCommand as BaseCommande;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateUserCommand extends BaseCommande
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('fos:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputArgument('nom', InputArgument::REQUIRED, 'The nom'),
                new InputArgument('prenom', InputArgument::REQUIRED, 'The prenom'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ))
            ->setHelp(<<<EOT
The <info>fos:user:create</info> command creates a user:

  <info>php app/console fos:user:create matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php app/console fos:user:create matthieu matthieu@example.com mypassword</info>

You can create a super admin via the super-admin flag:

  <info>php app/console fos:user:create admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php app/console fos:user:create thibault --inactive</info>

EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $nom        = $input->getArgument('nom');
        $prenom      = $input->getArgument('prenom');
        $inactive   = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

        $user_manager = $this->getContainer()->get('fos_user.user_manager');
 
        /** @var \Acme\AcmeUserBundle\Entity\User $user */
        $user = $user_manager->createUser();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) !$inactive);
        $user->setSuperAdmin((Boolean) $superadmin);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setFirst(0);
 
        $user_manager->updateUser($user);
      
        
        

        $output->writeln(sprintf('Created user <comment>%s</comment>', $user->getEmail()));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        
        if (!$input->getArgument('email')) {
            $email = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an email:',
                function($email) {
                    if (empty($email)) {
                        throw new \Exception('Email can not be empty');
                    }

                    return $email;
                }
            );
            $input->setArgument('email', $email);
        }

        if (!$input->getArgument('password')) {
            $password = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a password:',
                function($password) {
                    if (empty($password)) {
                        throw new \Exception('Password can not be empty');
                    }

                    return $password;
                }
            );
            $input->setArgument('password', $password);
        }
        
        if (!$input->getArgument('nom')) {
            $nom = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a nom :',
                function($nom) {
                    if (empty($nom)) {
                        throw new \Exception('Nom can not be empty');
                    }

                    return $nom;
                }
            );
            $input->setArgument('nom', $nom);
        }
        
        if (!$input->getArgument('prenom')) {
            $prenom = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a prenom:',
                function($prenom) {
                    if (empty($prenom)) {
                        throw new \Exception('Prenom can not be empty');
                    }

                    return $prenom;
                }
            );
            $input->setArgument('prenom', $prenom);
        }
    }
}