<?php

namespace Sereno\Processors;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Symfony\Component\Finder\SplFileInfo;

class BladeProcessor extends AbstractProcessor
{
    protected $allowedExtensions = ['.blade.php'];

    protected $viewFactory;

    public function __construct(Filesystem $filesystem, Factory $viewFactory)
    {
        parent::__construct($filesystem);
        $this->viewFactory = $viewFactory;
    }

    public function process(SplFileInfo $file, array $data, array $options = [])
    {
        $filename = $this->getOutputFilename($file, array_get($options, 'interceptor'));
        app()->line('Blade: '.$file->getRelativePathname().' -> '.$filename);

        $data['currentViewPath'] = $file->getRelativePathname();
        $data['currentUrlPath'] = $this->getUrl($filename);

        $content = $this->viewFactory->file($file->getPathname(), $data)->render();

        $this->writeContent($filename, $content);

        return $this;
    }
}
