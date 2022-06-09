<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Harian Jogja',
        ];
        return view('home', $data);
    }

    public function details()
    {
        $data = [
            'title' => 'Detail | Harian Jogja',
        ];
        return view('news_details', $data);
    }
}
