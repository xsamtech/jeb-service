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
        $total_orders = 1245999.73;
        $orders_paid = 57709.95;
        $tithe = $orders_paid / 10;
        $tva = ($orders_paid * 16) / 100;
        $rest_of_money = $orders_paid - ($tithe + $tva);

        return view('dashboard', [
            'total_orders' => formatIntegerNumber($total_orders),
            'orders_paid' => formatIntegerNumber($orders_paid),
            'tithe' => formatIntegerNumber($tithe),
            'tva' => formatIntegerNumber($tva),
            'rest_of_money' => formatIntegerNumber($rest_of_money),
        ]);
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

    /**
     * GET: Messages page
     *
     * @return \Illuminate\View\View
     */
    public function messages()
    {
        return view('messages');
    }

    /**
     * GET: account page
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('account');
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
