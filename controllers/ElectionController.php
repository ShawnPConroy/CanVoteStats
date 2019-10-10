<?php
namespace CanadianVoteStatisticsApp;

// ElectionController class
//
// This class gathers data and shows page view for any election/ request.

class ElectionController
{
    private $app;
    private $gov;
    private $request;
    
    public function __construct(&$app, $gov, $request)
    {
        $this->app = $app;
        $this->gov = $gov;
        $this->request = $request;
    }
    
    // Main controller looks at args and gets the right data/view
    // 0 args: show list of election
    // 1 args: specified election, show basic election statistics
    // 2 args: election and party specified, show party statistics
    // else: 404
    public function run()
    {
        $args = is_array($this->request) ? sizeof($this->request) : 0;
        
        if ($args == 1) {
            $nationalResult = Election::ElectionResults($this->app, $this->request[0]);
            ElectionView::ElectionResults($this->app, $this->request[0], $nationalResult);
            $parties = Election::Parties($this->app, $this->request[0]);
            ElectionView::Parties($this->app, $this->request[0], $parties);
            $partyRanges = Election::NationalVoteRange($this->app, $this->request[0], $parties);
            ElectionView::NationalVoteRange($this->app, 'Party Voter Percent Ranges', $partyRanges);
            
        } elseif ($args == 2) {
            $nationalResult = Election::PartyVotesByProvince($this->app, $this->request[0], $this->request[1]);
            ElectionView::PartyVotesByProvince($this->app, $this->request[0].$this->request[1], $nationalResult);
            $rangeData = Election::PartyVoteRange($this->app, $this->request[0], $this->request[1]);
            ElectionView::PartyVoteRange($this->app, $this->request[0].$this->request[1], $rangeData);
            
        } elseif ($args == 0) {
            $electionList = Election::Elections($this->app);
            ElectionView::Elections($this->app, $electionList);
            
        } else {
            //404 $this->app->show404
        }
    }
}
