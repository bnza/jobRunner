<?php
/**
 *
 * (c) 2018 - Pietro Baldassarri <pietro.baldassarri@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Bnza\JobRunnerBundle\Job\Entity\TmpFS;

use Bnza\JobRunnerBundle\Job\Entity\JobEntityInterface;
use Bnza\JobRunnerBundle\Job\Entity\TaskEntityInterface;

class JobEntity extends AbstractJobRunnerEntity implements JobEntityInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $status = 0;

    /**
     * @var int
     */
    protected $currentTaskNum = 0;

    /**
     * @var \ArrayIterator
     */
    protected $tasks;

    /**
     * JobEntity constructor.
     * @param string $id The job id (must be a SHA1 hash)
     */
    public function __construct(string $id = '')
    {
        if ($id) {
            if (ctype_xdigit($id) && strlen($id) == 40) {
                $this->id = $id;
            } else {
                throw new \InvalidArgumentException(sprintf("\"%s\" is not a valid sha1 hash", $id));
            }
        } else {
            $this->id = sha1(microtime());
        }
        $this->tasks = new \ArrayIterator();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = (int) $status;
    }

    public function getCurrentTaskNum(): int
    {
        return $this->currentTaskNum;
    }

    public function setCurrentTaskNum($num)
    {
        $this->currentTaskNum = (int) $num;
    }

    public function addTask(TaskEntityInterface $task)
    {
        if ($this->tasks->offsetExists($task->getNum())) {
            throw new \LogicException("Cannot replace existing task");
        }
        $this->tasks->offsetSet($task->getNum(), $task);
        $task->setJob($this);
    }

    public function getTasks(): \ArrayIterator
    {
        return $this->tasks;
    }

    public function getTask(int $num): TaskEntityInterface
    {
        if ($this->tasks->offsetExists($num)) {
            return $this->tasks->offsetGet($num);
        }
        throw new \RuntimeException("No tasks at index $num");
    }

    public function clearTasks()
    {
        $this->tasks = new \ArrayIterator();
    }
}