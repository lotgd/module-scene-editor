<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use LotGD\Core\Models\Scene;

class ShowCommand extends SceneEditorCommand {
    private function showAllScenes($output)
    {
        $scenes = $this->g->getEntityManager()->getRepository(Scene::class)->findAll();
        $this->command->printScenes($output, $scenes, false);
        return;
    }

    private function showSceneWithId($output, $id) {
        $scene = $this->g->getEntityManager()->getRepository(Scene::class)->find($id);

        if ($scene) {
            $this->command->printScenes($output, [$scene], true);
        } else {
            $output->writeln("<error>show: no scene with id=$id</error>");
        }
    }

    private function showScenesWithTemplate($output, $template)
    {
        $scenes = $this->g->getEntityManager()->getRepository(Scene::class)->findBy([
            'template' => $template
        ]);

        if (count($scenes) > 0) {
            $output->writeln("<fg=green>Scenes with template=$template:</>");
            $this->command->printScenes($output, $scenes, true);
        } else {
            $output->writeln("<error>show: no scenes with template=$template</error>");
        }
    }

    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        if (count($argv) == 1) {
            $this->showAllScenes($output);
        } else if (count($argv) == 2) {
            if (is_numeric($argv[1]) && is_int(0 + $argv[1])) {
                $this->showSceneWithId($output, $argv[1]);
            } else {
                $this->showScenesWithTemplate($output, $argv[1]);
            }
        } else {
            $output->writeln("<error>show: too many arguments</error>");
        }
    }
}
