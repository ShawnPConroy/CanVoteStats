<?php
namespace CanadianVoteStatisticsApp;

// The main app object that contains settings and automates some functions.
// Use for dependency injection of all relevante variables.

class CanadianVoteStatisticsApp
{
    private $siteName;
    
    private $siteUri;
    private $templateFolder;
    private $templateName;
    private $templateUri;
    private $config;
    private $pageContent;
    private $pageTitle;
    private $db;
    private $dbPrefix;
    
    // Load some defaults
    public function __construct()
    {
        $this->templateName = 'boilerplate';
        $this->templateFolder = 'templates/';
        $this->setTemplate('boilerplate');
        
        $this->pageContent = "";
        $this->pageScript = "";
    }
    
    // Router function to call the proper controller based on request.
    public function router()
    {
        $request = array_filter(explode('/', $_SERVER['REQUEST_URI']));
        
        // skip folders from root and domain
        // In the future this should be automated
        for ($i = 0; $i < $this->siteUriSkip; $i++) {
            array_shift($request);
        }
        $controller = array_shift($request);
        
        switch ($controller) {
            case 'federal':
                $election = new ElectionController($this, $controller, $request);
                $election->run();
                $this->showPage();
                break;
            
            // case 'ontario': case: 'on':
            
            case 'home': case '':
            default: // this should be 404
                $home = new HomeController($this);
                $home->run();
                $this->showPage();
        }
    }
    
    public function addContent($content)
    {
        $this->pageContent .= $content;
    }
    
    public function addScript($script)
    {
        $this->pageScript .= $script;
    }
    
    public function dbQuery($sql)
    {
        return $this->db->query($sql);
    }
    
    public function makeLink($request, $text)
    {
        return '<a href="'.$this->siteUri.$request.'">'.$text.'</a>';
    }
    
    private function showPage()
    {
        include($this->templateFolder.$this->templateName.'/index.php');
    }
    
    public function getPageTitle()
    {
        return $this->pageTitle;
    }
    
    public function getSiteName()
    {
        return $this->siteName;
    }
    
    /** Set user config **/
    public function setDB($host, $user, $pass, $dbName, $prefix)
    {
        // Connect to database
        $this->dbPrefix = $prefix;
        $this->db = new \mysqli($host, $user, $pass, $dbName);
    }
    
    public function setPageTitle($title)
    {
        $this->pageTitle = $title.' - '.$this->siteName;
    }
    
    public function setSiteName($name)
    {
        $this->siteName = $name;
        $this->setPageTitle($name);
    }
    
    public function setTemplate($templateName)
    {
        $this->templateName = $templateName;
        $this->templateUri = $this->siteUri.$this->templateFolder.$this->templateName;
    }
    
    public function setUri($uri, $skip = 0)
    {
        $this->siteUri = $uri;
        $this->siteUriSkip = $skip;
    }
}
