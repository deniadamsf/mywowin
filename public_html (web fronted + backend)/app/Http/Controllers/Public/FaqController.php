<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('public.faq.index');
    }
    public function howtobuy()
    {
        return view('public.howtobuy.index');
    }
    public function shipping()
    {
        return view('public.shipping.index');
    }
    public function freong()
    {
        return view('public.freong.index');
    }
    public function pickup()
    {
        return view('public.pickup.index');
    }
    public function transaction()
    {
        return view('public.transaction.index');
    }
    public function refund()
    {
        return view('public.refund.index');
    }
    public function member()
    {
        return view('public.faq.member');
    }
    public function syarat()
    {
        return view('public.faq.syarat');
    }
    public function policy()
    {
        return view('public.faq.policy');
    }
    public function about()
    {
        return view('public.faq.about');
    }
}
