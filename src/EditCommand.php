<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

use LotGD\Core\Models\Scene;

class EditCommand extends SceneEditorCommand {
    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        $helper = $this->command->getHelper('question');

        if (count($argv) < 2) {
            $output->writeln("<error>edit: too few arguments</error>");
            return;
        } else if (count($argv) > 2) {
            $output->writeln("<error>edit: too many arguments</error>");
            return;
        }

        $id = $argv[1];
        $scene = $this->g->getEntityManager()->getRepository(Scene::class)->find($id);

        if ($scene == null) {
            $output->writeln("<error>edit: cannot find a scene with id=$id</error>");
            return;
        }

        $this->command->printScenes([$scene], true);
        $output->writeln("");

        $whichQuestion = new ChoiceQuestion(
            "Select field(s) to edit: ",
            ['title', 'template', 'description']
        );
        $whichQuestion->setMultiselect(true);
        $which = $helper->ask($input, $output, $whichQuestion);

        foreach($which as $w) {
            switch($w) {
                case 'title':
                    $title = $helper->ask($input, $output, new Question("Title: "));
                    $scene->setTitle($title);
                    break;
                case 'template':
                    $template = $helper->ask($input, $output, new Question("Template: "));
                    $scene->setTemplate($template);
                    break;
                case 'description':
                    $description = $helper->ask($input, $output, new Question("Description: "));
                    $scene->setDescription($description);
                    break;
                default:
                    break;
            }
        }

        $scene->save($this->g->getEntityManager());
        $output->writeln("<fg=green>Edited scene id=$id:</>");
        $this->command->printScenes([$scene], true);
        $output->write("");
    }
}
