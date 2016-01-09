<?php

namespace React\Tests\Filesystem\ChildProcess;

use React\Filesystem\ChildProcess\Process;
use React\Tests\Filesystem\TestCase;
use WyriHaximus\React\ChildProcess\Messenger\Messages\Payload;

class ProcessTest extends TestCase
{
    public function testConstruct()
    {
        $messenger = $this->getMockBuilder('WyriHaximus\React\ChildProcess\Messenger\Messenger')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messenger
            ->expects($this->atLeastOnce())
            ->method('registerRpc')
        ;

        new Process($messenger);
    }

    public function testStat()
    {

        $messenger = $this->getMockBuilder('WyriHaximus\React\ChildProcess\Messenger\Messenger')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $resultCallbackRan = false;
        (new Process($messenger))->stat(new Payload([
            'path' => __FILE__,
        ]), $messenger)->then(function ($result) use (&$resultCallbackRan) {
            foreach ([
                'dev',
                'ino',
                'mode',
                'nlink',
                'uid',
                'size',
                'gid',
                'rdev',
                'blksize',
                'blocks',
                'atime',
                'mtime',
                'ctime',
            ] as $item) {
                $this->assertTrue(isset($result[$item]));
            }
            $resultCallbackRan = true;
        });
        $this->assertTrue($resultCallbackRan);
    }
}