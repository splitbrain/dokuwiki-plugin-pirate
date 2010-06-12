<?php
/**
 * Pirate speak filter output
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Dougal Campbell
 * @link   http://dougal.gunters.org/blog/2004/08/30/text-filter-suite
 * @license GPL
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

// we inherit from the XHTML renderer instead directly of the base renderer
require_once DOKU_INC.'inc/parser/xhtml.php';

/**
 * The Renderer
 */
class renderer_plugin_pirate extends Doku_Renderer_xhtml {

    function canRender($format) {
      return ($format=='xhtml');
    }

    function cdata($text) {
        $this->doc .= $this->_xmlEntities($this->pirate_filter($text));
    }

    /**
     * This function takes an array of ('/pattern/' => 'replacement') pairs
     * and applies them all to $content.
     */
    function _array_apply_regexp($patterns,$content) {
        // Extract the values:
        $keys = array_keys($patterns);
        $values = array_values($patterns);

        // Replace the words:
        $content = preg_replace($keys,$values,$content);

        return $content;
    }

    function pirate_filter($content) {
        $patterns = array(
                '%\bmy\b%' => 'me',
                '%\bboss\b%' => 'admiral',
                '%\bmanager\b%' => 'admiral',
                '%\b[Cc]aptain\b%' => "Cap'n",
                '%\bmyself\b%' => 'meself',
                '%\byour\b%' => 'yer',
                '%\byou\b%' => 'ye',
                '%\bfriend\b%' => 'matey',
                '%\bfriends\b%' => 'maties',
                '%\bco[-]?worker\b%' => 'shipmate',
                '%\bco[-]?workers\b%' => 'shipmates',
                '%\bpeople\b%' => 'scallywags',
                '%\bearlier\b%' => 'afore',
                '%\bold\b%' => 'auld',
                '%\bthe\b%' => "th'",
                '%\bof\b%' =>  "o'",
                "%\bdon't\b%" => "dern't",
                '%\bdo not\b%' => "dern't",
                '%\bnever\b%' => "ne'er",
                '%\bever\b%' => "e'er",
                '%\bover\b%' => "o'er",
                '%\bYes\b%' => 'Aye',
                '%\bNo\b%' => 'Nay',
                '%\bYeah\b%' => 'Aye',
                '%\byeah\b%' => 'aye',
                "%\bdon't know\b%" => "dinna",
                "%\bdidn't know\b%" => "did nay know",
                "%\bhadn't\b%" => "ha'nae",
                "%\bdidn't\b%"=>  "di'nae",
                "%\bwasn't\b%" => "weren't",
                "%\bhaven't\b%" => "ha'nae",
                '%\bfor\b%' => 'fer',
                '%\bbetween\b%' => 'betwixt',
                '%\baround\b%' => "aroun'",
                '%\bto\b%' => "t'",
                "%\bit's\b%" => "'tis",
                '%\bwoman\b%' => 'wench',
                '%\bwomen\b%' => 'wenches',
                '%\blady\b%' => 'wench',
                '%\bwife\b%' => 'lady',
                '%\bgirl\b%' => 'lass',
                '%\bgirls\b%' => 'lassies',
                '%\bguy\b%' => 'lubber',
                '%\bman\b%' => 'lubber',
                '%\bfellow\b%' => 'lubber',
                '%\bdude\b%' => 'lubber',
                '%\bboy\b%' => 'lad',
                '%\bboys\b%' => 'laddies',
                '%\bchildren\b%' => 'little sandcrabs',
                '%\bkids\b%' => 'minnows',
                '%\bhim\b%' => 'that scurvey dog',
                '%\bher\b%' => 'that comely wench',
                '%\bhim\.\b%' => 'that drunken sailor',
                '%\bHe\b%' => 'The ornery cuss',
                '%\bShe\b%' => 'The winsome lass',
                "%\bhe's\b%" => 'he be',
                "%\bshe's\b%" => 'she be',
                '%\bwas\b%' => "were bein'",
                '%\bHey\b%' => 'Avast',
                '%\bher\.\b%' => 'that lovely lass',
                '%\bfood\b%' => 'chow',
                '%\bmoney\b%' => 'dubloons',
                '%\bdollars\b%' => 'pieces of eight',
                '%\bcents\b%' => 'shillings',
                '%\broad\b%' => 'sea',
                '%\broads\b%' => 'seas',
                '%\bstreet\b%' => 'river',
                '%\bstreets\b%' => 'rivers',
                '%\bhighway\b%' => 'ocean',
                '%\bhighways\b%' => 'oceans',
                '%\binterstate\b%' => 'high sea',
                '%\bprobably\b%' => 'likely',
                '%\bidea\b%' => 'notion',
                '%\bcar\b%' => 'boat',
                '%\bcars\b%' => 'boats',
                '%\btruck\b%' => 'schooner',
                '%\btrucks\b%' => 'schooners',
                '%\bSUV\b%' => 'ship',
                '%\bairplane\b%' => 'flying machine',
                '%\bjet\b%' => 'flying machine',
                '%\bmachine\b%' => 'contraption',
                '%\bdriving\b%' => 'sailing',
                '%\bunderstand\b%' => 'reckon',
                '%\bdrive\b%' => 'sail',
                '%\bdied\b%' => 'snuffed it',
                '/ing\b/' => "in'",
                '/ings\b/' => "in's",
                // These next two do cool random substitutions
                '/(\.\s)/e' => 'renderer_plugin_pirate::avast("$0",3)',
                '/([!\?]\s)/e' => 'renderer_plugin_pirate::avast("$0",2)', // Greater chance after exclamation
                );

        // Replace the words:
        $content = $this->_array_apply_regexp($patterns,$content);

        return $content;
    }

    /**
     * support function for pirate()
     * this could probably be refactored to make it more generic, allowing
     * different filters to pass their own patterns in.
     */
    function avast($stub = '',$chance = 5) {
        $shouts = array(
                    ", avast$stub",
                    "$stub Ahoy!",
                    ", and a bottle of rum!",
                    ", by Blackbeard's sword$stub",
                    ", by Davy Jones' locker$stub",
                    "$stub Walk the plank!",
                    "$stub Aarrr!",
                    "$stub Yaaarrrrr!",
                    ", pass the grog!",
                    ", and dinna spare the whip!",
                    ", with a chest full of booty$stub",
                    ", and a bucket o' chum$stub",
                    ", we'll keel-haul ye!",
                    "$stub Shiver me timbers!",
                    "$stub And hoist the mainsail!",
                    "$stub And swab the deck!",
                    ", ye scurvey dog$stub",
                    "$stub Fire the cannons!",
                    ", to be sure$stub",
                    ", I'll warrant ye$stub",
                    ", on a dead man's chest!",
                    "$stub Load the cannons!",
                    "$stub Prepare to be boarded!",
                    ", I'll warrant ye$stub",
                    "$stub Ye'll be sleepin' with the fishes!",
                    "$stub The sharks will eat well tonight!",
                    "$stub Oho!",
                    "$stub Fetch me spyglass!",
                    );

        shuffle($shouts);

        return (((1 == rand(1,$chance))?array_shift($shouts):$stub) . ' ');
    }
}

?>
