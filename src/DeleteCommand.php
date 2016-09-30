<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use LotGD\Core\Models\Scene;

class DeleteCommand extends SceneEditorCommand {
    private function delete(OutputInterface $output, Scene $scene, array $deletedScenes): array
    {
        if (in_array($scene->getId(), $deletedScenes)) {
            return $deletedScenes;
        }

        if ($scene->hasChildren()) {
            foreach ($scene->getChildren() as $c) {
                $deletedScenes += $this->delete($output, $c, $deletedScenes);
            }
        }

        $id = $scene->getId();
        $output->writeln("<fg=yellow>Deleting scene id=$id</>");
        array_push($deletedScenes, $scene->getId());
        $scene->delete($this->g->getEntityManager());
        return $deletedScenes;
    }

    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        $count = count($argv);
        if ($count < 2) {
            $output->writeln("<error>delete: too few arguments</error>");
            return;
        }
        $scenes = [];
        for ($i = 1; $i < $count; $i++) {
            $id = $argv[$i];
            $scene = $this->g->getEntityManager()->getRepository(Scene::class)->find($id);
            if ($scene == null) {
                $output->writeln("<error>delete: cannot find scene with id=$id</error>");
                return;
            }
            array_push($scenes, $scene);
        }

        $helper = $this->command->getHelper('question');
        $sure = $helper->ask($input, $output, new ConfirmationQuestion("This will recursively remove all child scenes. Are you sure? [y/N] ", false));
        if ($sure) {
            $deletedScenes = [];
            foreach ($scenes as $s) {
                $deletedScenes += $this->delete($output, $s, $deletedScenes);
            }
            $count = count($deletedScenes);
            $output->writeln("<fg=green>Deleted $count scenes.</>");
        } else {
            $output->writeln("<fg=yellow>Aborted. No scenes deleted.</>");
        }
     }
}
