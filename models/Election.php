<?php
namespace CanadianVoteStatisticsApp;

// Election class model
//
// Loads relevant election data from the database.

class Election
{
    // Returns an array for party slugs.
    public static function Parties($app, $election)
    {
        $parties = $app->dbQuery(
            "SELECT DISTINCT party_slug
            FROM votes_by_province
            ORDER BY party_slug
            ;"
        );
        // WHERE election = '$election';");
        if ($parties) {
            while ($electionRow = $parties->fetch_object()) {
                $result[]=$electionRow->party_slug;
            }
            $parties->close();
        }
        return $result;
    }
    
    // returns the vote total['y'] for each party['label']
    // in array for CanvasJS API.
    public static function ElectionResults($app, $election)
    {
        $data = $app->dbQuery("SELECT party_slug, vote_total FROM votes_by_province WHERE election_id = '$election' ORDER BY vote_total ;");
        if ($data) {
            // Cycle through results
            while ($current = $data->fetch_object()) {
                $party['y'] = $current->vote_total;
                $party['label'] = $current->party_slug;
                $result[] = $party;
            }
            // Free result set
            $data->close();
            return $result;
        } else {
            return false;
        }
    }

    // Get's the votes for $party in $election broken down by provice
    // formatted for CanvasJS API.
    public static function PartyVotesByProvince($app, $election, $party)
    {
        $partyVotes = $app->dbQuery(
            "SELECT *
            FROM votes_by_province
            WHERE election_id = '$election'
            AND party_slug = '$party';"
        );
        if ($partyVotes) {
            $votes = $partyVotes->fetch_object();
            $result = [
            ['y'=>$votes->nl_vote_tally, 'label'=>'nl'],
            ['y'=>$votes->pe_vote_tally, 'label'=>'pe'],
            ['y'=>$votes->ns_vote_tally, 'label'=>'ns'],
            ['y'=>$votes->nb_vote_tally, 'label'=>'nb'],
            ['y'=>$votes->qc_vote_tally, 'label'=>'qc'],
            ['y'=>$votes->on_vote_tally, 'label'=>'on'],
            ['y'=>$votes->mb_vote_tally, 'label'=>'mb'],
            ['y'=>$votes->sk_vote_tally, 'label'=>'sk'],
            ['y'=>$votes->ab_vote_tally, 'label'=>'ab'],
            ['y'=>$votes->bc_vote_tally, 'label'=>'bc'],
            ['y'=>$votes->yt_vote_tally, 'label'=>'yt'],
            ['y'=>$votes->nt_vote_tally, 'label'=>'nt'],
            ['y'=>$votes->nu_vote_tally, 'label'=>'nu']
            ];
            $partyVotes->close();
        }
        return $result;
    }
    
    // protected function getVotesByProvince ($app, $election) {
    //     $votesByProvince = $this->app->dbQuery(
    //         "SELECT *
    //         FROM votes_by_province
    //         ORDER BY tally_total
    //         WHERE election = '$election';");
    //     if($votesByProvince){
    //         while ($row = $votesByProvince->fetch_object()){
    //             $currentParty['y']=$row->tally_total;
    //             $currentParty['label']=$row->party_slug;
    //             $result[]=$party;
    //         }
    //         $votesByProvince->close();
    //         //$db->next_result();
    //     }
    //     return $result;
    // }
    
    // Finds each party's national riding quartiles and formats for CanvasJS
    public static function NationalVoteRange($app, $election, $parties)
    {
        foreach ($parties as $party) {
            $result = Election::findNationalQuartile($app, $election, $party);
            // Parties with few candidates don't have quartiles
            // Create quartiles by duplicating data
            for ($i=sizeof($result)+1; $i<=4; $i++) {
                $result[$i]['min'] = $result[$i]['max'] = $result[$i-1]['max'];
            }
            
            $partyData[] = ['label'=>ucfirst(str_replace("-", " ", $party)), 'y'=> [
                            $result[1]['min'],
                            $result[1]['max'],
                            $result[4]['min'],
                            $result[4]['max'],
                            $result[3]['min']
                            ]];
        }
        return $partyData;
    }
    
