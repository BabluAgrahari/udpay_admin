<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function about()
    {
        return view('Website.about');
    }

    public function contact()
    {
        return view('Website.contact_us');
    }

    public function privacyPolicy()
    {
        return view('Website.privacy_policy');
    }

    public function termsConditions()
    {
        return view('Website.term_condition');
    }

    public function returnPolicy()
    {
        return view('Website.return_policy');
    }

    public function shippingPolicy()
    {
        return view('Website.shipping_policy');
    }

    public function legalDocs()
    {
        return view('Website.leagal_docs');
    }

    public function faq()
    {
        return view('Website.faq');
    }

    public function grievanceCell()
    {
        return view('Website.grievance_cell');
    }

    public function trackOrder()
    {
        return view('Website.track_order');
    }

    public function complianceDocuments()
    {
        return view('Website.compliance_documents');
    }
} 