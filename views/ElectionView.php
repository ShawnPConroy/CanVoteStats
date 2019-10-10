<?php
namespace CanadianVoteStatisticsApp;

class ElectionView
{
    // Adds list of parties to the page
    public static function Parties($app, $election, $data)
    {
        $app->addContent('<p>Here\'s a list of parties in this election. Click for info about them.</p>');
        $list = "";
        foreach ($data as $party) {
            $list .= '<li>'.$app->makeLink('federal/'.$election.'/'.$party, $party).'</li>';
        }
        $app->addContent("<ul style=\"columns: 3\">$list</ul>");
    }
    
    // Adds standard election bar chart to the page
    public static function ElectionResults($app, $title, $data)
    {
        $app->addScript('
            var chartElectionResults = new CanvasJS.Chart("chartContainer-ElectionResults",
            {
              title:{
                text: "'.$title.'"
              },
              axisY:{
                  logarithmic: true,
                  title: "Votes",
                  labelAutoFit: true,
                  labelFontSize: 15
              },
              axisX:{
                  title: "Parties",
                  labelAutoFit: true,
                  labelWrap: false,
                  labelFontSize: 15
              },
              data: [
              {
                type: "bar",
                dataPoints: '.json_encode($data, JSON_NUMERIC_CHECK).'
              }
              ]
            });
            
            chartElectionResults.render();
            ');
        $app->addContent('
            <div id="chartContainer-ElectionResults" style="height: 2400px; margin: 0px auto;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            ');
    }

    // Adds a list of elections to the page
    public static function Elections($app, $data)
    {
        $app->setPageTitle("Elections");
        $app->addContent('<p>List of elections in my database:</p>');
        $list = "";
        foreach ($data as $election) {
            $list .= '<li>'.$app->makeLink('federal/'.$election, $election).'</li>';
        }
        $app->addContent("<ul>$list</ul>");
    }
    
    // Adds national vote range box 'n whisker chart to the page
    public static function NationalVoteRange($app, $title, $data)
    {
        $app->addScript('
                var chartPartyVoteRange = new CanvasJS.Chart("chartContainer-PartyVoteRange", {
            	animationEnabled: true,
            	title:{
            		text: "'.$title.'"
            	},
            	axisY: {
            		title: "Vote percentage"
            	},
            	axisX: {
            	    labelMaxWidth: 200,
            	    labelWrap: false,
            	    labelAngle: 90
            	},
            	data: [{
            		type: "boxAndWhisker",
            		upperBoxColor: "#FFC28D",
            		lowerBoxColor: "#9ECCB8",
            		color: "black",
            		dataPoints: 
            			'.json_encode($data, JSON_NUMERIC_CHECK).'
            		
            	}]
            });
            chartPartyVoteRange.render();
            ');
        $app->addContent('
            <div id="chartContainer-PartyVoteRange" style="height: 500px; margin: 0px auto;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            ');
    }
    
    // Adds the party vote range across each province as box 'n whiskers chart
    public static function PartyVoteRange($app, $title, $data)
    {
        $app->addScript('
                var chartPartyVoteRange = new CanvasJS.Chart("chartContainer-PartyVoteRange", {
            	animationEnabled: true,
            	title:{
            		text: "'.$title.'"
            	},
            	axisY: {
            		title: "Vote percentage"
            	},
            	axisX: {
            	    labelMaxWidth: 200,
            	    labelWrap: false,
            	    labelAngle: 90
            	},
            	data: [{
            		type: "boxAndWhisker",
            		upperBoxColor: "#FFC28D",
            		lowerBoxColor: "#9ECCB8",
            		color: "black",
            		dataPoints: 
            			'.json_encode($data, JSON_NUMERIC_CHECK).'
            		
            	}]
            });
            chartPartyVoteRange.render();
            ');
        $app->addContent('
            <div id="chartContainer-PartyVoteRange" style="height: 500px; margin: 0px auto;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            ');
    }
    
    // Adds party votes by province as pie chart
    public static function PartyVotesByProvince($app, $title, $data)
    {
        $app->addScript('
                var chartPartyVotesByProvince = new CanvasJS.Chart("chartContainer-PartyVotesByProvince",
                {
                  title:{
                    text: "'.$title.'"
                  },
                  axisY:{
                      title: "Votes",
                      labelAutoFit: true,
                      labelFontSize: 15
                  },
                  axisX:{
                      title: "Province",
                      labelAutoFit: true,
                      labelWrap: false,
                      labelFontSize: 15
                  },
                  data: [
                  {
                    type: "pie",
                    dataPoints: '.json_encode($data, JSON_NUMERIC_CHECK).'
                  }
                  ]
                });
                
                chartPartyVotesByProvince.render();
            ');
        $app->addContent('
            <div id="chartContainer-PartyVotesByProvince" style="height: 600px; margin: 0px auto;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            ');
    }
}
