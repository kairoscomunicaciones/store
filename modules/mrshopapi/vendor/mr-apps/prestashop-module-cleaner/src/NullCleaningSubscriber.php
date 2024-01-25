<?php

namespace MrAPPs\ModuleCleaner;

/**
 * Subscriber that does nothing
 */
class NullCleaningSubscriber implements CleaningSubscriberInterface
{
    public function cleaned($path, $type)
    {
        return;
    }

    public function cleaning($path, $type)
    {
        return;
    }

    public function notcleaned($path, $type)
    {
        return;
    }

    public function nodeNotFound($path, $type)
    {
        return;
    }
    
    public function cleaningEmptyFolder($path)
    {
        return;
    }
}