    // Get $party's $election riding quartiles for each province
    // and format for CanvasJS
    public static function PartyVoteRange($app, $election, $party)
    {
        $result = Election::findPartyQuartile($app, $election, $party);
        
        foreach ($result as $province=>$provincialResult) {
            // Parties with few candidates don't have quartiles
            // Create quartiles by duplicating data
            for ($i=sizeof($provincialResult)+1; $i<=4; $i++) {
                $provincialResult[$i]['min'] = $provincialResult[$i]['max'] = $provincialResult[$i-1]['max'];
            }
            $provincialData[] = ['label'=>$province, 'y'=> [
                            $provincialResult[1]['min'],
                            $provincialResult[1]['max'],
                            $provincialResult[4]['min'],
                            $provincialResult[4]['max'],
                            $provincialResult[3]['min']
                    ]];
        }
        return $provincialData;
    }
    
    // Returns an array of parties for a given $election
    public static function getListOfParties($app, $election)
    {
        $partyNames = $this->app->dbQuery(
            "SELECT partyName
            FROM votes_by_province
            ORDER BY partyName
            WHERE election = '$election';"
        );
        if ($partyNames) {
            while ($partyRow = $partyNames->fetch_object()) {
                $parties[]=$partyRow->party_slug;
            }
            $partyNames->close();
        }
        return $parties;
    }
    
    // Returns a list of elections in the database.
    public static function Elections($app)
    {
        $allElections = $app->dbQuery(
            "SELECT DISTINCT election_id
            FROM votes_by_province
            ORDER BY election_id
            ;"
        );
        if ($allElections) {
            while ($electionRow = $allElections->fetch_object()) {
                $electionsResult[]=$electionRow->election_id;
            }
            $allElections->close();
        }
        return $electionsResult;
    }
    
    // Find national quartiles for a specific party
    // Load $election the riding quartile boundaries for one $party
    // and return as array.
    public static function findNationalQuartile($app, $election, $party)
    {
        $data = $app->dbQuery("
            SELECT
                p.party_slug as party,
                p.quartile,
                MIN(p.voters_perc) as min_perc,
                MAX(p.voters_perc) as max_perc,
                COUNT(p.candidate_name) as candidate_count
            FROM
                (
                SELECT
                    candidate_name,
                    party_slug,
                    voters_perc,
                    ntile(4) OVER(
                    ORDER BY
                        voters_perc
                    ) AS quartile
                FROM
                    `candidates`
                WHERE
                    `party_slug` = '$party' AND `election_id` = '$election'
                ) AS p
            GROUP BY
                quartile
            ");
        
        while ($current = $data->fetch_object()) {
            $quart[$current->quartile] = [
                'min' => !is_null($current->min_perc) ? $current->min_perc : 0,
                'max' => !is_null($current->max_perc) ? $current->max_perc : 0
                ];
        }
        return $quart;
    }
    
    // Find all provincial quartiles for a party.
    // Given a $party and $election load the riding quartile boundaries for
    // each province and returns as array.
    public static function findPartyQuartile($app, $election, $party)
    {
        $data = $app->dbQuery("
            SELECT
                p.party_slug as party,
                p.province_id,
                p.quartile,
                MIN(p.voters_perc) as min_perc,
                MAX(p.voters_perc) as max_perc,
                COUNT(p.candidate_name) as candidate_count
            FROM
                (
                SELECT
                    candidate_name,
                    province_id,
                    party_slug,
                    voters_perc,
                    ntile(4) OVER(
                    PARTITION BY
                        province_id
                    ORDER BY
                        voters_perc
                    ) AS quartile
                FROM
                    `candidates`
                WHERE
                    `party_slug` = '$party' AND `election_id` = '$election'
                ) AS p
            GROUP BY
                province_id, quartile
            ");
        
        while ($current = $data->fetch_object()) {
            $quart[$current->province_id][$current->quartile] = [
                'min' => !is_null($current->min_perc) ? $current->min_perc : 0,
                'max' => !is_null($current->max_perc) ? $current->max_perc : 0
                ];
        }
        return $quart;
    }
}
