<?php

namespace artworx\omegacp\Widgets;

use Arrilot\Widgets\AbstractWidget;
use artworx\omegacp\Facades\Omega;

class PostDimmer extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Omega::model('Post')->count();
        $string = $count == 1 ? 'post' : 'posts';

        return view('omega::dimmer', array_merge($this->config, [
            'icon'   => 'omega-group',
            'title'  => "{$count} {$string}",
            'text'   => "You have {$count} {$string} in your database. Click on button below to view all posts.",
            'button' => [
                'text' => 'View all posts',
                'link' => route('omega.posts.index'),
            ],
            'image' => url(config('omega.assets_path').'/images/widget-backgrounds/03.png'),
        ]));
    }
}
