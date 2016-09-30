<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

use LotGD\Core\Models\Scene;

class NewCommand extends SceneEditorCommand {
    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        if (count($argv) != 1) {
            $output->writeln("<error>new: too many arguments</error>");
            return;
        }

        $helper = $this->command->getHelper('question');

        $title = $helper->ask($input, $output, new Question("Title: "));
        $template = $helper->ask($input, $output, new Question("Template: "));
        $description = $helper->ask($input, $output, new Question("Description: "));

        $output->writeln("");

        $scene = Scene::create([
            'title' => $title ?? '',
            'template' => $template ?? '',
            'description' => $description ?? '',
        ]);

        $scene->save($this->g->getEntityManager());
        $id = $scene->getId();
        $output->writeln("<fg=green>Newly created scene id=$id:</>");
        $this->command->printScenes($output, [$scene], true);
        $output->write("");
    }
}
