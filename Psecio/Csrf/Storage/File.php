<?php
namespace Psecio\Csrf\Storage;

class File extends \Psecio\Csrf\Storage
{
    protected $namespace = 'csrf-file';

    public function save($key, $code)
    {
        $filename = $this->getConfig('filename');
        $contents = $this->getFileContents($filename);

        $contents['tokens'][$key] = $code;
        $this->writeFileContents($filename, $contents);
    }

    public function get($key)
    {
        $contents = $this->getFileContents($this->getConfig('filename'));
        return (isset($contents['tokens'][$key])) ? $contents['tokens'][$key] : null;
    }
    public function delete($key)
    {
        $filename = $this->getConfig('filename');
        $contents = $this->getFileContents($filename);

        unset($contents['tokens'][$key]);
        $this->writeFileContents($filename, $content);
    }

    private function getFileContents($filename)
    {
        if (file_exists($filename) && !is_writable($filename)) {
            throw new \Exception('Cannot write token to file: '.$filename);
        }
        if (file_exists($filename)) {
            $contents = json_decode(file_get_contents($filename), true);
        } else {
            $contents = ['tokens' => []];
        }

        return $contents;
    }
    private function writeFileContents($filename, $contents)
    {
        return file_put_contents($filename, json_encode($contents));
    }
}
