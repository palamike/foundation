<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/21/2016 AD
 * Time: 4:56 PM
 */

namespace Palamike\Foundation\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Palamike\Foundation\Methods\CommonControllerTrait;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, CommonControllerTrait;
}