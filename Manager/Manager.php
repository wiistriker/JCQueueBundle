<?php

namespace JC\QueueBundle\Manager;

class Manager
{
    protected $queues;

    public function setQueues(Array $queues)
    {
        $this->queues = $queues;
    }

    public function getQueues()
    {
        return $this->queues;
    }

    public function getQueue($queue_name)
    {
        $options = array(
            'name'          => $queue_name,
            'driverOptions' => array(
                'host'      => '127.0.0.1',
                'username'  => 'root',
                'password'  => '',
                'dbname'    => 'devoted',
                'type'      => 'pdo_mysql'
            )
        );

        // Create a database queue.
        // Zend_Queue will prepend Zend_Queue_Adapter_ to 'Db' for the class name.
        $queue = new \Zend_Queue('Db', $options);

        return $queue;
    }
}