<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\Table;

use LotGD\Core\Console\Command\BaseCommand;
use LotGD\Core\Game;
use LotGD\Core\Models\Scene;

class ScenesCommand extends BaseCommand {
    protected function configure()
    {
        $this->setName('scenes')
             ->setDescription('Start a shell to edit the scene hierarchy');
    }

    public function getVersion(): string
    {
        return "0.1.0";
    }

    public function getAboutLine(): string
    {
        return "Scene Editor 🎭 v" . $this->getVersion();
    }

    public function printScenes(OutputInterface $output, array $scenes, bool $includeDescription)
    {
        $table = new Table($output);

        $headers = ['ID', 'Title', 'Template', 'Parents', 'Children'];
        if ($includeDescription) {
            array_push($headers, 'Description');
        }

        $rows = [];
        foreach ($scenes as $s) {
            $row = [];

            array_push($row, $s->getId());
            array_push($row, $s->getTitle());
            array_push($row, $s->getTemplate());

            if ($s->hasParent()) {
                array_push($row, $s->getParent()->getId());
            } else {
                array_push($row, '');
            }

            if ($s->hasChildren()) {
                $children = '';
                $count = count($s->getChildren());
                foreach ($s->getChildren() as $index => $c) {
                    $children .= $c->getId() . (($index == $count - 1) ? "" : ",");
                }
                array_push($row, $children);
            } else {
                array_push($row, '');
            }

            if ($includeDescription) {
                array_push($row, $s->getDescription());
            }
            array_push($rows, $row);
        }

        $table
            ->setHeaders($headers)
            ->setRows($rows);

        $table->render();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getAboutLine());
        $output->writeln("Try 'help'");
        $output->writeln("");

        $helper = $this->getHelper('question');

        $prompt = "🎭 > ";
        while (true) {
            $line = $helper->ask($input, $output, new Question($prompt));
            $line = trim($line);

            if (strlen($line) == 0) {
                continue;
            }

            $parts = explode(' ', $line);
            $command = $parts[0];
            switch ($command) {
                case 'new':
                    (new NewCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'show':
                    (new ShowCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'edit':
                    (new EditCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'move':
                    (new MoveCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'delete':
                    (new DeleteCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'help':
                    (new HelpCommand($this, $this->game))->execute($input, $output, $parts);
                    break;
                case 'quit':
                case 'exit':
                    goto quit;
                    break;
                default:
                    $output->writeln("<error>$command: unknown command, try 'help'</error>");
                    break;
            }
        }
quit:
    }
}
