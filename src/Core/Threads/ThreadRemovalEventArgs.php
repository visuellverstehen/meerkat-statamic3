<?php

namespace Stillat\Meerkat\Core\Threads;

use Stillat\Meerkat\Core\Contracts\DataObjectContract;
use Stillat\Meerkat\Core\DataObject;

/**
 * Represents a Thread during a thread mutation request
 *
 * @since 2.0.0
 */
class ThreadRemovalEventArgs implements DataObjectContract
{
    use DataObject;

    /**
     * Indicates whether or not the thread should be permanently removed or not.
     *
     * @var boolean
     */
    protected $doSoftDelete = false;

    /**
     * Sets an internal flag indicating that the thread should be hidden,
     * but not completely removed from the underlying storage system.
     *
     * @return void
     */
    public function keep()
    {
        $this->doSoftDelete = true;
    }

    /**
     * Sets an internal flag indicating that the thread should be completely removed.
     *
     * @return void
     */
    public function deletePermanently()
    {
        $this->doSoftDelete = false;
    }

    /**
     * Returns a value indicating if the thread should be permanently removed or not.
     *
     * @return boolean
     */
    public function shouldKeep()
    {
        return $this->doSoftDelete;
    }

}