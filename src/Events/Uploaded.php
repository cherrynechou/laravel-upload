<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/8 0008
 * Time: 11:06
 */

namespace CherryneChou\LaravelUpload\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Uploading.
 *
 * @author overtrue <i@overtrue.me>
 */
class Uploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $resultData;

    /**
     * Uploaded constructor.
     *
     * @param array    $resultData
     */
    public function __construct(array $resultData)
    {
        $this->resultData = $resultData;
    }
}
