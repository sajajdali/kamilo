<?php
namespace App\Base;

use App\Http\Controllers\Controller;
/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller {

	CONST ADMIN_BASE_PERMMISION = "ADMIN";
	public $paginate_count = 50;
}