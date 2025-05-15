<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class DashboardController extends Controller
{
    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * GET: Customers list page
     *
     * @return \Illuminate\View\View
     */
    public function customers()
    {
        return view('customers');
    }

    /**
     * GET: Show customer page
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function customerDatas($id)
    {
        return view('customers');
    }

    /**
     * GET: Statistics page
     *
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        return view('statistics');
    }

    // ==================================== HTTP DELETE METHODS ====================================
    /**
     * GET: Delete customer
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function removeCustomer($id)
    {
        // 
    }
}
