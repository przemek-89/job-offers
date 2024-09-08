<?php

namespace App\View\Components;

use Illuminate\View\Component;
use WP_Post;

class JobCard extends Component
{
    public $post;

    public function __construct(WP_Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('components.job-card');
    }
}