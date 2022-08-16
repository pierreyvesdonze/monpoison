<?php

namespace App\Command;

use App\Service\MailService;
use App\Service\SoberService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Security;

#[AsCommand(
    name: 'AutoSoberCommand',
    description: 'Create auto sober',
    hidden: false,
    aliases: ['autosober']
)]

class AutoSoberCommand extends Command
{
    protected static $defaultName = 'app:autosober';
    private $soberService;
    private $security;
    private $mailer;
    private $logger;

    public function __construct(
        SoberService $soberService,
        Security $security,
        MailService $mailer,
        LoggerInterface $logger,
    ) {
        $this->soberService = $soberService;
        $this->security = $security;
        $this->mailer = $mailer;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $user = $this->security->getUser();
        //$this->mailer->sendCommentMail($user, 'test command');
        // $this->logger->info($user);
        $this->soberService->addAutoSoberDay($user);

        return Command::SUCCESS;
    }
}
