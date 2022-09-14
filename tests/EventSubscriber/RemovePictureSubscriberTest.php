<?php
namespace App\Tests\EventSubscriber;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RemovePictureSubscriberTest extends KernelTestCase
{
    public function testSubscribeToRightEvent()
    {

    }

    public function testCacheFileIsRemoved()
    {
        //load fixtures pro
        //remove pro
        
        // self::getContainer()->get(CacheManager::class)->getBrowerPath()
        //ou
        $this->assertFileDoesNotExist()
    }


    
}