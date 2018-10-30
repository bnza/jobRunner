<?php
/**
 *
 * (c) 2018 - Pietro Baldassarri <pietro.baldassarri@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Bnza\JobRunnerBundle\Job\Entity;

interface JobEntityInterface extends JobRunnerEntityInterface
{
    public function getId(): string;

    public function getStatus(): int;

    public function setStatus($status);

    public function getCurrentTaskNum(): int;

    public function setCurrentTaskNum($num);

    public function addTask(TaskEntityInterface $task);

    public function getTasks(): \ArrayIterator;

    public function getTask(int $num): TaskEntityInterface;

    public function clearTasks();

}