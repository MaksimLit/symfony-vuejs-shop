<?php declare(strict_types = 1);

namespace App\Service\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FilesystemWorker
 */
class FilesystemWorker
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FilesystemWorker constructor.
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $folder
     */
    public function createFolderIfItNotExist(string $folder)
    {
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
    }

    /**
     * @param string $item
     */
    public function remove(string $item)
    {
        if ($this->filesystem->exists($item)) {
            $this->filesystem->remove($item);
        }
    }
}
