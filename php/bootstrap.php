<?php
// CanadianVoteStatistics bootstrap file

// Diversion:

// Source: **Why The Phrase 'Pull Yourself Up By Your Bootstraps' Is Nonsense**
// The interpretation of the phrase as we know it today is quite different
// from its original meaning.

// https://www.huffingtonpost.ca/entry/pull-yourself-up-by-your-bootstraps-nonsense_n_5b1ed024e4b0bbb7a0e037d4

// Etymologist Barry Popik and linguist and lexicographer Ben Zimmer have cited
// an American newspaper snippet from Sept. 30, 1834 as the earliest published
// reference to lifting oneself up by one’s bootstraps. A month earlier, a man
// named Nimrod Murphree announced in the Nashville Banner that he had
// “discovered perpetual motion.” The Mobile Advertiser picked up this tidbit
// and published it with a snarky response ridiculing his claim: “Probably Mr.
// Murphree has succeeded in handing himself over the Cumberland river, or a
// barn yard fence, by the straps of his boots.”
//
// “Bootstraps were a typical feature of boots that you could pull on in the act
// of putting your boots on, but of course bootstraps wouldn’t actually help you
// pull yourself over anything,” Zimmer told HuffPost. “If you pulled on them,
// it would be physically impossible to get yourself over a fence. The original
// imagery was something very ludicrous, as opposed to what we mean by it today
// of being a self-made man.”

namespace CanadianVoteStatisticsApp;

// Include PHP files
include_once('php/CanadianVoteStatisticsApp.php');

include_once('controllers/ElectionController.php');
include_once('models/Election.php');
include_once('views/ElectionView.php');

include_once('controllers/HomeController.php');

// Create app object (router)
$app = new CanadianVoteStatisticsApp();
