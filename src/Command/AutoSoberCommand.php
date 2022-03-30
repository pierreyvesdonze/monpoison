<?php

namespace App\Command;

use App\Service\MailService;
use App\Service\SoberService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Security;

class AutoSoberCommand extends Command
{
    private $soberService;
    private $security;
    private $mailer;

    public function __construct(
        SoberService $soberService,
        Security $security,
        MailService $mailer
        )
    {
        $this->soberService = $soberService;
        $this->security = $security;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {

        $user = $this->security->getUser();
        $this->mailer->sendCommentMail($user, 'test command');
        $this->soberService->addAutoSoberDay($user);
                
        return Command::SUCCESS;
    }
}
