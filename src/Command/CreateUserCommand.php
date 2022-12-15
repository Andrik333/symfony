<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Extensions\Moex\ParseCsv;
use App\Repository\Moex\FullOrderRepository;

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'moex:loaddata';
    private $forep;

    public function __construct(FullOrderRepository $forep)
    {
        $this->forep = $forep;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');
        if (file_exists($filePath)) {
            $output->writeln([
                '',
                'Выполнение...',
                '',
            ]);
            try {
                $data = (new ParseCsv($filePath))->getData();
            } catch (\Throwable $th) {
                $output->writeln('Ошибка при разборе файла!');
            }

            try {
                $this->forep->insertData($data);
                $output->writeln('Готово!');
                return Command::SUCCESS;
            } catch (\Throwable $th) {
                $output->writeln('Ошибка при сохранении данных!');
            }
        } else {
            $output->writeln([
                '',
                'Файл не найден',
                '',
            ]);
            return Command::INVALID;
        }
    }

    protected function configure(): void
    {
        $this->setHelp('Команда для загрузки данных из .csv файла');
        $this->addArgument('filePath', InputArgument::REQUIRED, 'path to file');
    }
}