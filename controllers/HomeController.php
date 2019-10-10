<?php
namespace CanadianVoteStatisticsApp;

// Home page controller to display links to different parts of the app.

class HomeController
{
    private $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function run()
    {
        // Link to the federal election results section.
        $this->app->addContent('
            <h1>'.$this->app->getSiteName().'</h1>
            <p>Head over to the '.$this->app->makeLink('federal', 'federal election').' page.</p>
            ');
    }
}
