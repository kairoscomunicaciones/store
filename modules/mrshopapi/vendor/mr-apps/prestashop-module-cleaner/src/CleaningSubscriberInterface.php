<?php

namespace MrAPPs\ModuleCleaner;

interface CleaningSubscriberInterface
{
    const FOLDER = 'folder';
    const FILE   = 'file';
    
    /**
     * Pre-cleaning node hook
     * @param string $path
     * @param string $type
     */
    public function cleaning($path, $type);
    
    /**
     * Post-clean node hook
     * @param string $path
     * @param string $type
     */
    public function cleaned($path, $type);
    
    /**
     * Not-cleaned node hook
     * @param string $path
     * @param string $type
     */
    public function notcleaned($path, $type);
    
    /**
     * Node not found post clean hook
     * @param string $path
     * @param string $type
     */
    public function nodeNotFound($path, $type);
    
    /**
     * Cleaning empty folder hook
     * @param string $path
     * @param string $type
     */
    public function cleaningEmptyFolder($path);
}
