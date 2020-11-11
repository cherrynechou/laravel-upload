<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/9 0009
 * Time: 11:17
 */
namespace CherryneChou\LaravelUpload\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class BaseController
 * @package Dcat\Admin\Extension\Upload\Http\Controllers
 */
class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param bool $status
     * @param array $data
     * @param int $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxJson($status = true, $data = [], $code = 200, $message = '' ){
        return response()->json(
            ['status' => $status, 'code' => $code, 'message' => $message, 'data' => $data]
        );
    }
}