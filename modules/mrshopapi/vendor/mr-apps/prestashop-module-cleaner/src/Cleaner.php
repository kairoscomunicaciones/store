<?php

namespace MrAPPs\ModuleCleaner;

class Cleaner
{
    protected $path;
    protected $dryrun;
    /** @var \MrAPPs\ModuleCleaner\CleaningSubscriberInterface */
    protected $subscriber;
    
    /**
     * @param string $path absolute rott path to clean
     */
    public function __construct($path, $dryrun)
    {
        $this->path       = realpath($path);
        $this->dryrun     = $dryrun;
        $this->subscriber = new NullCleaningSubscriber();
    }
    
    public function setSubscriber($subscriber)
    {
        if (($subscriber instanceof CleaningSubscriberInterface) == false) {
            throw new \UnexpectedValueException("Subscriber must be an instance of ".CleaningSubscriberInterface::class);
        }
        $this->subscriber = $subscriber;
        return $this;
    }
    
    /**
     * @param array<string, array|bool> $nodeTree a node tree array where key is
     * the node name (folder or filename) and the value is an array listing
     * content for folder or a boolean files
     */
    public function clean($nodeTree)
    {
        if (false == is_array($nodeTree)) {
            throw new \RuntimeException("The node tree has to be an array");
        }
        
        $list = $this->getPathContents($this->path);
        
        foreach ($list as $node) {
            $p        = str_replace($this->path.DIRECTORY_SEPARATOR, '', $node);
            $parts    = explode(DIRECTORY_SEPARATOR, $p);
            $toDelete = false == $this->isInTree($parts, $nodeTree);
            
            if ($toDelete) {
                $this->removeNode($node);
            }
        }
        
        // delete folders recursively since each path is full to the last leaf of the tree node
        do {
            $empties = $this->listEmptyPaths();
            foreach ($empties as $empty) {
                $this->removeNode($empty);
            }
        } while(count($empties) > 0);
    }
    
    /**
     * List files inside the specified path
     * @param string $path
     * @return array
     */
    protected function getPathContents($path)
    {
        $results = array();
        $nodes = $this->listDir($path);
        foreach ($nodes as $node) {
            if (is_dir($node)) {
                $content = $this->getPathContents($node);
                if(false == empty($content)) {
                    $results = array_merge($results, $content);
                }
            } else {
                $results[] = $node;
            }
        }

        return $results;
    }
    
    /**
     * Tells if a path is empty
     * @param string $path
     * @return boolean
     */
    public function isPathEmpty($path)
    {
        if (false == is_dir($path)) {
            return false;
        }
        
        $notemptyfolders = 0;
        
        $contents = $this->getPathContents($path);
        
        foreach ($contents as $content) {
            if (false == $this->isPathEmpty($content)) {
                $notemptyfolders++;
            }
        }
        
        return $notemptyfolders == 0;
    }
    
    
    /**
     * Lists empty paths inside the cleaner scope
     * @return type
     */
    protected function listEmptyPaths()
    {
        $list = $this->listDir($this->path);
        
        return array_filter($list, function ($item) {
            return is_dir($item);
        });
        
    }
    
    public function listDir($p)
    {
        if (false == is_dir($p)) {
            throw new \UnexpectedValueException('path as to be a dir');
        }
        
        $contents = scandir($p);
        
        return array_reduce($contents, function($carry, $item) use ($p) {
            $fullPath = realpath($p . DIRECTORY_SEPARATOR . $item);
            if (in_array($item, array('.', '..'))) {
                return $carry;
            } else if (false == is_dir($fullPath)) {
                $carry[] = $fullPath;
                return $carry;
            } else {
                $list = $this->listDir($fullPath);
                if (empty($list)) {
                    $carry[] = $fullPath;
                } else {
                    $carry = array_merge($carry, $list);
                }
                return $carry;
            }
        }, array());
        
    }
    
    
    /**
     * Checks if the node path, described as an array (es: "my/little/dir" => ['my', 'little', 'dir'])
     * exists inside the node tree
     * @param array $pathParts
     * @param array $nodeTree
     * @return boolean
     */
    protected function isInTree($pathParts, $nodeTree)
    {
        /*
         * Delete the first element from $pathParts and getting it
         */
        list($part)    = array_splice($pathParts, 0, 1);
        
        $present = false == empty($nodeTree[$part]);
        
        if (false == $present) {
            return false;
        } else if (count($pathParts) < 1) {
            return $present;
        } else {
            return $this->isInTree($pathParts, $nodeTree[$part]);
        }
    }
    
    protected function removeNode($path)
    {
        $type = is_dir($path)
                ? CleaningSubscriberInterface::FOLDER
                : CleaningSubscriberInterface::FILE;
        
        if (is_dir($path)) {
            $list = $this->listDir($path);
            if (empty($list)) {
               $this->subscriber->cleaningEmptyFolder($path); 
            } else {
                $this->subscriber->cleaning($path, $type);
            }
        } else {
            $this->subscriber->cleaning($path, $type);
        }
        
        $result = false;
        if ($this->dryrun) {
            echo "cleaning $type $path";
            $result = true;
        } else if (file_exists($path)){
            $result = is_dir($path)
                    ? rmdir($path)
                    : unlink($path);
        } else {
            $this->subscriber->nodeNotFound($path, $type);
            return;
        }
        
        if ($result) {
            $this->subscriber->cleaned($path, $type);
        } else {
            $this->subscriber->notcleaned($path, $type);
        }
    }
    
    
    
    /**
     * Static creation method for fluency
     * @param type $path
     * @param type $dryRun
     * @return \static
     */
    public static function create($path, $dryRun)
    {
        return new static($path, $dryRun);
    }
}
