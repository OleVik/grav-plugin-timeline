<?php
namespace Grav\Plugin\Console;

use Grav\Common\Grav;
use Grav\Common\GravTrait;
use Grav\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Timeline\API\SchemaBlueprint;
use Grav\Framework\Cache\Adapter\FileStorage;

/**
 * Class DumpDataCommand
 * 
 * @package Grav\Plugin\Timeline
 * @author  Ole Vik <git@olevik.net>
 * @license MIT
 * @since   v1.1.0
 */
class DumpDataCommand extends ConsoleCommand
{
    /**
     * Command definitions
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("dump")
            ->setDescription("Generates and stores data-tree.")
            ->setHelp('The <info>dump</info>-command generates and stores the data-tree.');
    }

    /**
     * Generates schema definitions for event types
     *
     * @return void
     */
    protected function serve()
    {
        $this->output->writeln('<info>Generating schemas for blueprint</info>');
        try {
            $config = Grav::instance()['config']->get('plugins.timeline');
            $res = Grav::instance()['locator'];
            $file = 'Event.schema.json';
            $target = array(
                'persist' => $res->findResource('user://') . '/data/timeline',
                'transient' => $res->findResource('cache://') . '/timeline',
                'native' => $res->findResource('user://') . '/plugins/timeline/data'
            );
            $location = $target[$config['cache']];
            $Storage = new FileStorage($location);
            if ($Storage->doHas($file)) {
                $Storage->doDelete($file);
            }
            $schemas = new SchemaBlueprint('/^Bordeux\\\\SchemaOrg\\\\Thing\\\\Event/mi');
            $schemas->remove('UserInteraction');
            $data = json_encode($schemas->data);
            $Storage->doSet($file, $data, 0);
            $this->output->writeln('<info>Saved to ' . $location . '/' . $file . '.</info>');
        } catch(\Exception $e) {
            throw new \Exception($e);
        }
    }
}