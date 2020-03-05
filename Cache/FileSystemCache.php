<?php

namespace Scraper\Scraper\Cache;

class FileSystemCache extends Cache
{
    private static $BASE_FOLDER_CACHE = 'scraper';

    /**
     * @var string
     */
    protected $cacheFolder;

    /**
     * @return bool
     */
    public function exist()
    {
        return is_file($this->getFilePath());
    }

    /**
     * @return bool|mixed|string
     */
    public function get()
    {
        return file_get_contents($this->getFilePath());
    }

    /**
     * @return string
     */
    public function getCacheFolder(): ?string
    {
        return $this->cacheFolder;
    }

    /**
     * @param string $cacheFolder
     *
     * @return $this
     */
    public function setCacheFolder(?string $cacheFolder)
    {
        if ('/' !== substr($cacheFolder, -1)) {
            $cacheFolder .= '/';
        }
        $this->cacheFolder = $cacheFolder;
        $this->initCache();
        return $this;
    }

    /**
     * @param $data
     *
     * @return bool|void
     */
    public function write($data)
    {
        $this->createFolderIfNotExist($this->folderPath());
        $this->createFile($this->getFilePath(), $data);
    }

    /**
     * @param $name
     * @param $data
     */
    private function createFile($name, $data)
    {
        $r = file_put_contents($name, $data);
    }

    /**
     * @param $path
     */
    private function createFolderIfNotExist($path)
    {
        if (!is_dir($path)) {
            $r = mkdir($path, 0777, true);
        }
    }

    /**
     * @return string
     */
    private function fileName()
    {
        $data = serialize($this->request);
        return sha1($data);
    }

    /**
     * @return string
     */
    private function folderPath()
    {
        $scraper = strtolower(str_replace(["Scraper\Scraper", "\Request"], '', $this->reflectionClass->getNamespaceName()));
        $class   = strtolower($this->reflectionClass->getShortName());
        $folder  = $this->getBasePath() . $scraper . '/' . $class;
        return $folder . '/';
    }

    /**
     * @return string
     */
    private function getBasePath()
    {
        return $this->cacheFolder . self::$BASE_FOLDER_CACHE . '/';
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        return $this->folderPath() . $this->fileName();
    }

    private function initCache()
    {
        $this->createFolderIfNotExist($this->getBasePath());
    }
}
